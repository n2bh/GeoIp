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

class Magentix_GeoIp_Model_Observer extends Magentix_GeoIp_Model_Geoip_Abstract {

    /**
     * Check config
     *
     * @param Varien_Event_Observer $observer
     */
    public function checkLicenseKey(Varien_Event_Observer $observer) {
        $request = $observer->getControllerAction()->getRequest();
        if ($request->getParam('section') != 'geoip') {
            return;
        }

        $groups = $request->getPost('groups');

        /* We need a valid license key */
        if(isset($groups['license']['fields']['key']['value'])) {
            if(!empty($groups['license']['fields']['key']['value'])) {
                $this->setIp('0.0.0.0');
                $this->setLicense($groups['license']['fields']['key']['value']);

                $this->_lines = $this->getLines();

                $find = is_array($this->_lines) && count($this->_lines) && isset($this->_lines[count($this->_lines)-1]);

                if($find) {
                    if(preg_match('/INVALID_LICENSE_KEY/i',$this->_lines[count($this->_lines)-1])) {
                        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('geoip')->__('Invalid License Key'));
                    }
                }
            }
        }

        /* We need a valid default country (if service return error) */
        if(isset($groups['country']['fields']['default']['value'])) {
            if(empty($groups['country']['fields']['default']['value'])) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('geoip')->__('Invalid Default Country'));
                $groups['country']['fields']['default']['value'] = 'US';

                $request->setPost('groups', $groups);
            }
        }
        
        return;
    }

}