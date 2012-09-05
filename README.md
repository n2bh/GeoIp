Description
-----------

Magento GeoIP Country Geolocation IP Address to Country with MaxMind

You need to use MaxMind GeoIP Country Web Service : [GeoIP Web Services](http://www.maxmind.com/app/web_services#country)


Configuration
-------------

* Config
 * System > Configuration > General > MaxMind GeoIp


How to use?
-----------

Check if current country code is default code :

```
Mage::helper('geoip')->isDefault();
```

Retrieve current country code :

```
Mage::helper('geoip')->getCode();
```