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
                Mage::getModel('ursltn_webgains/export')->products($this->helper()->getProductExportProfile());
                $batchModel = Mage::getSingleton('dataflow/batch');

                $this->helper()->debug(sprintf(
                    'Product export complete. Profile ID: %s. Batch ID: %s.',
                    $this->helper()->getProductExportProfile(),
                    $batchModel->getId()
                ));
            } catch (UrSltn_Webgains_Exception $e) {
                $this->helper()->debug(sprintf('Exception: %s', $e->getMessage()));
            } catch (Exception $e) {
                $this->helper()->exception($e);
            }
        } else {
            $this->helper()->debug('Product export is disabled.');
        }

        $this->helper()->debug(sprintf('%s - END', __METHOD__));
    }

    public function checkConvertParserProduct(Varien_Event_Observer $observer)
    {
        $object = $observer->getEvent()->getData('object');

        if ($object instanceof Mage_Dataflow_Model_Profile) {
            if ($object->getName() == 'Webgains Product Export') {
                $object->setData('actions_xml', '
<action type="catalog/convert_adapter_product" method="load">\r\n    <var name="store"><![CDATA[0]]></var>\r\n</action>\r\n\r\n<action type="ursltn_webgains/convert_parser_product" method="unparse">\r\n    <var name="store"><![CDATA[0]]></var>\r\n</action>\r\n\r\n<action type="ursltn_webgains/convert_mapper_column" method="map">\r\n</action>\r\n\r\n<action type="dataflow/convert_parser_csv" method="unparse">\r\n    <var name="delimiter"><![CDATA[,]]></var>\r\n    <var name="enclose"><![CDATA["]]></var>\r\n    <var name="fieldnames">true</var>\r\n</action>\r\n\r\n<action type="dataflow/convert_adapter_io" method="save">\r\n    <var name="type">file</var>\r\n    <var name="path">var/export</var>\r\n    <var name="filename"><![CDATA[webgains_product_export.csv]]></var>\r\n</action>\r\n\r\n
                ');
            }
        }
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