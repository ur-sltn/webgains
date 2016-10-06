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
 * Class UrSltn_Webgains_Model_Adminhtml_System_Config_Source_Cron_Hours
 */
class UrSltn_Webgains_Model_Adminhtml_System_Config_Source_Cron_Hours
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
            self::$options = [];

            for ($i = 1; $i <= 23; $i++) {
                self::$options[] = [
                    'label' => $i,
                    'value' => $i
                ];
            }
        }

        return self::$options;
    }
}