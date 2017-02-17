<?php
// This include will generate an array named 'result' with formatted informations about your devices and external modules
require_once( 'inc' . DIRECTORY_SEPARATOR . 'global.inc.php' );

header( 'Content-type: text/plain; charset=UTF-8' );

// Only parse informations if 'result' is an array
if ( is_array( $result ) )
{
	// Only parse informations if 'result' is not empty
	if ( count( $result ) > 0 )
	{
		// Uncomment the line below to see the structure of the 'result' array in your browser
		// var_dump($result); die();

		// For all devices -> in my case, I have 3 netamos : at home, at office and at my parents home
		foreach ( $result as $data )
		{
			// get all external modules for the current device
			if ( isset( $data[ 'm' ] ) && is_array( $data[ 'm' ] ) )
			{
				foreach ( $data[ 'm' ] as $moduleid => $datam )
				{
					echo "id|" . $moduleid . "\n";
					echo "name|" . @$datam[ 'name' ] . "\n";
					echo "time|" . @$datam[ 'time' ] . "\n";

					foreach ( array( 'dashboard' , 'results' , 'misc' ) as $key )
					{
						if ( ( isset( $datam[ $key ] ) ) && is_array( $datam[ $key ] ) )
						{
							foreach ( $datam[ $key ] as $k => $v )
							{
								echo $k . "|" . $v . "\n";
							}
						}
					}
				}
			}
		}
	}
	else
	{
		echo __( 'No device' );
	}
}
else
{
	echo $result->result[ 'error' ][ 'message' ];
}
