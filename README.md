Netatmo PHP Widget
==================

More information at [https://www.potsky.com/code/netatmo/]()

[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/potsky/netatmo/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

## Change log

- **0.5.3**
    - change `raw.php` to return rain gauge informations
    - change `raw.php` to specify custom message
    - change `raw.php` to retrieve all weather stations informations with GET parameter `a`
    - add polish language (thanx to Karol Zak)

## Upgrade notes

### Upgrading to 0.5

To retrieve rain informations, please add `Rain` and `RainSum` in constant `NETATMO_MODULE_DEFAULT_VALUES` in your configuration file like this :

```
define( 'NETATMO_DEVICE_DEFAULT_VALUES' , 'Humidity,CO2,Noise' );
define( 'NETATMO_MODULE_DEFAULT_VALUES' , 'Humidity,Rain,RainSum' );
```

