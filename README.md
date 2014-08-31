Netatmo PHP Widget
==================

## Requirements

You need a web server with PHP 5.4+
The *gettext PHP* extension is mandatory to localize the widget.

## Installation

Just copy the project directory on your server and configure it.

## Configuration

1. Create a `config.user.inc.php` at the root directory.
2. Put these lines :
    ```
    $NAusername = "____EMAIL_ACCOUNT_HERE____";
    $NApwd      = "____PASS_ACCOUNT_HERE____";
    $NAconfig   = array(
        'client_id'     => '____API_CLIENT_ID_HERE____',
        'client_secret' => '____API_CLIENT_SECRET_HERE____',
    );

    define( 'NETATMO_DEVICE_DEFAULT_VALUES' , 'Humidity,CO2,Noise' );
    define( 'NETATMO_MODULE_DEFAULT_VALUES' , 'Humidity,Rain,RainSum,sum_rain_1,sum_rain_24' );
    ``` 
3. Change `____EMAIL_ACCOUNT_HERE____` by your netatmo email address
4. Change `____PASS_ACCOUNT_HERE____` by your netatmo password
5. To change `____API_CLIENT_ID_HERE____` and `____API_CLIENT_SECRET_HERE____`, you need to create a netatmo app, it is really straightforward :
    1. Go to <https://dev.netatmo.com>
    2. Sign in with your netatmo email address and password
    3. Click on *CREATE AN APP* button on top
    4. Give a name to your application
    5. Fill the application description *eg: Netatmo Application for my Potsky PHP Widget installed on my website*
    6. Accept the terms of use
    7. Click on *CREATE*
    8. Now you can copy paste *client id* and *client secret*

> You can configure the CSS by creating a `css/style.user.css` file, it will not be updated when you pull code from git.

## Change log

- **0.5.5**

    - Add GET parameter `a` in `raw.php`
    - Add `rain1` and `rain24` values for GET messages in `raw.php`
    
- **0.5.4**

    - Add `rain_sum_1` and `rain_sum_24` values
    - Add `index_combined.php` file to display rain information in the first outside module instead of a new rain module

- **0.5.3**
    - change `raw.php` to return last 24h rain gauge informations
    - change `raw.php` to specify custom message
    - change `raw.php` to retrieve all weather stations informations with GET parameter `a`
    - add polish language (thanx to Karol Zak)

## Upgrade notes

### Upgrading to 0.5

To retrieve rain informations, please add `Rain`, `RainSum`, `RainSum` in constant `NETATMO_MODULE_DEFAULT_VALUES` in your configuration file like this :

```
define( 'NETATMO_DEVICE_DEFAULT_VALUES' , 'Humidity,CO2,Noise' );
define( 'NETATMO_MODULE_DEFAULT_VALUES' , 'Humidity,Rain,RainSum' );
```

## dump.php

You can dump all Netatmo informations by calling this script.

## raw.php

If you only want to display text instead of a widget, you can call `raw.php` instead of `index.php`.

You can now specify 2 custom messages in the url when :

- there is a rain gauge information
- there is no rain gauge information

Here are the distinct available parameters :

- <http://xxx/raw.php> : english default messages and display all weather stations
- <http://xxx/raw.php?a=1> : english default messages and display only the first weather station
- <http://xxx/raw.php?a=2> : english default messages and display only the second weather station
- <http://xxx/raw.php?text_wo_rain=Temperature is _temp_°C> : message without rain gauge
- <http://xxx/raw.php?text_wi_rain=Temperature is _temp_°C and rain is _rain_> : message with rain gauge
- <http://xxx/raw.php?text_wi_rain=...&text_wo_rain=...> : messages for both cases (rain and no rain)

Available parameters in your message are :

- `_device_name_`
- `_name_`
- `_human_date_`
- `_human_hour_`
- `_temp_`
- `_humi_`
- `_rain_` : rain since the beginning of the day (or week or month if you have change the module scale parameter)
- `_rain1_` : rain for 1 hour
- `_rain24_` : rain for 24 hours

## More...

More information at [https://www.potsky.com/code/netatmo/]()

[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/potsky/netatmo/trend.png)](https://bitdeli.com/free "Bitdeli Badge")
