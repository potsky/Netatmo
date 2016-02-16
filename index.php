<?php
require_once 'inc'. DIRECTORY_SEPARATOR . 'global.inc.php';
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
	<?php
	if ( file_exists( 'css/style.user.css' ) ) {
		echo '<link rel="stylesheet" type="text/css" href="css/style.user.css">';
	}
	?>
</head>
<body>
<center>
<div id="container">
<?php
$when = sprintf( __('%1$s %2$s %3$s %4$s at %5$s:%6$s'),
	mb_ucfirst( strftime( '%A' ) ),
	strftime( '%e' ),
	mb_ucfirst( strftime( '%B' ) ),
	strftime( '%Y' ),
	strftime( '%H' ),
	strftime( '%M' )
);

$mm_display_when = ( (int) @$_GET['mm'] == 1 ) ? true : false;

if ( is_array( $result ) ) {

	$scales = $result['scales'];
	unset( $result['scales'] );

	if ( count( $result ) > 0 ) {

		foreach ($result as $macid => $data) {

			$name   = $data['station'];
			$blocks = 1 + count($data['m']);

			if ( isset( $_GET['n'] ) )
				if ( strtolower( $name ) != strtolower( $_GET['n'] ) )
					continue;

			$tempc = (float) $data['results']['Temperature'];
			$when  = sprintf( __('measured %1$s %2$s at %5$s:%6$s'),
				mb_ucfirst( strftime( '%A' ,$data['time'] ) ),
				strftime( '%e' ,$data['time'] ),
				mb_ucfirst( strftime( '%B' ,$data['time'] ) ),
				strftime( '%Y' ,$data['time'] ),
				strftime( '%H' ,$data['time'] ),
				strftime( '%M' ,$data['time'] )
			);

			echo '<table class="' . htmlentities( $name . ' m' . sanitize_mac_id( $macid ) ) . '">';
			echo '<tr class="stationname"><td colspan="' . $blocks . '" id="title" align="center">' . $name . '</td></tr>';
			echo '<tr class="stationdata">';
			echo '	<td id="inside" valign="top" align="left">';
			echo '		<table>';

			echo '			<tr><th align="left" class="insideimage"></th>';
			echo '              <td class="te" style="color:' . get_color( (int) $tempc ) . '">';
			if ( $unitmetric == 0 )
				echo sprintf( __('%s°C') , round( $tempc , 1 ) );
			else
				echo sprintf( __('%s°F') , round ( ( 9 / 5 ) * $tempc + 32 , 1) );
			echo '              </td>';
			echo '          </tr>';

			$device_order = ( isset( $_GET['do'] ) ) ? $_GET['do'] : NETATMO_DEVICE_DEFAULT_VALUES;
			$device_disp  = 0;

			foreach ( explode( ',' , $device_order ) as $t) {

				$t = trim($t);
				switch ($t) {

					case 'Humidity':
						if ( (int) $data['results']['Humidity'] < 40 )
							$humiditycolor = get_color( (int) $data['results']['Humidity'] , 20 , 40 , 255 , 0 , 0 , 0 , 255 , 0 );
						else if ( (int) $data['results']['Humidity']>50 )
							$humiditycolor = get_color( (int) $data['results']['Humidity'] , 50 , 80 , 0 , 255 , 0 , 255 , 0 , 0 );
						else
							$humiditycolor = 'rgb( 0,255,0 )';

						echo '<tr><th>' . __('Humidity') . '</th>';
						echo '     <td style="color:' . $humiditycolor.'">' . sprintf( __('%s%%') , $data['results']['Humidity'] ) . '</td>';
						echo '</tr>';
						$device_disp++;
						break;

					case 'CO2':
						echo '<tr><th>' . __('CO2') . '</th>';
						echo '    <td style="color:' . get_color( (int) $data['results']['Co2'],0,3000,0,255,0,255,0,0 ).'">' . sprintf( __('%sppm') , $data['results']['Co2'] ) . '</td>';
						echo '</tr>';
						$device_disp++;
						break;

					case 'Noise':
						echo '<tr><th>' . __('Noise') . '</th>';
						echo '    <td style="color:' . get_color( (int) $data['results']['Noise'],30,90,0,255,0,255,0,0 ).'">' . sprintf( __('%sdB') , $data['results']['Noise'] ) . '</td>';
						echo '</tr>';
						$device_disp++;
						break;

					case 'Pressure':
						echo '<tr><th>' . __('Pressure') . '</th>';
						echo '    <td>';
						if ( $unitpressure == 0 )
							echo sprintf( __('%smbar') , round( (int) $data['results']['Pressure'] ) );
						else if ( $unitpressure == 1 )
							echo sprintf( __('%sinHg') , round( (int) $data['results']['Pressure'] / 33.8638815 , 2 ) );
						else
							echo sprintf( __('%smmHg') , round( (int) $data['results']['Pressure'] * 0.750061 ) );
						echo '    </td>';
						echo '</tr>';
						$device_disp++;
						break;

					case 'TemperatureMin' :
						echo '<tr><th valign="top">' . __('Temp Min') . '</th><td valign="top" class="mm">';
						if ( $unitmetric == 0 )
							echo sprintf( __('%s°C') , round( (float) $data['misc']['min_temp'] , 1 ) );
						else
							echo sprintf( __('%s°F') , round ( ( 9 / 5 ) * (int) $data['misc']['min_temp'] + 32 , 1) );
						echo '</td></tr>';
						$device_disp++;

						if ($mm_display_when) {
							echo '<tr><td class="mmd" colspan="2">';
							echo sprintf(
								__('on %1$s %2$s at %5$s:%6$s'),
								mb_ucfirst( strftime( '%A' ,$data['misc']['date_min_temp'] ) ),
								strftime( '%e' ,$data['misc']['date_min_temp'] ),
								mb_ucfirst( strftime( '%B' ,$data['misc']['date_min_temp'] ) ),
								strftime( '%Y' ,$data['misc']['date_min_temp'] ),
								strftime( '%H' ,$data['misc']['date_min_temp'] ),
								strftime( '%M' ,$data['misc']['date_min_temp'] )
							);
							echo '</td></tr>';
							$device_disp++;
						}
						break;

					case 'TemperatureMax' :
						echo '<tr><th valign="top">' . __('Temp Max') . '</th><td valign="top" class="mm">';
						if ( $unitmetric == 0 )
							echo sprintf( __('%s°C') , round( (float) $data['misc']['max_temp'] , 1 ) );
						else
							echo sprintf( __('%s°F') , round ( ( 9 / 5 ) * (int) $data['misc']['max_temp'] + 32 , 1) );
						echo '</td></tr>';
						$device_disp++;

						if ($mm_display_when) {
							echo '<tr><td class="mmd" colspan="2">';
							echo sprintf( __('on %1$s %2$s at %5$s:%6$s'),
								mb_ucfirst( strftime( '%A' ,$data['misc']['date_max_temp'] ) ),
								strftime( '%e' ,$data['misc']['date_max_temp'] ),
								mb_ucfirst( strftime( '%B' ,$data['misc']['date_max_temp'] ) ),
								strftime( '%Y' ,$data['misc']['date_max_temp'] ),
								strftime( '%H' ,$data['misc']['date_max_temp'] ),
								strftime( '%M' ,$data['misc']['date_max_temp'] )
							);
							echo '</td></tr>';
							$device_disp++;
						}
						break;

					case 'HumidityMin' :
						echo '<tr><th valign="top">' . __('Humidity Min') . '</th><td valign="top" class="mm">';
						echo sprintf( __('%s%%') , (int) $data['misc']['min_hum'] );
						echo '</td></tr>';
						$device_disp++;

						if ($mm_display_when) {
							echo '<tr><td class="mmd" colspan="2">';
							echo sprintf( __('on %1$s %2$s at %5$s:%6$s'),
								mb_ucfirst( strftime( '%A' ,$data['misc']['date_min_hum'] ) ),
								strftime( '%e' ,$data['misc']['date_min_hum'] ),
								mb_ucfirst( strftime( '%B' ,$data['misc']['date_min_hum'] ) ),
								strftime( '%Y' ,$data['misc']['date_min_hum'] ),
								strftime( '%H' ,$data['misc']['date_min_hum'] ),
								strftime( '%M' ,$data['misc']['date_min_hum'] )
							);
							echo '</td></tr>';
							$device_disp++;
						}
						break;

					case 'HumidityMax' :
						echo '<tr><th valign="top">' . __('Humidity Max') . '</th><td valign="top" class="mm">';
						echo sprintf( __('%s%%') , (int) $data['misc']['max_hum'] );
						echo '</td></tr>';
						$device_disp++;

						if ($mm_display_when) {
							echo '<tr><td class="mmd" colspan="2">';
								echo sprintf( __('on %1$s %2$s at %5$s:%6$s'),
								mb_ucfirst( strftime( '%A' ,$data['misc']['date_max_hum'] ) ),
								strftime( '%e' ,$data['misc']['date_max_hum'] ),
								mb_ucfirst( strftime( '%B' ,$data['misc']['date_max_hum'] ) ),
								strftime( '%Y' ,$data['misc']['date_max_hum'] ),
								strftime( '%H' ,$data['misc']['date_max_hum'] ),
								strftime( '%M' ,$data['misc']['date_max_hum'] )
							);
							echo '</td></tr>';
							$device_disp++;
						}
						break;

					case 'PressureMin' :
						echo '<tr><th valign="top">' . __('Pressure Min') . '</th><td valign="top" class="mm">';
						if ( $unitpressure == 0 )
							echo sprintf( __('%smbar') , round( (int) $data['misc']['min_pressure'] ) );
						else if ( $unitpressure == 1 )
							echo sprintf( __('%sinHg') , round( (int) $data['misc']['min_pressure'] / 33.8638815 , 2 ) );
						else
							echo sprintf( __('%smmHg') , round( (int) $data['misc']['min_pressure'] * 0.750061 ) );
						echo '</td></tr>';
						$device_disp++;

						if ($mm_display_when) {
							echo '<tr><td class="mmd" colspan="2">';
							echo sprintf( __('on %1$s %2$s at %5$s:%6$s'),
								mb_ucfirst( strftime( '%A' ,$data['misc']['date_min_pressure'] ) ),
								strftime( '%e' ,$data['misc']['date_min_pressure'] ),
								mb_ucfirst( strftime( '%B' ,$data['misc']['date_min_pressure'] ) ),
								strftime( '%Y' ,$data['misc']['date_min_pressure'] ),
								strftime( '%H' ,$data['misc']['date_min_pressure'] ),
								strftime( '%M' ,$data['misc']['date_min_pressure'] )
							);
							echo '</td></tr>';
							$device_disp++;
						}
						break;

					case 'PressureMax' :
						echo '<tr><th valign="top">' . __('Pressure Max') . '</th><td valign="top" class="mm">';
						if ( $unitpressure == 0 )
							echo sprintf( __('%smbar') , round( (int) $data['misc']['max_pressure'] ) );
						else if ( $unitpressure == 1 )
							echo sprintf( __('%sinHg') , round( (int) $data['misc']['max_pressure'] / 33.8638815 , 2 ) );
						else
							echo sprintf( __('%smmHg') , round( (int) $data['misc']['max_pressure'] * 0.750061 ) );
						echo '</td></tr>';
						$device_disp++;

						if ($mm_display_when) {
							echo '<tr><td class="mmd" colspan="2">';
								echo sprintf( __('on %1$s %2$s at %5$s:%6$s'),
								mb_ucfirst( strftime( '%A' ,$data['misc']['date_max_pressure'] ) ),
								strftime( '%e' ,$data['misc']['date_max_pressure'] ),
								mb_ucfirst( strftime( '%B' ,$data['misc']['date_max_pressure'] ) ),
								strftime( '%Y' ,$data['misc']['date_max_pressure'] ),
								strftime( '%H' ,$data['misc']['date_max_pressure'] ),
								strftime( '%M' ,$data['misc']['date_max_pressure'] )
							);
							echo '</td></tr>';
							$device_disp++;
						}
						break;

					case 'NoiseMin' :
						echo '<tr><th valign="top">' . __('Noise Min') . '</th><td valign="top" class="mm">';
						echo sprintf( __('%sdB') , round( (int) $data['misc']['min_noise'] , 1 ) );
						echo '</td></tr>';
						$device_disp++;

						if ($mm_display_when) {
							echo '<tr><td class="mmd" colspan="2">';
							echo sprintf( __('on %1$s %2$s at %5$s:%6$s'),
								mb_ucfirst( strftime( '%A' ,$data['misc']['date_min_noise'] ) ),
								strftime( '%e' ,$data['misc']['date_min_noise'] ),
								mb_ucfirst( strftime( '%B' ,$data['misc']['date_min_noise'] ) ),
								strftime( '%Y' ,$data['misc']['date_min_noise'] ),
								strftime( '%H' ,$data['misc']['date_min_noise'] ),
								strftime( '%M' ,$data['misc']['date_min_noise'] )
							);
							echo '</td></tr>';
							$device_disp++;
						}
						break;

					case 'NoiseMax' :
						echo '<tr><th valign="top">' . __('Noise Max') . '</th><td valign="top" class="mm">';
						echo sprintf( __('%sdB') , round( (int) $data['misc']['max_noise'] , 1 ) );
						echo '</td></tr>';
						$device_disp++;

						if ($mm_display_when) {
							echo '<tr><td class="mmd" colspan="2">';
							echo sprintf( __('on %1$s %2$s at %5$s:%6$s'),
								mb_ucfirst( strftime( '%A' ,$data['misc']['date_max_noise'] ) ),
								strftime( '%e' ,$data['misc']['date_max_noise'] ),
								mb_ucfirst( strftime( '%B' ,$data['misc']['date_max_noise'] ) ),
								strftime( '%Y' ,$data['misc']['date_max_noise'] ),
								strftime( '%H' ,$data['misc']['date_max_noise'] ),
								strftime( '%M' ,$data['misc']['date_max_noise'] )
							);
							echo '</td></tr>';
							$device_disp++;
						}
						break;

					default:
						break;
				}
			}
			echo '<tr><td colspan="2" class="da">' . $when . '</td></tr>';
			echo '</table>';

			echo '</td>';

			foreach ($data['m'] as $moduleid=>$datam)
			{
				$moduleid = sanitize_mac_id( $moduleid );
				$extra_class = '';
				$color       = '';
				$title       = '';

				if ( isset( $datam['dashboard']['Rain'] ) )
				{
					$type = 'rain';
				}
				else if ( isset( $datam['dashboard']['WindStrength'] ) )
				{
					$type = 'wind';
				}
				else
				{
					$type = 'outside';
				}

				echo '<td id="' . $type . $moduleid . '" class="' . $type . '" valign="top" align="right">';

				if ( $type === 'rain' )
				{
					$disp        = sprintf( __('%smm/h') , floor( (float) $datam['results']['Rain'] * 10 ) / 10 );
				}
				else if ( $type === 'wind' )
				{
					if ( $unitmetric == 0 )
					{
						$speed = sprintf( __('%skm/h') , round( (int)$datam['dashboard']['WindStrength'] ) );
					}
					else
					{
						$speed = sprintf( __('%smph') , round( (int)$datam['dashboard']['WindStrength'] * 0.621371 ) );
					}

					$disp        = $speed;
					$color       = ' style="color:' . get_color( (int)$datam['dashboard']['WindStrength'] , 0 , 100 , 0 , 255 , 0 , 255 , 0 , 0 ) . '"';
					$extra_class = 'windimage' . floor( $wind_icons_count * (int)$datam['dashboard']['WindAngle'] / 360 );
					$title       = sprintf( __('%s°') , $datam['dashboard']['WindAngle'] );
				}
				else
				{
					$tempc       = (float) $datam['results']['Temperature'];
					$color       = ' style="color:' . get_color( (int) $tempc ) . '"';
					$extra_class = '';

					if ( $unitmetric == 0 )
					{
						$disp = sprintf( __('%s°C') , round( $tempc , 1 ) );
					}
					else
					{
						$disp = sprintf( __('%s°F') , round ( ( 9 / 5 ) * $tempc + 32 , 1) );
					}
				}

				$when  = sprintf( __('measured %1$s %2$s at %5$s:%6$s'),
					mb_ucfirst( strftime( '%A' ,$datam['time'] ) ),
					strftime( '%e' ,$datam['time'] ),
					mb_ucfirst( strftime( '%B' ,$datam['time'] ) ),
					strftime( '%Y' ,$datam['time'] ),
					strftime( '%H' ,$datam['time'] ),
					strftime( '%M' ,$datam['time'] )
				);

				echo '<table>';

				echo '<tr><th class="te"' . $color . '>' . $disp . '</th>';

				echo '<td align="right" class="' . $type . 'image ' . $extra_class . ' m' . $moduleid . '" title="' . $title. '"></td></tr>';

				$module_order     = ( isset( $_GET['mo'] ) ) ? $_GET['mo'] : NETATMO_MODULE_DEFAULT_VALUES;
				$this_device_disp = $device_disp;

				foreach ( explode( ',' , $module_order ) as $t) {

					$t = trim($t);

					switch ($t) {

						case 'Humidity':
							if ( $type !== 'outside' ) break;
							if ( is_null( $datam['results']['Humidity'] ) ) break;
							echo '<tr><th>' . __('Humidity') .'</th>';
							echo '<td>' . sprintf( __('%s%%') , $datam['results']['Humidity'] ) . '</td></tr>';
							$this_device_disp--;
							break;

						case 'CO2':
							if ( $type !== 'outside' ) break;
							if ( is_null( $datam['results']['CO2'] ) ) break;
							echo '<tr><th>' . __('CO2') . '</th>';
							echo '<td>' . sprintf( __('%sppm') , $datam['results']['CO2'] ) . '</td></tr>';
							$this_device_disp--;
							break;

						case 'TemperatureMin' :
							if ( $type !== 'outside' ) break;
							if ( is_null( $datam['misc']['min_temp'] ) ) break;
							echo '<tr><th valign="top">' . __('Temp Min') . '</th><td valign="top" class="mm">';
							if ( $unitmetric == 0 )
								echo sprintf( __('%s°C') , round( (float) $datam['misc']['min_temp'] , 1 ) );
							else
								echo sprintf( __('%s°F') , round ( ( 9 / 5 ) * (int) $datam['misc']['min_temp'] + 32 , 1) );
							echo '</td></tr>';
							$this_device_disp--;

							if ($mm_display_when) {
								echo '<tr><td class="mmd" colspan="2">';
								echo sprintf( __('on %1$s %2$s at %5$s:%6$s'),
									mb_ucfirst( strftime( '%A' ,$datam['misc']['date_min_temp'] ) ),
									strftime( '%e' ,$datam['misc']['date_min_temp'] ),
									mb_ucfirst( strftime( '%B' ,$datam['misc']['date_min_temp'] ) ),
									strftime( '%Y' ,$datam['misc']['date_min_temp'] ),
									strftime( '%H' ,$datam['misc']['date_min_temp'] ),
									strftime( '%M' ,$datam['misc']['date_min_temp'] )
								);
								echo '</td></tr>';
								$this_device_disp--;
							}
							break;

						case 'TemperatureMax' :
							if ( $type !== 'outside' ) break;
							if ( is_null( $datam['misc']['max_temp'] ) ) break;
							echo '<tr><th valign="top">' . __('Temp Max') . '</th><td valign="top" class="mm">';
							if ( $unitmetric == 0 )
								echo sprintf( __('%s°C') , round( (float) $datam['misc']['max_temp'] , 1 ) );
							else
								echo sprintf( __('%s°F') , round ( ( 9 / 5 ) * (int) $datam['misc']['max_temp'] + 32 , 1) );
							echo '</td></tr>';
							$this_device_disp--;

							if ($mm_display_when) {
								echo '<tr><td class="mmd" colspan="2">';
								echo sprintf( __('on %1$s %2$s at %5$s:%6$s'),
									mb_ucfirst( strftime( '%A' ,$datam['misc']['date_max_temp'] ) ),
									strftime( '%e' ,$datam['misc']['date_max_temp'] ),
									mb_ucfirst( strftime( '%B' ,$datam['misc']['date_max_temp'] ) ),
									strftime( '%Y' ,$datam['misc']['date_max_temp'] ),
									strftime( '%H' ,$datam['misc']['date_max_temp'] ),
									strftime( '%M' ,$datam['misc']['date_max_temp'] )
								);
								echo '</td></tr>';
								$this_device_disp--;
							}
							break;

						case 'HumidityMin' :
							if ( $type !== 'outside' ) break;
							if ( is_null( $datam['misc']['min_hum'] ) ) break;
							echo '<tr><th valign="top">' . __('Humidity Min') . '</th><td valign="top" class="mm">';
							echo sprintf( __('%s%%') , (int) $datam['misc']['min_hum'] );
							echo '</td></tr>';
							$this_device_disp--;

							if ($mm_display_when) {
								echo '<tr><td class="mmd" colspan="2">';
								echo sprintf( __('on %1$s %2$s at %5$s:%6$s'),
									mb_ucfirst( strftime( '%A' ,$datam['misc']['date_min_hum'] ) ),
									strftime( '%e' ,$datam['misc']['date_min_hum'] ),
									mb_ucfirst( strftime( '%B' ,$datam['misc']['date_min_hum'] ) ),
									strftime( '%Y' ,$datam['misc']['date_min_hum'] ),
									strftime( '%H' ,$datam['misc']['date_min_hum'] ),
									strftime( '%M' ,$datam['misc']['date_min_hum'] )
								);
								echo '</td></tr>';
								$this_device_disp--;
							}
							break;

						case 'HumidityMax' :
							if ( $type !== 'outside' ) break;
							if ( is_null( $datam['misc']['max_hum'] ) ) break;
							echo '<tr><th valign="top">' . __('Humidity Max') . '</th><td valign="top" class="mm">';
							echo sprintf( __('%s%%') , (int) $datam['misc']['max_hum'] );
							echo '</td></tr>';
							$this_device_disp--;

							if ($mm_display_when) {
								echo '<tr><td class="mmd" colspan="2">';
								echo sprintf( __('on %1$s %2$s at %5$s:%6$s'),
									mb_ucfirst( strftime( '%A' ,$datam['misc']['date_max_hum'] ) ),
									strftime( '%e' ,$datam['misc']['date_max_hum'] ),
									mb_ucfirst( strftime( '%B' ,$datam['misc']['date_max_hum'] ) ),
									strftime( '%Y' ,$datam['misc']['date_max_hum'] ),
									strftime( '%H' ,$datam['misc']['date_max_hum'] ),
									strftime( '%M' ,$datam['misc']['date_max_hum'] )
								);
								echo '</td></tr>';
								$this_device_disp--;
							}
							break;

						case 'RainSum' :
							if ( $type !== 'rain' ) break;

							// Lang trick
							$a = __('1day') . __('1week') . __('1month');

							if ( is_null( @$datam['misc']['sum_rain'] ) ) break;
							echo '<tr><th valign="top">' . __('Rain Sum') . ' (' . __( $scales['module'] ) . ')' . '</th><td valign="top" class="mm">';
							echo sprintf( __('%smm') , floor( (float) $datam['misc']['sum_rain'] * 10 ) / 10 );
							echo '</td></tr>';
							$this_device_disp--;

							if ($mm_display_when) {
								echo '<tr><td class="mmd" colspan="2">';
								echo sprintf( __('on %1$s %2$s at %5$s:%6$s'),
									mb_ucfirst( strftime( '%A' ,$datam['misc']['date_sum_rain'] ) ),
									strftime( '%e' ,$datam['misc']['date_sum_rain'] ),
									mb_ucfirst( strftime( '%B' ,$datam['misc']['date_sum_rain'] ) ),
									strftime( '%Y' ,$datam['misc']['date_sum_rain'] ),
									strftime( '%H' ,$datam['misc']['date_sum_rain'] ),
									strftime( '%M' ,$datam['misc']['date_sum_rain'] )
								);
								echo '</td></tr>';
								$this_device_disp--;
							}
							break;

						case 'sum_rain_24' :
							if ( $type !== 'rain' ) break;
							if ( ! isset( $datam['dashboard']['sum_rain_24'] ) ) break;
							if ( is_null( $datam['dashboard']['sum_rain_24'] ) ) break;
							echo '<tr><th valign="top">' . __('Rain Sum (24h)') . '</th><td valign="top" class="mm">';
							echo sprintf( __('%smm') , $datam['dashboard']['sum_rain_24'] );
							echo '</td></tr>';
							$this_device_disp--;
							break;

						case 'sum_rain_1' :
							if ( $type !== 'rain' ) break;
							if ( ! isset( $datam['dashboard']['sum_rain_1'] ) ) break;
							if ( is_null( $datam['dashboard']['sum_rain_1'] ) ) break;
							echo '<tr><th valign="top">' . __('Rain Sum (1h)') . '</th><td valign="top" class="mm">';
							echo sprintf( __('%smm') , $datam['dashboard']['sum_rain_1'] );
							echo '</td></tr>';
							$this_device_disp--;
							break;

						case 'GustStrength' :
							if ( ! isset( $datam['dashboard']['GustStrength'] ) ) break;
							if ( is_null( $datam['dashboard']['GustStrength'] ) ) break;
							if ( $unitmetric == 0 )
							{
								$speed = sprintf( __('%skm/h') , round( (int)$datam['dashboard']['GustStrength'] ) );
							}
							else
							{
								$speed = sprintf( __('%smph') , round( (int)$datam['dashboard']['GustStrength'] * 0.621371 ) );
							}
							echo '<tr><th valign="top">' . __('Gust Strength') . '</th><td valign="top" style="color:';
							echo get_color( (int)$datam['dashboard']['GustStrength'] , 0 , 100 , 0 , 255 , 0 , 255 , 0 , 0 ) . '">';
							echo $speed;
							echo '</td></tr>';
							$this_device_disp--;
							break;

						case 'GustAngle' :
							if ( ! isset( $datam['dashboard']['GustAngle'] ) ) break;
							if ( is_null( $datam['dashboard']['GustAngle'] ) ) break;
							echo '<tr><th valign="top">' . __('Gust Angle') . '</th><td valign="top">';
							echo sprintf( __('%s°') , $datam['dashboard']['GustAngle'] );
							echo '</td></tr>';
							$this_device_disp--;
							break;

						default:
							break;
					}
				}

				for ($i=0; $i<$this_device_disp; $i++) {
					echo '<tr><th></th><td></td></tr>';
				}

				echo '<tr><td colspan="2" class="da">' . $when . '</td></tr>';

				echo '</table>';
				echo '</td>';
			}

			echo '</tr></table><br/>';
		}
	} else {
		echo '<table><tr><td><img src="img/rain.png"/></td><td class="te">&nbsp;' . __('Netatmo problem') . '&nbsp;<br/><center><span class="er">';
		echo __('No device');
		echo '</center></span></td></tr>';
		echo '<tr><td colspan="2" style="text-align:center; color:#888">' . $when . '</td></tr></table>';
	}

} else {
	echo '<table><tr><td><img src="img/rain.png"/></td><td class="te">&nbsp;' . __('Netatmo unreachable') . '&nbsp;<br/><center><span class="er">';
	echo $result->result['error']['message'];
	echo '</center></span></td></tr>';
	echo '<tr><td colspan="2" style="text-align:center; color:#888">' . $when . '</td></tr></table>';
}

?>
</div>
</center>
</body>
</html>
