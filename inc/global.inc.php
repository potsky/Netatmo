<?php
require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config.inc.php');
$userfile = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config.user.inc.php';
if (file_exists($userfile)) {
	require_once ($userfile);
}

require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . 'functions.inc.php');
require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Netatmo' . DIRECTORY_SEPARATOR . 'NAApiClient.php');

/*
 * Set remote account if feature is enabled in config.inc.php via constant ALLOW_REMOTE_ACCOUNTING
 */
if (defined('ALLOW_REMOTE_ACCOUNTING')) {
	if (ALLOW_REMOTE_ACCOUNTING === true) {
		if (isset($_GET['nu'])
			 && isset($_GET['np'])
			 && isset($_GET['nc'])
			 && isset($_GET['ns'])
		) {

			$NAusername = $_GET['nu'];
			$NApwd      = $_GET['np'];
			$NAconfig   = array(
				'client_id'     => $_GET['nc'],
				'client_secret' => $_GET['ns'],
			);

			// Change the APC key too
			// Set the md5 of passwords to improve user experience when they change them and want to see it does not work anymore in real time
			define('NETATMO_CACHE_KEY', NETATMO_CACHE_DEFAULT_KEY . '-' . @$_GET['scd'] . '-' . @$_GET['scm'] . '-' . $_GET['nu'] . '-' . $_GET['nc'] . md5($_GET['np'] . $_GET['ns']));
		}
	}
}
if (!defined('NETATMO_CACHE_KEY')) {
	define('NETATMO_CACHE_KEY', NETATMO_CACHE_DEFAULT_KEY . '-' . @$_GET['scd'] . '-' . @$_GET['scm']);
}

/*
 * Clear APC cache if needed
 */
if (isset($_GET['cc']))
{
	if (function_exists('apc_exists'))
	{
		apc_delete(NETATMO_CACHE_KEY);
	}
}

/*
 * Define locale and translation
 */
$locale = '';

if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
{
	@list($locale, $dumb) = @explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE'], 2);
}

if (defined('WIDGET_LOCALE'))
{
	$locale = WIDGET_LOCALE;
}

if (isset($_GET['l']))
{
	$locale = $_GET['l'];
}

$locale       = str_replace('-', '_', $locale);
@list($a, $b) = explode('_', $locale);
$locale       = strtolower($a) . '_' . strtoupper($b);

if (function_exists('bindtextdomain'))
{
	putenv('LC_ALL=' . $locale);
	putenv('LANGUAGE=' . $locale);
	if ($a == 'fr')
	{
		setlocale(LC_ALL, $locale, $locale . '.utf8', 'fra');
	}
	else if ($a == 'de')
	{
		setlocale(LC_ALL, $locale, $locale . '.utf8', 'deu_deu', 'de', 'ge');
	}
	else {
		setlocale(LC_ALL, $locale, $locale . '.utf8');
	}

	bindtextdomain('messages', './lang');
	bind_textdomain_codeset('messages', 'UTF-8');
	textdomain('messages');
}
else
{
	function gettext($t)
	{
		return $t;
	}
}

/*
 * Query Netatmo API and retrieve user informations
 */
$result = get_netatmo(@$_GET['scd'], @$_GET['scm']);


if (isset($result->result['error']))
{
	// Uncomment this to have more informations. Be carful, your password is in plain text!
	// var_dump($result);
	die(print_r($result->result['error'], true));
}

if (!is_array($result))
{
	// Uncomment this to have more informations. Be carful, your password is in plain text!
	// var_dump($result);
	die(__('API format error'));
}

if (!isset($result['user']))
{
	// Uncomment this to have more informations. Be carful, your password is in plain text!
	// var_dump($result);
	die(__('API format error'));
}

$user = $result['user'];
unset($result['user']);

/*
 * Define Unit System
 */
if (isset($user["administrative"]["unit"]))
{
	$unitmetric = $user["administrative"]["unit"];
}
if (defined('WIDGET_UNIT_METRIC'))
{
	$unitmetric = ((int) WIDGET_UNIT_METRIC) % 2;
}
if (isset($_GET['u']))
{
	$unitmetric = ((int) $_GET['u']) % 2;
}
if (!isset($unitmetric))
{
	$unitmetric = 0;
}

/*
 * Define Pressure Unit
 */
if (isset($user["administrative"]["pressureunit"]))
{
	$unitpressure = $user["administrative"]["pressureunit"];
}
if (defined('WIDGET_UNIT_PRESSURE'))
{
	$unitpressure = ((int) WIDGET_UNIT_PRESSURE) % 3;
}
if (isset($_GET['up']))
{
	$unitpressure = ((int) $_GET['up']) % 3;
}
if (!isset($unitpressure))
{
	$unitpressure = 0;
}


/**
 * Count of icons in css for wind
 * First is 0° (trigo right)
 * 90° is top
 * @var  integer
 */
$wind_icons_count = 16;


// Add mock= in the URL to get mock data instead of real data
//
if ( isset( $_GET['mock'] ) )
{
	$result = include 'inc'. DIRECTORY_SEPARATOR . 'mock.php';
}



