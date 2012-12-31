<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
	<title>Netatmo</title>
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Cache-control" content="no-cache" />
	<meta http-equiv="Expires" content="-1" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="shortcut icon" href="favicon.ico" />
	<style type="text/css">

		body {
			background:#000;
			overflow: hidden;
			overflow-x: hidden;
			overflow-y: hidden;
			background:#333;
			padding: 10px;
			color: #333;
			font-family: Arial;
			font-size: 27px;
		}
		table {
			background:#fff;
			border-collapse: collapse;
			border: 0;
			margin: 0;
			padding: 0;
		}
		td#title {
			border-bottom: 1px solid black;
			height:20px;
			text-align: center;
		}
		td#inside {
			border-right: 1px solid black;
		}
		td#outside {
			border-left: 1px solid black;
		}
		td#inside table, td#outside table {
			width: 250px!important;
		}
		tr {
			height:20px;
		}
		th {
			font-size: 16px;
			font-family: Arial;
			color: #888;
			padding-left: 5px;
			padding-right: 5px;
			text-align: left;
		}
		td {
			font-size: 16px;
			font-family: Arial;
			padding-left: 5px;
			padding-right: 5px;
			text-align: right;
		}
		.te {
			font-size: 40px;
			font-weight: normal;
			font-family: Tahoma;
		}
		.da {
			font-size: 12px;
			font-family: Arial;
			color: #aaa;
			text-align: center;
		}
	</style>
</head>

<body><?php

setlocale(LC_ALL, "fr_FR");

function get_color($c,$min=3,$max=30,$r_min=0,$g_min=128,$b_min=255,$r_max=255,$g_max=0,$b_max=0) {
	$from_color = array($r_min,$g_min,$b_min);
	$to_color   = array($r_max,$g_max,$b_max);
	$from       = $min;
	$to         = $max;
	$d          = min($c,$to);
	$d          = max($d,$from);
	$c          = 'rgb(';
	for ($i=0; $i<3; $i++) {
		$a = $from_color[$i]+round(($d-$from)*($to_color[$i]-$from_color[$i])/($to-$from));
		$c.= ($i>0) ? ','.$a : $a;
	}
	return $c.')';
}

include_once('global.inc.php');
$result = get_netatmo();

if (count($result)>0) {
	foreach ($result as $data) {
		$name = $data['station'];
		if (isset($_GET['n'])) if (strtolower($name)!=strtolower($_GET['n'])) continue;

		if ((int)$data['results'][1]<40) $humiditycolor = get_color((int)$data['results'][1],20,40,255,0,0,0,255,0);
		else if ((int)$data['results'][1]>50) $humiditycolor = get_color((int)$data['results'][1],50,80,0,255,0,255,0,0);
		else $humiditycolor = 'rgb(0,255,0)';

		echo '<table>';
		echo '<tr><td colspan="2" id="title" align="center">'.$name.'</td></tr>';
		echo '<tr>';
		echo '	<td id="inside" valign="top" align="left">';
		echo '		<table>';
		echo '			<tr><th align="left"><img src="assets/inside.png"/></th><td class="te" style="color:'.get_color((int)$data['results'][0]).'">'.$data['results'][0].'°C</td></tr>';
		echo '			<tr><th>Humidité</th><td style="color:'.$humiditycolor.'">'.$data['results'][1].'%</td></tr>';
		echo '			<tr><th>CO2</th><td style="color:'.get_color((int)$data['results'][2],0,3000,0,255,0,255,0,0).'">'.$data['results'][2].'ppm</td></tr>';
		echo '			<tr><th>Ambiance</th><td style="color:'.get_color((int)$data['results'][3],30,90,0,255,0,255,0,0).'">'.$data['results'][3].'dB</td></tr>';
		echo '			<tr><td colspan="2" class="da">'.utf8_encode(ucfirst(strftime('Mesure %A %e'))).' à '.utf8_encode(ucfirst(strftime('%H:%M',$data['time']))).'</td></tr>';
		echo '		</table>';
		echo '	</td>';
		echo '	<td id="outside" valign="top" align="right">';

		foreach ($data['m'] as $moduleid=>$datam) {
			echo '		<table>';
			echo '			<tr><th class="te" style="color:'.get_color((int)$datam['results'][0]).'">'.$datam['results'][0].'°C</th><td align="right"><img src="assets/outside.png" style="margin-right:1px;"/></td></tr>';
			echo '			<tr><th>Humidité</th><td>'.$datam['results'][1].'%</td></tr>';
			echo '			<tr><th></th><td></td></tr>';
			echo '			<tr><th></th><td></td></tr>';
			echo '			<tr><td colspan="2" class="da">'.utf8_encode(ucfirst(strftime('Mesure %A %e'))).' à '.utf8_encode(ucfirst(strftime('%H:%M',$datam['time']))).'</td></tr>';
			echo '		</table>';
			echo '	</td>';
		}

		echo '</tr></table><br/>';
	}
}
else {
	echo '<table><tr><td><img src="assets/rain.png"/></td><td class="te">Station météo non joignable</td></tr>';
	echo '<tr><td colspan="2" style="text-align:center; color:#888"><br/>'.utf8_encode(ucfirst(strftime('%A %e %B %Y'))).' à '.utf8_encode(ucfirst(strftime('%H:%M'))).'</td></tr></table>';
}


?></body>
</html>

