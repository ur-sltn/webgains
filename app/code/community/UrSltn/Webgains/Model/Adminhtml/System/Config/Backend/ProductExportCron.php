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
 * Class UrSltn_Webgains_Model_Adminhtml_System_Config_Backend_ProductExportCron
 */
class UrSltn_Webgains_Model_Adminhtml_System_Config_Backend_ProductExportCron extends Mage_Core_Model_Config_Data
{
    const CRON_STRING_PATH  = 'crontab/jobs/process_ursltn_webgains_export_products/schedule/cron_expr';
    const CRON_MODEL_PATH   = 'crontab/jobs/process_ursltn_webgains_export_products/run/model';
    
    /**
     * Cron settings after save
     *
     * @return void
     * @throws Exception
     */
    protected function _afterSave()
    {
        $enabled    = $this->getData('groups/product_export/fields/active/value');
        $frequency  = $this->getData('groups/product_export/fields/cron_frequency/value');
        $hourly     = $this->getData('groups/product_export/fields/cron_hourly/value');
        $minutely   = $this->getData('groups/product_export/fields/cron_minutely/value');
        $time       = $this->getData('groups/product_export/fields/cron_time/value');

        if ($enabled) {
            if ($frequency == UrSltn_Webgains_Model_Adminhtml_System_Config_Source_Cron_Frequency::CRON_MINUTELY) {
                $cronExprArray = [
                    '*/' . $minutely,
                    '*',
                    '*',
                    '*',
                    '*'
                ];
            } else if ($frequency == UrSltn_Webgains_Model_Adminhtml_System_Config_Source_Cron_Frequency::CRON_HOURLY) {
                $cronExprArray = [
                    '0',
                    '*/' . $hourly,
                    '*',
                    '*',
                    '*'
                ];
            } else {
                $cronExprArray = [
                    intval($time[1]), // Minute
                    intval($time[0]), // Hour
                    ($frequency == UrSltn_Webgains_Model_Adminhtml_System_Config_Source_Cron_Frequency::CRON_MONTHLY) ?
                        '1' : '*', // Day of the Month
                    '*', // Month of the Year
                    ($frequency == UrSltn_Webgains_Model_Adminhtml_System_Config_Source_Cron_Frequency::CRON_WEEKLY) ?
                        '1' : '*' // Day of the Week
                ];
            }

            $cronExprString = join(' ', $cronExprArray);
        } else {
            $cronExprString = '';
        }

        try {
            /** @noinspection PhpUndefinedMethodInspection */
            Mage::getModel('core/config_data')
                ->load(self::CRON_STRING_PATH, 'path')
                ->setValue($cronExprString)
                ->setPath(self::CRON_STRING_PATH)
                ->save();

            /** @noinspection PhpUndefinedMethodInspection */
            Mage::getModel('core/config_data')
                ->load(self::CRON_MODEL_PATH, 'path')
                ->setValue((string) Mage::getConfig()->getNode(self::CRON_MODEL_PATH))
                ->setPath(self::CRON_MODEL_PATH)
                ->save();
        } catch (Exception $e) {
            throw new Exception(Mage::helper('ursltn_webgains')->__('Unable to save the cron expression.'));
        }
    }
}