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
 * Class UrSltn_Webgains_Model_Adminhtml_System_Config_Source_Export_Format
 */
class UrSltn_Webgains_Model_Adminhtml_System_Config_Source_Export_Format
{
    /**
     * Options
     *
     * @var string[] $options
     */
    protected static $options = [];

    /**#@+
     * Format config
     */
    const FORMAT_CSV = 'csv';
    const FORMAT_XML = 'xml';

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
                    'label' => Mage::helper('ursltn_webgains')->__('CSV'),
                    'value' => self::FORMAT_CSV
                ],
                [
                    'label' => Mage::helper('ursltn_webgains')->__('XML'),
                    'value' => self::FORMAT_XML
                ]
            ];
        }

        return self::$options;
    }
}