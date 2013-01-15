<?php
/*
Name: Netatmo PHP Widget
URI: https://www.potsky.com/code/netatmo/
Description: A PHP Widget to display weather information of several locations and Netatmo modules
Version: 0.1.1
Date: 2013-01-03
Author: potsky
Author URI: http://www.potsky.com/about/

Copyright © 2012 Raphael Barbate ( potsky ) <potsky@me.com> [http://www.potsky.com]
This file is part of Netatmo PHP Widget.

Netatmo PHP Widget is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License.

Netatmo PHP Widget is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Netatmo PHP Widget.  If not, see <http://www.gnu.org/licenses/>.
*/

require_once( 'inc' . DIRECTORY_SEPARATOR . 'global.inc.php' );
$result = get_netatmo();


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Netatmo</title>
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Cache-control" content="no-cache" />
	<meta http-equiv="Expires" content="-1" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="shortcut icon" href="favicon.ico" />
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<?php
$when = sprintf( __('%1$s %2$s %3$s %4$s at %5$s:%6$s'), 
	utf8_encode( ucfirst( strftime( '%A' ) ) ),
	strftime( '%e' ),
	utf8_encode( ucfirst( strftime( '%B' ) ) ),
	strftime( '%Y' ),
	strftime( '%H' ),
	strftime( '%M' )
);

if ( is_array( $result ) ) {

	if ( count( $result ) > 0 ) {

		foreach ( $result as $data ) {

			$name = $data['station'];

			if ( isset( $_GET['n'] ) )
				if ( strtolower( $name ) != strtolower( $_GET['n'] ) ) 
					continue;

			if ( (int) $data['results'][1] < 40 )
				$humiditycolor = get_color( (int) $data['results'][1] , 20 , 40 , 255 , 0 , 0 , 0 , 255 , 0 );
			else if ( (int) $data['results'][1]>50 )
				$humiditycolor = get_color( (int) $data['results'][1] , 50 , 80 , 0 , 255 , 0 , 255 , 0 , 0 );
			else
				$humiditycolor = 'rgb( 0,255,0 )';

			$when = sprintf( __('measured %1$s %2$s at %5$s:%6$s'), 
				utf8_encode( ucfirst( strftime( '%A' ,$data['time'] ) ) ),
				strftime( '%e' ,$data['time'] ),
				utf8_encode( ucfirst( strftime( '%B' ,$data['time'] ) ) ),
				strftime( '%Y' ,$data['time'] ),
				strftime( '%H' ,$data['time'] ),
				strftime( '%M' ,$data['time'] )
			);

			echo '<table>';
			echo '<tr><td colspan="2" id="title" align="center">' . $name . '</td></tr>';
			echo '<tr>';
			echo '	<td id="inside" valign="top" align="left">';
			echo '		<table>';
			echo '			<tr><th align="left"><img src="img/inside.png"/></th>';
			echo '              <td class="te" style="color:' . get_color( (int) $data['results'][0] ) . '">' . sprintf(__('%s°C'),$data['results'][0]) . '</td>';
			echo '          </tr>';
			echo '			<tr><th>' . __('Humidity') . '</th>';
			echo '               <td style="color:' . $humiditycolor.'">' . sprintf(__('%s%%'),$data['results'][1]) . '</td>';
			echo '          </tr>';
			echo '			<tr><th>' . __('CO2') . '</th>';
			echo '              <td style="color:' . get_color( (int) $data['results'][2],0,3000,0,255,0,255,0,0 ).'">' . sprintf(__('%sppm'),$data['results'][2]) . '</td>';
			echo '          </tr>';
			echo '			<tr><th>' . __('Noise') . '</th>';
			echo '              <td style="color:' . get_color( (int) $data['results'][3],30,90,0,255,0,255,0,0 ).'">' . sprintf(__('%sdB'),$data['results'][3]) . '</td></tr>';
			echo '			<tr><td colspan="2" class="da">' . $when . '</td></tr>';
			echo '		</table>';
			echo '	</td>';
			echo '	<td id="outside" valign="top" align="right">';

			foreach ( $data['m'] as $moduleid=>$datam ) {

				$when = sprintf( __('measured %1$s %2$s at %5$s:%6$s'), 
					utf8_encode( ucfirst( strftime( '%A' ,$datam['time'] ) ) ),
					strftime( '%e' ,$datam['time'] ),
					utf8_encode( ucfirst( strftime( '%B' ,$datam['time'] ) ) ),
					strftime( '%Y' ,$datam['time'] ),
					strftime( '%H' ,$datam['time'] ),
					strftime( '%M' ,$datam['time'] )
				);

				echo '		<table>';
				echo '			<tr><th class="te" style="color:' . get_color( (int) $datam['results'][0] ) . '">'  . sprintf(__('%s°C'),$datam['results'][0]) . '</th>';
				echo '              <td align="right"><img src="img/outside.png" style="margin-right:1px;"/></td></tr>';
				echo '			<tr><th>' . __('Humidity') .'</th>';
				echo '              <td>' . sprintf(__('%s%%'),$datam['results'][1]) . '</td></tr>';
				echo '			<tr><th></th><td></td></tr>';
				echo '			<tr><th></th><td></td></tr>';
				echo '			<tr><td colspan="2" class="da">' . $when . '</td></tr>';
				echo '		</table>';
				echo '	</td>';
			}

			echo '</tr></table><br/>';
		}
	}

	else {
		echo '<table><tr><td><img src="img/rain.png"/></td><td class="te">&nbsp;' . __('Netatmo problem') . '&nbsp;<br/><center><span class="er">';
		echo __('No device');
		echo '</center></span></td></tr>';
		echo '<tr><td colspan="2" style="text-align:center; color:#888">' . $when . '</td></tr></table>';
	}

}

else {
	echo '<table><tr><td><img src="img/rain.png"/></td><td class="te">&nbsp;' . __('Netatmo unreachable') . '&nbsp;<br/><center><span class="er">';
	echo $result->result['error']['message'];
	echo '</center></span></td></tr>';
	echo '<tr><td colspan="2" style="text-align:center; color:#888">' . $when . '</td></tr></table>';
}


?>
</body>
</html>

