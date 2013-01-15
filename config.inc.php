<?php
/*
 * Default account parameters
 * client_id ans client_secret have to be created at http://dev.netatmo.com/dev/createapp
 */
$NAusername = "____EMAIL_ACCOUNT_HERE____";
$NApwd      = "____PASS_ACCOUNT_HERE____";
$NAconfig   = array(
    'client_id'     => '____API_CLIENT_ID_HERE____',
    'client_secret' => '____API_CLIENT_SECRET_HERE____',
);


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
//define( 'WIDGET_LOCALE' , 'fr_FR' );


/*
 * APC parameters
 * No need to change this
 */
$NAcachekey = "netatmo-weather-station-api";
$NAttl      = 5*60; // every 5 minutes


