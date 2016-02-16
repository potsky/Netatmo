<?php
/*
All commented parameters should be set in the config.user.inc.php file.
Create a config.user.inc.php file just here in the root directory and put your custom settings.
This file is in the .gitignore file so it will not be removed when pulling new versions from GitHub.
 */

/*
 * Default account parameters
 * client_id ans client_secret have to be created at http://dev.netatmo.com/dev/createapp
 */
//$NAusername = "____EMAIL_ACCOUNT_HERE____";
//$NApwd      = "____PASS_ACCOUNT_HERE____";
//$NAconfig   = array(
//    'client_id'     => '____API_CLIENT_ID_HERE____',
//    'client_secret' => '____API_CLIENT_SECRET_HERE____',
//);


/*
 * Allow guest to call index.php with GET account parameters to display other weather stations rather than this below
 * GET parameters are :
 * - nu : Netatmo Username      > overrides PHP variable $NAusername
 * - np : Netatmo Passsword     > overrides PHP variable $NApwd
 * - nc : Netatmo Client ID     > overrides PHP variable $NAconfig['client_id']
 * - ns : Netatmo Client Config > overrides PHP variable $NAconfig['client_secret']
 */
//define( 'ALLOW_REMOTE_ACCOUNTING' , true );


/*
 * Override locale sent by the browser
 * If a GET parameter local is set, it takes precedence on this
 *
 * The precedence order is :
 * - browser
 * - constant below
 * - GET parameter is the dude
 */
//define( 'WIDGET_LOCALE' , 'nl_NL' );
//define( 'WIDGET_LOCALE' , 'en_GB' );
//define( 'WIDGET_LOCALE' , 'fr_FR' );
//define( 'WIDGET_LOCALE' , 'de_DE' );
//define( 'WIDGET_LOCALE' , 'it_IT' );
//define( 'WIDGET_LOCALE' , 'pl_PL' );
//define( 'WIDGET_LOCALE' , 'es_ES' );


/*
 * Override netatmo Unit user preferences set from your device
 * If a GET parameter local is set, it takes precedence on this
 *
 * The precedence order is :
 * - Netatmo User preference
 * - Constant below
 * - GET parameter is the dude
 *
 * accepted values are :
 * - 0 for Metric system (°C/mm , km/h)
 * - 1 for US system (°F/inch , mph)
 */
//define( 'WIDGET_UNIT_METRIC' , 0 );
//define( 'WIDGET_UNIT_METRIC' , 1 );


/*
 * Override netatmo Pressure Unit user preferences set from your device
 * If a GET parameter local is set, it takes precedence on this
 *
 * The precedence order is :
 * - Netatmo User preference
 * - Constant below
 * - GET parameter is the dude
 *
 * accepted values are :
 * - 0 for mbar
 * - 1 for inHg
 * - 2 for mmHg
 */
//define( 'WIDGET_UNIT_PRESSURE' , 0 );
//define( 'WIDGET_UNIT_PRESSURE' , 1 );
//define( 'WIDGET_UNIT_PRESSURE' , 2 );


/*
 * You can change this
 */
//define( 'NETATMO_DEVICE_DEFAULT_VALUES' , 'Humidity,CO2,Noise' );
//define( 'NETATMO_MODULE_DEFAULT_VALUES' , 'Humidity,Rain,RainSum,sum_rain_1,sum_rain_24,WindStrength,WindAngle,GustStrength,GustAngle' );


/***************************************************************************
 * Internal
 * No need to change this
 * Do not copy this in your config.user.inc.php file
 **************************************************************************/
define( 'NETATMO_WIDGET_VERSION'        , '0.6' );
define( 'NETATMO_DEVICE_SCALES'         , '1day,1week,1month' );
define( 'NETATMO_DEVICE_TYPE_MAIN'      , 'Temperature,Co2,Humidity,Noise,Pressure' );
define( 'NETATMO_DEVICE_TYPE_MISC'      , 'min_temp,date_min_temp,max_temp,date_max_temp,min_hum,date_min_hum,max_hum,date_max_hum,min_pressure,date_min_pressure,max_pressure,date_max_pressure,min_noise,date_min_noise,max_noise,date_max_noise' );
define( 'NETATMO_MODULE_TYPE_MAIN'      , 'Temperature,Humidity,CO2,Rain' );
define( 'NETATMO_MODULE_TYPE_MISC'      , 'min_temp,date_min_temp,max_temp,date_max_temp,min_hum,date_min_hum,max_hum,date_max_hum,sum_rain,sum_rain_1,sum_rain_24,WindStrength,WindAngle,GustStrength,GustAngle' );
define( 'NETATMO_CACHE_DEFAULT_KEY'     , "netatmo-weather-station-api-" . NETATMO_WIDGET_VERSION );
define( 'NETATMO_CACHE_TTL'             , 5*60 );







