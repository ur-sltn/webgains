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
 * Class UrSltn_Webgains_Model_Observer
 */
class UrSltn_Webgains_Model_Observer
{
    /**
     * Export products
     *
     * Schedule: process_ursltn_webgains_export_products
     *
     * @param Mage_Cron_Model_Schedule $schedule
     * @return void
     * @throws Mage_Core_Exception
     */
    public function exportProducts(Mage_Cron_Model_Schedule $schedule)
    {
        unset($schedule);

        $this->helper()->debug(sprintf('%s - START', __METHOD__));

        if ($this->helper()->isProductExportActive()) {
            try {
                Mage::getModel('ursltn_webgains/export')->products($this->helper()->getProductExportFormat());
            } catch (Exception $e) {
                $this->helper()->exception($e);
            }
        } else {
            $this->helper()->debug('Product export is disabled.');
        }

        $this->helper()->debug(sprintf('%s - END', __METHOD__));
    }

    /**
     * Helper
     *
     * @return UrSltn_Webgains_Helper_Data
     */
    protected function helper()
    {
        return Mage::helper('ursltn_webgains');
    }
}