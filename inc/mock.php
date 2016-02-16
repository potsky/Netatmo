<?php
require_once 'inc'. DIRECTORY_SEPARATOR . 'functions.inc.php';

// float must be formatted with a dot and not a coma. eg:
// - float(980.1) > ok
// - float(980,1) > nok
return unvar_dump('
array(2) {
["scales"]=>
array(2) {
["device"]=>
string(4) "1day"
["module"]=>
string(4) "1day"
}
["70:ee:50:00:f1:04"]=>
array(8) {
["dashboard"]=>
array(11) {
["AbsolutePressure"]=>
float(980.1)
["time_utc"]=>
int(1447792571)
["Noise"]=>
int(51)
["Temperature"]=>
float(22.3)
["Humidity"]=>
int(51)
["Pressure"]=>
float(1018.3)
["CO2"]=>
int(818)
["date_max_temp"]=>
int(1447784542)
["date_min_temp"]=>
int(1447751020)
["min_temp"]=>
float(20.7)
["max_temp"]=>
float(23.3)
}
["results"]=>
array(5) {
["Temperature"]=>
float(22.3)
["Co2"]=>
int(818)
["Humidity"]=>
int(51)
["Noise"]=>
int(51)
["Pressure"]=>
float(1018.3)
}
["name"]=>
string(5) "Buero"
["station"]=>
string(20) "Wetterstation-Saffen"
["type"]=>
string(6) "NAMain"
["time"]=>
int(1447792571)
["misc"]=>
array(16) {
["min_temp"]=>
float(20.7)
["date_min_temp"]=>
int(1447751020)
["max_temp"]=>
float(23.3)
["date_max_temp"]=>
int(1447784542)
["min_hum"]=>
int(48)
["date_min_hum"]=>
int(1447717599)
["max_hum"]=>
int(54)
["date_max_hum"]=>
int(1447783813)
["min_pressure"]=>
float(1017.7)
["date_min_pressure"]=>
NULL
["max_pressure"]=>
float(1020.6)
["date_max_pressure"]=>
NULL
["min_noise"]=>
int(36)
["date_min_noise"]=>
int(1447714888)
["max_noise"]=>
int(57)
["date_max_noise"]=>
int(1447785652)
}
["m"]=>
array(3) {
["02:00:00:00:f2:e2"]=>
array(5) {
["dashboard"]=>
array(7) {
["time_utc"]=>
int(1447792538)
["Temperature"]=>
int(15)
["Humidity"]=>
int(73)
["date_max_temp"]=>
int(1447792231)
["date_min_temp"]=>
int(1447736863)
["min_temp"]=>
float(5.8)
["max_temp"]=>
int(15)
}
["results"]=>
array(4) {
["Temperature"]=>
int(15)
["Humidity"]=>
int(73)
["CO2"]=>
NULL
["Rain"]=>
NULL
}
["name"]=>
string(7) "Outdoor"
["time"]=>
int(1447792538)
["misc"]=>
array(9) {
["min_temp"]=>
float(5.8)
["date_min_temp"]=>
int(1447736863)
["max_temp"]=>
int(15)
["date_max_temp"]=>
int(1447792231)
["min_hum"]=>
int(62)
["date_min_hum"]=>
int(1447767211)
["max_hum"]=>
int(86)
["date_max_hum"]=>
int(1447726303)
["sum_rain"]=>
NULL
}
}
["05:00:00:00:4e:7e"]=>
array(5) {
["dashboard"]=>
array(4) {
["time_utc"]=>
int(1447792564)
["Rain"]=>
int(0)
["sum_rain_24"]=>
float(2.929)
["sum_rain_1"]=>
int(0)
}
["results"]=>
array(4) {
["Temperature"]=>
NULL
["Humidity"]=>
NULL
["CO2"]=>
NULL
["Rain"]=>
int(0)
}
["name"]=>
string(11) "Regensensor"
["time"]=>
int(1447792564)
["misc"]=>
array(9) {
["min_temp"]=>
NULL
["date_min_temp"]=>
NULL
["max_temp"]=>
NULL
["date_max_temp"]=>
NULL
["min_hum"]=>
NULL
["date_min_hum"]=>
NULL
["max_hum"]=>
NULL
["date_max_hum"]=>
NULL
["sum_rain"]=>
float(2.9)
}
}
["06:00:00:00:1e:7a"]=>
array(4) {
["dashboard"]=>
array(13) {
["WindAngle"]=>
int(191)
["WindStrength"]=>
int(3)
["GustAngle"]=>
int(213)
["GustStrength"]=>
int(11)
["time_utc"]=>
int(1447792570)
["WindHistoric"]=>
array(12) {
[0]=>
array(3) {
["WindStrength"]=>
int(2)
["WindAngle"]=>
int(225)
["time_utc"]=>
int(1447789257)
}
[1]=>
array(3) {
["WindStrength"]=>
int(1)
["WindAngle"]=>
int(45)
["time_utc"]=>
int(1447789559)
}
[2]=>
array(3) {
["WindStrength"]=>
int(3)
["WindAngle"]=>
int(189)
["time_utc"]=>
int(1447789860)
}
[3]=>
array(3) {
["WindStrength"]=>
int(2)
["WindAngle"]=>
int(120)
["time_utc"]=>
int(1447790154)
}
[4]=>
array(3) {
["WindStrength"]=>
int(3)
["WindAngle"]=>
int(163)
["time_utc"]=>
int(1447790455)
}
[5]=>
array(3) {
["WindStrength"]=>
int(1)
["WindAngle"]=>
int(210)
["time_utc"]=>
int(1447790757)
}
[6]=>
array(3) {
["WindStrength"]=>
int(2)
["WindAngle"]=>
int(205)
["time_utc"]=>
int(1447791064)
}
[7]=>
array(3) {
["WindStrength"]=>
int(1)
["WindAngle"]=>
int(90)
["time_utc"]=>
int(1447791365)
}
[8]=>
array(3) {
["WindStrength"]=>
int(2)
["WindAngle"]=>
int(178)
["time_utc"]=>
int(1447791667)
}
[9]=>
array(3) {
["WindStrength"]=>
int(2)
["WindAngle"]=>
int(156)
["time_utc"]=>
int(1447791968)
}
[10]=>
array(3) {
["WindStrength"]=>
int(4)
["WindAngle"]=>
int(181)
["time_utc"]=>
int(1447792269)
}
[11]=>
array(3) {
["WindStrength"]=>
int(3)
["WindAngle"]=>
int(191)
["time_utc"]=>
int(1447792570)
}
}
["date_max_wind_str"]=>
int(1447774477)
["date_max_temp"]=>
int(1447714885)
["date_min_temp"]=>
int(1447714885)
["min_temp"]=>
int(0)
["max_temp"]=>
int(0)
["max_wind_angle"]=>
int(108)
["max_wind_str"]=>
int(18)
}
["results"]=>
array(4) {
["Temperature"]=>
int(0)
["Humidity"]=>
int(0)
["CO2"]=>
NULL
["Rain"]=>
NULL
}
["name"]=>
string(10) "Windmesser"
["time"]=>
int(1447792570)
}
}
}
}
');
