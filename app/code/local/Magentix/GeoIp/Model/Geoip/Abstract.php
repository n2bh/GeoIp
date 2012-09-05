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

abstract class Magentix_GeoIp_Model_Geoip_Abstract extends Mage_Core_Model_Abstract {

    /**
     * Customer IP
     *
     * @var string
     */
    protected $_ip = null;

    /**
     * MaxMind License Key
     *
     * @var string
     */
    protected $_license = null;

    /**
     * Webservice result
     *
     * @var array
     */
    protected $_lines = null;

    /**
     * Initialize IP
     */
    public function __construct($ip) {
        $this->_ip = $ip;
    }

    /**
     * Retrieve Country Code
     *
     * @return string
     */
    public function getCountryCode() {
        if(is_null($this->_lines)) {
            $this->_lines = $this->getLines();
        }

        $find = is_array($this->_lines) && count($this->_lines) && isset($this->_lines[count($this->_lines)-1]);

        if($find) {
            if(preg_match('/^[a-zA-Z]{2}$/',$this->_lines[count($this->_lines)-1])) {
                return $this->_lines[count($this->_lines)-1];
            }
        }

        return $this->getDefaultCode();
    }

    /**
     * Set Customer IP
     *
     * @param string $ip
     * @return Magentix_GeoIp_Model_Abstract
     */
    public function setIp($ip) {
        $this->_ip = $ip;
        return $this;
    }

    /**
     * Retrieve Webservice results
     *
     * @return array
     */
    public function getLines() {
        if(($url = $this->getUrl()) && !Mage::helper('geoip')->isBot()) {
            $url = parse_url($this->getUrl());

            if(isset($url['host']) && isset($url['path']) && isset($url['query'])) {
                $host = $url['host'].':80';
                $path = $url['path'] . '?' . $url['query'];

                $fp = stream_socket_client($host, $errno, $errstr, 1);

                if ($fp) {
                    fputs ($fp, "GET $path HTTP/1.0\nHost: " . $host . "\n\n");
                    $buf = '';
                    while (!feof($fp)) {
                        $buf .= fgets($fp, 128);
                    }
                    $lines = explode("\n", $buf);
                    fclose($fp);

                    return $lines;
                }
            }
        }

        return array();
    }

    /**
     * Retrieve Customer IP
     *
     * @return string
     */
    public function getIp() {
        return $this->_ip;
    }

    /**
     * Retrieve MaxMind License Key
     *
     * @return string
     */
    protected function getLicense() {
        if(is_null($this->_license)) {
            $this->_license = Mage::getStoreConfig('geoip/license/key');
        }

        return $this->_license;
    }

    /**
     * Set License Key
     *
     * @return string
     */
    public function setLicense($license) {
        $this->_license = $license;
    }

    /**
     * Retrieve Default Country Code
     *
     * @return string
     */
    public function getDefaultCode() {
        return Mage::helper('geoip')->getDefaultCode();
    }

    /**
     * Retrieve Webservice URL
     *
     * @return string
     */
    protected function getUrl() {
        if($this->getLicense() && $this->getIp()) {
            return 'http://geoip.maxmind.com/a?l='.$this->getLicense().'&i='.$this->getIp();
        }
        return '';
    }

}