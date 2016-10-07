<?php
/**
 * Ur-Sltn Webgains
 *
 * NOTICE OF LICENSE
 *
 * Private Proprietary Software (https://ur-sltn.com/terms-of-service.php)
 *
 * @category   UrSltn
 * @package    UrSltn_Webgains
 * @copyright  Copyright (c) 2016 Ur-Sltn LTD (https://ur-sltn.com)
 * @license    https://ur-sltn.com/terms-of-service.php  Private Proprietary Software
 * @author     Shaughn Le Grange - Hatlen <me@shaughn.pro>
 */

/**
 * Class UrSltn_Webgains_Helper_Data
 */
class UrSltn_Webgains_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * General settings
     *
     * @var string
     */
    protected $generalSettings = 'general_settings';

    /**
     * Product export
     *
     * @var string
     */
    protected $productExport = 'product_export';

    /**
     * Tracking settings
     *
     * @var string
     */
    protected $trackingSettings = 'tracking_settings';

    /**
     * Get module version
     *
     * @return string
     */
    public function getModuleVersion()
    {
        /** @noinspection PhpUndefinedFieldInspection */
        return Mage::getConfig()->getModuleConfig($this->_getModuleName())->version;
    }

    /**
     * Exception logging
     * 
     * @param Exception $e
     * @return void
     */
    public function exception(Exception $e)
    {
        Mage::log("\n" . $e->__toString(), Zend_Log::ERR, $this->_moduleName.'-exception.log');
    }

    /**
     * Get config setting
     *
     * @param string $config
     * @return string
     */
    public function getConfigSetting($config)
    {
        return Mage::getStoreConfig(
            strtolower($this->_getModuleName()) . DS . $this->generalSettings . DS . $config,
            Mage::app()->getStore()
        );
    }

    /**
     * Get product export setting
     *
     * @param string $config
     * @return string
     */
    public function getProductExportSetting($config)
    {
        return Mage::getStoreConfig(
            strtolower($this->_getModuleName()) . DS . $this->productExport . DS . $config,
            Mage::app()->getStore()
        );
    }

    /**
     * Get tracking setting
     *
     * @param string $config
     * @return string
     */
    public function getTrackingSetting($config)
    {
        return Mage::getStoreConfig(
            strtolower($this->_getModuleName()) . DS . $this->trackingSettings . DS . $config,
            Mage::app()->getStore()
        );
    }

    /**
     * Is active
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->getConfigSetting('active');
    }

    /**
     * Is debug mode active
     *
     * @return bool
     */
    public function isDebugMode()
    {
        return $this->getConfigSetting('debug');
    }

    /**
     * Is product export active
     *
     * @return bool
     */
    public function isProductExportActive()
    {
        return $this->getProductExportSetting('active');
    }

    /**
     * Get default delivery cost
     *
     * @return string
     */
    public function getDefaultDeliveryCost()
    {
        return $this->getProductExportSetting('default_delivery_cost');
    }

    /**
     * Get default delivery period
     *
     * @return string
     */
    public function getDefaultDeliveryPeriod()
    {
        return $this->getProductExportSetting('default_delivery_period');
    }

    /**
     * Get product export profile
     *
     * @return string
     */
    public function getProductExportProfile()
    {
        return $this->getProductExportSetting('profile');
    }

    /**
     * Is tracking active
     *
     * @return bool
     */
    public function isTrackingActive()
    {
        return ($this->getTrackingSetting('active') && $this->getTrackingEventId())? true : false;
    }

    /**
     * Is tracking event ID
     *
     * @return string
     */
    public function getTrackingEventId()
    {
        return $this->getTrackingSetting('event_id');
    }

    /**
     * Debug logging
     *
     * @param string|string[] $message
     * @return void
     */
    public function debug($message)
    {
        if ($this->isDebugMode()) {
            Mage::log($message, false, strtolower($this->_moduleName) . '-debug.log');
        }
    }
}