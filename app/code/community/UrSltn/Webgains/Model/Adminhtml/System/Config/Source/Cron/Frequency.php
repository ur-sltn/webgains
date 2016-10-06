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
 * Class UrSltn_Webgains_Model_Adminhtml_System_Config_Source_Cron_Frequency
 */
class UrSltn_Webgains_Model_Adminhtml_System_Config_Source_Cron_Frequency
{
    /**
     * Options
     *
     * @var string[] $options
     */
    protected static $options = [];

    /**#@+
     * Cron config
     */
    const CRON_MINUTELY = 'A';
    const CRON_HOURLY   = 'H';
    const CRON_DAILY    = 'D';
    const CRON_WEEKLY   = 'W';
    const CRON_MONTHLY  = 'M';

    /**
     * To option array
     *
     * @return string[]
     */
    public function toOptionArray()
    {
        if (empty(self::$options)) {
            self::$options = [
                [
                    'label' => Mage::helper('ursltn_webgains')->__('Minutely'),
                    'value' => self::CRON_MINUTELY
                ],
                [
                    'label' => Mage::helper('ursltn_webgains')->__('Hourly'),
                    'value' => self::CRON_HOURLY
                ],
                [
                    'label' => Mage::helper('ursltn_webgains')->__('Daily'),
                    'value' => self::CRON_DAILY
                ],
                [
                    'label' => Mage::helper('ursltn_webgains')->__('Weekly'),
                    'value' => self::CRON_WEEKLY
                ],
                [
                    'label' => Mage::helper('ursltn_webgains')->__('Monthly'),
                    'value' => self::CRON_MONTHLY
                ]
            ];
        }

        return self::$options;
    }
}