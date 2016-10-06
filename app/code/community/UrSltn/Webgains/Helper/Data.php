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

class UrSltn_Webgains_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * General settings
     *
     * @var string
     */
    protected $_generalSettings = 'general_settings';

    /**
     * Get module version
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
     */
    public function exception(Exception $e)
    {
        Mage::log("\n" . $e->__toString(), Zend_Log::ERR, $this->_moduleName.'-exception.log');
    }

    /**
     * Get config setting
     *
     * @param string $config
     * @return mixed
     */
    public function getConfigSetting($config)
    {
        return Mage::getStoreConfig(
            strtolower($this->_moduleName) . DS . $this->_generalSettings . DS . $config,
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
}