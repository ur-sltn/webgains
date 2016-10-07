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
 * Class UrSltn_Webgains_Model_Adminhtml_System_Config_Source_Export_Profile
 */
class UrSltn_Webgains_Model_Adminhtml_System_Config_Source_Export_Profile
{
    /**
     * Options
     *
     * @var string[] $options
     */
    protected static $options = [];

    /**
     * To option array
     *
     * @return string[]
     */
    public function toOptionArray()
    {
        if (empty(self::$options)) {

            $profiles = Mage::getModel('dataflow/profile')->getCollection()
                ->addFieldToFilter('direction', ['eq' => 'export']);

            self::$options = [
                [
                    'label' => '',
                    'value' => ''
                ]
            ];

            if ($profiles->getSize()) {
                foreach ($profiles as $profile) {
                    self::$options[] = [
                        'label' => $profile->getName(),
                        'value' => $profile->getId()
                    ];
                }
            }
        }

        return self::$options;
    }
}