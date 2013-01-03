<?php
require_once("config.inc.php");
require_once("Netatmo/NAApiClient.php");

$NAcachekey = "netatmo-weather-station-api";
$NAttl      = 5*60; // every 5 minutes


// Clear APC cache if user wants
//
if (isset($_GET['cc'])) {
	if (function_exists('apc_exists')) {
		apc_delete($NAcachekey);
	}
}


// Return array with Netatmo informations
//
function get_netatmo() {
	global $NAconfig, $NAusername, $NApwd, $NAcachekey, $NAttl;

	if (function_exists('apc_exists')) {
		if (apc_exists($NAcachekey)) {
			$return = @unserialize(apc_fetch($NAcachekey));
			if (is_array($return)) {
				if (count($return)>0) {
					return $return;
				}
			}
		}
	}

	$return = array();

	/*
	Netatmo job
	 */
	$client = new NAApiClient($NAconfig);
	$client->setVariable("username", $NAusername);
	$client->setVariable("password", $NApwd);
	try {
	    $tokens = $client->getAccessToken();
		$refresh_token = $tokens["refresh_token"];
		$access_token  = $tokens["access_token"];
	}
	catch(NAClientException $ex) {
		return $ex;
	}


    $device_id = '';
	try {
	    $deviceList = $client->api("devicelist");
	    if (is_array($deviceList["devices"])) {
	    	foreach ($deviceList["devices"] as $device) {

	    		$device_id = $device["_id"];

				$params = array(
					"scale" =>"max",
					"type"=>"Temperature,Humidity,Co2,Noise",
					"date_end"=>"last",
					"device_id"=>$device_id
				);

    			$res = $client->api("getmeasure",'GET',$params);
    			if(isset($res[0]) && isset($res[0]["beg_time"])) {
    				$time = $res[0]["beg_time"];
    				$return[$device_id]['results'] = $res[0]["value"][0];
    				$return[$device_id]['name']    = $device["module_name"];
    				$return[$device_id]['station'] = $device["station_name"];
    				$return[$device_id]['type']    = $device["type"];
    				$return[$device_id]['time']    = $res[0]["beg_time"];
    			}
	    	}
	    }
	}
	catch(NAClientException $ex) {
		return $ex;
	}

	if ($device_id!='') {
	    if (is_array($deviceList["modules"])) {
	    	foreach ($deviceList["modules"] as $module) {
	    		try {
		    		$module_id = $module["_id"];
		    		$device_id = $module["main_device"];

					$params = array(
						"scale" =>"max",
						"type"=>"Temperature,Humidity",
						"date_end"=>"last",
						"device_id"=>$device_id,
						"module_id"=>$module_id
					);

	    			$res = $client->api("getmeasure",'GET',$params);
	    			if(isset($res[0]) && isset($res[0]["beg_time"])) {
	    				$return[$device_id]['m'][$module_id]['results'] = $res[0]["value"][0];
	    				$return[$device_id]['m'][$module_id]['name']    = $module["module_name"];
	    				$return[$device_id]['m'][$module_id]['time']    = $res[0]["beg_time"];
	    			}
				}
				catch(NAClientException $ex) {
					return $ex;
				}
	    	}
	    }
	}

	if (function_exists('apc_exists')) {
		apc_store($NAcachekey,serialize($return),$NAttl);
	}
    return $return;
}

