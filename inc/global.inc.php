<?php
require_once( dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config.inc.php' );
require_once( dirname(__FILE__) . DIRECTORY_SEPARATOR . 'functions.inc.php' );
require_once( dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Netatmo' . DIRECTORY_SEPARATOR . 'NAApiClient.php' );


/*
 * Set remote account if feature is enabled i config.inc.php via constant ALLOW_REMOTE_ACCOUNTING
 */
if ( defined( 'ALLOW_REMOTE_ACCOUNTING' ) ) {
	if ( ALLOW_REMOTE_ACCOUNTING === true ) {
		if ( 	isset( $_GET['nu'] )
			&&	isset( $_GET['np'] )
			&&	isset( $_GET['nc'] )
			&&	isset( $_GET['ns'] )
		) {

			$NAusername = $_GET['nu'];
			$NApwd      = $_GET['np'];
			$NAconfig   = array(
			    'client_id'     => $_GET['nc'],
			    'client_secret' => $_GET['ns'],
			);

			// Change the APC key too
			// Set the md5 of passwords to improve user experience when they change them and want to see it does not work anymore in real time
			$NAcachekey = $NAcachekey . '-' . $_GET['nu'] . '-' . $_GET['nc'] . md5( $_GET['np'] . $_GET['ns'] );
		}
	}
}




/*
 * Clear APC cache if needed
 */
if ( isset( $_GET['cc'] ) ) {
	if ( function_exists( 'apc_exists' ) ) {
		apc_delete( $NAcachekey );
	}
}




/*
 * Define locale and translation
 */
$locale = '';

if ( isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {
	@list($locale,$dumb) = @explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE'],2);
}

if ( defined( 'WIDGET_LOCALE' ) ) {
	$locale = WIDGET_LOCALE;
}

if ( isset( $_GET['l'] ) ) {
	$locale = $_GET['l'];
}

$locale      = str_replace('-','_',$locale);
@list($a,$b) = explode('_',$locale);
$locale      = strtolower($a).'_'.strtoupper($b);

putenv( 'LC_ALL=' . $locale );
putenv( 'LANGUAGE=' . $locale );
setlocale( LC_ALL , $locale );
bindtextdomain( 'messages' , './lang' );
bind_textdomain_codeset( 'messages' , 'UTF-8' );
textdomain( 'messages' );



/*
 * Query Netatmo API nd retrieve user informations
 */
$result = get_netatmo();
$user   = $result['user'];
unset( $result['user'] );



/*
 * Define Unit
 */
if ( isset( $user["administrative"]["unit"] ) ) {
	$unit = $user["administrative"]["unit"];
}

if ( defined( 'WIDGET_UNIT' ) ) {
	$unit = ( (int)WIDGET_UNIT ) % 2;
}

if ( isset( $_GET['u'] ) ) {
	$unit = ( (int)$_GET['u'] ) % 2;
}





