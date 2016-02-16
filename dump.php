<?php
$_GET['cc'] = 1;
define( 'DEBUG' , 1 );

echo '<html><body>';

require_once 'inc'. DIRECTORY_SEPARATOR . 'global.inc.php';

echo '<pre>';
var_dump( $result );
