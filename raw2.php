<?php
// This script is just an example to show you how to customize yourself

// This include will generate an array named 'result' with formatted informations about your devices and external modules
require_once( 'inc' . DIRECTORY_SEPARATOR . 'global.inc.php' );

// Uncomment this to debug and view the main object
//var_dump( $result );
//die();

// Remove the scales key of the object
unset( $result['scales'] );

// Get the first module
$data = current( $result );

// Display all informations
var_dump( $data['dashboard'] );

// Display only min_temp
echo "Min temp is : " . $data['dashboard']['min_temp'];
