<?php
/**
 * Created by Magentix
 * Date: 05/09/12
 *
 * @category   Magentix
 * @package    Magentix_GeoIp
 * @author     Matthieu Vion (http://www.magentix.fr)
 * @license    This work is free software, you can redistribute it and/or modify it
 */

class Magentix_GeoIp_Helper_Data extends Mage_Core_Helper_Abstract {

    /**
     * Check if current country code is FR
     *
     * @deprecated Please use isDefault() method instead (since 0.1.1)
     * @return bool
     */
    public function isFr() {
        return $this->getCode() == 'FR' ? true : false;
    }

    /**
     * Check if current country code is default code
     *
     * @return bool
     */
    public function isDefault() {
        return $this->getCode() == $this->getDefaultCode() ? true : false;
    }

    /**
     * Retrieve current country code
     *
     * @return string
     */
    public function getCode() {
        return Mage::getModel('geoip/geoip',$this->getIp())->getCountryCode();
    }

    /**
     * Retrieve customer IP
     *
     * @return string
     */
    public function getIp() {
        return Mage::helper('core/http')->getRemoteAddr();
    }

    /**
     * Retrieve Http User AGgent
     *
     * @return string
     */
    public function getHttpUserAgent() {
        return Mage::helper('core/http')->getHttpUserAgent();
    }

    /**
     * Check if visitor is a bot
     *
     * @return bool
     */
    public function isBot() {
        return preg_match('/bot|robot|spider|crawler|curl|^$/i', $this->getHttpUserAgent());
    }

    /**
     * Retrieve Default Country Code
     *
     * @return string
     */
    public function getDefaultCode() {
        return Mage::getStoreConfig('geoip/country/default') ? Mage::getStoreConfig('geoip/country/default') : 'US';
    }

}