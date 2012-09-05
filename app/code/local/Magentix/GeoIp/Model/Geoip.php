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

class Magentix_GeoIp_Model_Geoip extends Magentix_GeoIp_Model_Geoip_Abstract {

    /**
     * Retrieve current country code
     *
     * @return string
     */
    public function getCountryCode() {
        if(!$this->getCountrySession()) {
            $this->setCountrySession(parent::getCountryCode());
        }

        return $this->getCountrySession();
    }

    /**
     * Set Country Code in Session
     *
     * @param string $code
     * @return Magentix_GeoIp_Model_Geoip
     */
    public function setCountrySession($code) {
        if($code) $this->getSession()->setCountryCode($code);

        return $this;
    }

    /**
     * Retrieve Country Code in Session
     *
     * @return string
     */
    public function getCountrySession() {
        return $this->getSession()->getCountryCode();
    }

    /**
     * Retrieve Session
     *
     * @return Mage_Core_Model_Session
     */
    public function getSession() {
        return Mage::getSingleton('core/session');
    }

}