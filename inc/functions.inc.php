<?php
/**
 * Simply return a localized text or empty string if the key is empty
 * Useful when localize variable which can be empty
 *
 * @param    string       $text          the text key
 * @return   string                      the translation
 */
function __($text)
{
    if( empty( $text ) )

        return '';
    else
        return gettext($text);
}

/**
 * Simply echo a localized text
 *
 * @param    string       $text          the text key
 * @return   void
 */
function _e($text)
{
    echo __($text);
}

/**
 * Return a color between RGB($r_min,$g_min,$b_min) and RGB($r_max,$g_max,$b_max) according to value $c where min value is $min and max value is $mac
 *
 * @param    string        $c                the value
 * @param    integer       $min=3            the min value corresponding to the min color
 * @param    integer       $max=30           the max value corresponding to the max color
 * @param    integer       $r_min=0          min red
 * @param    integer       $g_min=128        min green
 * @param    integer       $b_min=255        min blue
 * @param    integer       $r_max=255        max red
 * @param    integer       $g_max=0          max green
 * @param    integer       $b_max=0          max blue
 * @return   string                          the css rgb element
 */
function get_color($c,$min=3,$max=30,$r_min=0,$g_min=128,$b_min=255,$r_max=255,$g_max=0,$b_max=0)
{
    $from_color = array($r_min,$g_min,$b_min);
    $to_color   = array($r_max,$g_max,$b_max);
    $from       = $min;
    $to         = $max;
    $d          = min($c,$to);
    $d          = max($d,$from);
    $c          = 'rgb(';
    for ($i=0; $i<3; $i++) {
        $a = $from_color[$i]+round(($d-$from)*($to_color[$i]-$from_color[$i])/($to-$from));
        $c.= ($i>0) ? ','.$a : $a;
    }

    return $c.')';
}

/**
 * Remove
 *
 * @param   string  $string     the string representation of the list
 * @param   string  $separator  the seperator char used to delimit items in the list
 * @param   array   $values     the array of items to remove
 *
 * @return  string              the string representation of the list with separator and without provided items
 */
function remove_unknown_values( $string , $separator = ',' , $values = array( 'sum_rain_1' , 'sum_rain_24' ) ) {
    return implode( $separator , array_diff( explode( $separator, $string ) , $values ) );
}

/**
 * Return array with Netatmo informations
 *
 * @return   mixed                          an array with all weather station values or an exception object if error happens
 */
function get_netatmo($scale_device = '1day' , $scale_module = '1day' )
{
    global $NAconfig, $NAusername, $NApwd;

    if ( ( ! isset( $NAusername ) ) || ( ! isset( $NApwd ) ) || ( ! isset( $NAconfig ) ) ||  ( ! isset( $NAconfig['client_id'] ) ) ||  ( ! isset( $NAconfig['client_secret'] ) ) ) {
        die("Oups! The widget is not configured! Take a look on this page : <a href='https://github.com/potsky/Netatmo#configuration'>https://github.com/potsky/Netatmo#configuration</a>");
    }

    if ( function_exists( 'apc_exists' ) ) {
        if ( apc_exists( NETATMO_CACHE_KEY ) ) {
            $return = @unserialize( apc_fetch( NETATMO_CACHE_KEY ) );
            if ( is_array( $return ) ) {
                if ( count( $return ) > 0 ) {
                    return $return;
                }
            }
        }
    }

    $scale_device = ( in_array( $scale_device , explode( ',' , NETATMO_DEVICE_SCALES ) ) ) ? $scale_device : '1day';
    $scale_module = ( in_array( $scale_module , explode( ',' , NETATMO_DEVICE_SCALES ) ) ) ? $scale_module : $scale_device;
    $return       = array(
        'scales' => array(
            'device' => $scale_device,
            'module' => $scale_module,
        )
    );

    /*
    Netatmo job
     */
    $client = new NAApiClient( $NAconfig );
    $client->setVariable("username", $NAusername);
    $client->setVariable("password", $NApwd);
    try {
        $tokens        = $client->getAccessToken();
        $refresh_token = $tokens["refresh_token"];
        $access_token  = $tokens["access_token"];
    } catch (NAClientException $ex) {
        return $ex;
    }

    $userinfo = array();
    try {
        $userinfo = $client->api("getuser");
    } catch (NAClientException $ex) {
        return $ex;
    }
    $return['user'] = $userinfo;

    $device_id = '';
    try {
        $deviceList = $client->api("devicelist");
        if (is_array($deviceList["devices"])) {

            foreach ($deviceList["devices"] as $device) {
                $device_id                       = $device["_id"];
                $return[$device_id]['dashboard'] = $device['dashboard_data'];
                $params                          = array(
                    "scale"     => "max",
                    "type"      => remove_unknown_values( NETATMO_DEVICE_TYPE_MAIN ),
                    "date_end"  => "last",
                    "device_id" => $device_id
                );
                $res = $client->api("getmeasure",'GET',$params);

                if ( ( @defined( 'DEBUG' ) ) && ( DEBUG === 1 ) ) {
                    echo '<pre>';
                    var_dump( NETATMO_DEVICE_TYPE_MAIN , $res);
                    echo '<hr/>';
                }

                if (isset($res[0]) && isset($res[0]["beg_time"])) {
                    $time = $res[0]["beg_time"];
                    $vals = explode( ',' , NETATMO_DEVICE_TYPE_MAIN );
                    foreach( $res[0]["value"][0] as $key => $value )
                        $return[$device_id]['results'][ $vals[$key] ] = $value;
                    $return[$device_id]['name']    = $device["module_name"];
                    $return[$device_id]['station'] = $device["station_name"];
                    $return[$device_id]['type']    = $device["type"];
                    $return[$device_id]['time']    = $res[0]["beg_time"];
                }

                $params = array(
                    "scale"     => $scale_device,
                    "type"      => remove_unknown_values( NETATMO_DEVICE_TYPE_MISC ),
                    "date_end"  => "last",
                    "device_id" => $device_id
                );
                $res = $client->api("getmeasure",'GET',$params);

                if ( ( @defined( 'DEBUG' ) ) && ( DEBUG === 1 ) ) {
                    echo '<pre>';
                    var_dump( NETATMO_DEVICE_TYPE_MISC , $res);
                    echo '<hr/>';
                }

                if (isset($res[0]) && isset($res[0]["beg_time"])) {
                    $vals = explode( ',' , NETATMO_DEVICE_TYPE_MISC );
                    foreach( $res[0]["value"][0] as $key => $value ) {
                        $return[$device_id]['misc'][ $vals[$key] ] = $value;
                    }
                }

            }
        }
    } catch (NAClientException $ex) {
        return $ex;
    }

    if ($device_id!='') {
        if (is_array($deviceList["modules"])) {
            foreach ($deviceList["modules"] as $module) {
                try {
                    $module_id                                        = $module["_id"];
                    $device_id                                        = $module["main_device"];
                    $return[$device_id]['m'][$module_id]['dashboard'] = $module['dashboard_data'];

                    $params = array(
                        "scale"     => "max",
                        "type"      => remove_unknown_values( NETATMO_MODULE_TYPE_MAIN ),
                        "date_end"  => "last",
                        "device_id" => $device_id,
                        "module_id" => $module_id
                    );

                    $res = $client->api("getmeasure",'GET',$params);

                    if ( ( @defined( 'DEBUG' ) ) && ( DEBUG === 1 ) ) {
                        echo '<pre>';
                        var_dump( NETATMO_MODULE_TYPE_MAIN , $res );
                        echo '<hr/>';
                    }

                    if (isset($res[0]) && isset($res[0]["beg_time"])) {
                        $vals = explode( ',' , NETATMO_MODULE_TYPE_MAIN );
                        foreach( $res[0]["value"][0] as $key => $value ) {
                            $return[$device_id]['m'][$module_id]['results'][ $vals[$key] ] = $value;
                        }
                        $return[$device_id]['m'][$module_id]['name']    = $module["module_name"];
                        $return[$device_id]['m'][$module_id]['time']    = $res[0]["beg_time"];
                    }

                    $params = array(
                        "scale"     => $scale_module,
                        "type"      => remove_unknown_values( NETATMO_MODULE_TYPE_MISC ),
                        "date_end"  => "last",
                        "device_id" => $device_id,
                        "module_id" => $module_id
                    );

                    $res = $client->api("getmeasure",'GET',$params);

                    if ( ( @defined( 'DEBUG' ) ) && ( DEBUG === 1 ) ) {
                        echo '<pre>';
                        var_dump( NETATMO_MODULE_TYPE_MISC , $res );
                        echo '<hr/>';
                    }

                    if (isset($res[0]) && isset($res[0]["beg_time"])) {
                        $vals = explode( ',' , NETATMO_MODULE_TYPE_MISC );
                        foreach( $res[0]["value"][0] as $key => $value ) {
                            $return[$device_id]['m'][$module_id]['misc'][ $vals[$key] ] = $value;
                        }
                    }
                } catch (NAClientException $ex) {
                    return $ex;
                }
            }
        }
    }

    if ( function_exists( 'apc_exists' ) ) {
        apc_store( NETATMO_CACHE_KEY , serialize( $return ) , NETATMO_CACHE_TTL );
    }

    return $return;
}

/**
 * Remove all - : and space from a mac address
 *
 * @param   string  $macid  the mac address
 *
 * @return  string          the value
 */
function sanitize_mac_id($macid)
{
    return str_replace( array(':',' ','-') , array('','','',) , $macid );
}

/**
 * Multubytes ucfirst
 *
 * @param   string  $string
 * @param   string  $encoding
 *
 * @return  string
 */
function mb_ucfirst( $string , $encoding = 'UTF-8' )
{
    $strlen = mb_strlen($string, $encoding);
    $firstChar = mb_substr($string, 0, 1, $encoding);
    $then = mb_substr($string, 1, $strlen - 1, $encoding);
    return mb_strtoupper($firstChar, $encoding) . $then;
}

/**
 * Convert var_dump output to php object/array/...
 *
 * source : http://stackoverflow.com/questions/3531857/convert-var-dump-of-array-back-to-array-variable
 *
 * @param   string  $str  the var_dump output
 *
 * @return  mixed         the PHP object
 */
function unvar_dump($str)
{
    if (strpos($str, "\n") === false) {
        //Add new lines:
        $regex = array(
            '#(\\[.*?\\]=>)#',
            '#(string\\(|int\\(|float\\(|array\\(|NULL|object\\(|})#',
        );
        $str = preg_replace($regex, "\n\\1", $str);
        $str = trim($str);
    }
    $regex = array(
        '#^\\040*NULL\\040*$#m',
        '#^\\s*array\\((.*?)\\)\\s*{\\s*$#m',
        '#^\\s*string\\((.*?)\\)\\s*(.*?)$#m',
        '#^\\s*int\\((.*?)\\)\\s*$#m',
        '#^\\s*bool\\(true\\)\\s*$#m',
        '#^\\s*bool\\(false\\)\\s*$#m',
        '#^\\s*float\\((.*?)\\)\\s*$#m',
        '#^\\s*\[(\\d+)\\]\\s*=>\\s*$#m',
        '#\\s*?\\r?\\n\\s*#m',
    );
    $replace = array(
        'N',
        'a:\\1:{',
        's:\\1:\\2',
        'i:\\1',
        'b:1',
        'b:0',
        'd:\\1',
        'i:\\1',
        ';'
    );
    $serialized = preg_replace($regex, $replace, $str);
    $func = create_function(
        '$match',
        'return "s:".strlen($match[1]).":\\"".$match[1]."\\"";'
    );
    $serialized = preg_replace_callback(
        '#\\s*\\["(.*?)"\\]\\s*=>#',
        $func,
        $serialized
    );
    $func = create_function(
        '$match',
        'return "O:".strlen($match[1]).":\\"".$match[1]."\\":".$match[2].":{";'
    );
    $serialized = preg_replace_callback(
        '#object\\((.*?)\\).*?\\((\\d+)\\)\\s*{\\s*;#',
        $func,
        $serialized
    );
    $serialized = preg_replace(
        array('#};#', '#{;#'),
        array('}', '{'),
        $serialized
    );

    return unserialize($serialized);
}
