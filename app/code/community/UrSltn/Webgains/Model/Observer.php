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

    /**
     * Update Webgains Product Export profile actions xml
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function updateExportProfile(Varien_Event_Observer $observer)
    {
        $object = $observer->getEvent()->getData('object');

        if ($object instanceof Mage_Dataflow_Model_Profile) {
            if ($object->getName() == 'Webgains Product Export') {

                $write = Mage::getSingleton('core/resource')
                    ->getConnection(Mage_Core_Model_Resource::DEFAULT_WRITE_RESOURCE);

                $sql = sprintf(
                    'UPDATE %s SET actions_xml=?, gui_data=? WHERE name=\'Webgains Product Export\'',
                    Mage::getSingleton('core/resource')->getTableName('dataflow/profile')
                );

                $write->query(
                    $sql,
                    ['
<action type="catalog/convert_adapter_product" method="load">
    <var name="store"><![CDATA[0]]></var>
</action>

<action type="ursltn_webgains/convert_parser_product" method="unparse">
    <var name="store"><![CDATA[0]]></var>
    <var name="url_field"><![CDATA[0]]></var>
</action>

<action type="ursltn_webgains/convert_mapper_column" method="map">
</action>

<action type="dataflow/convert_parser_csv" method="unparse">
    <var name="delimiter"><![CDATA[,]]></var>
    <var name="enclose"><![CDATA["]]></var>
    <var name="fieldnames">true</var>
</action>

<action type="dataflow/convert_adapter_io" method="save">
    <var name="type">file</var>
    <var name="path">var/export</var>
    <var name="filename"><![CDATA[webgains_product_export.csv]]></var>
</action>',
                        'a:7:{s:6:"export";a:1:{s:13:"add_url_field";s:1:"0";}s:6:"import";a:2:{s:17:"number_of_records";s:1:"1";s:17:"decimal_separator";s:1:".";}s:4:"file";a:8:{s:4:"type";s:4:"file";s:8:"filename";s:27:"webgains_product_export.csv";s:4:"path";s:10:"var/export";s:4:"host";s:0:"";s:4:"user";s:0:"";s:8:"password";s:0:"";s:9:"file_mode";s:1:"2";s:7:"passive";s:0:"";}s:5:"parse";a:5:{s:4:"type";s:3:"csv";s:12:"single_sheet";s:0:"";s:9:"delimiter";s:1:",";s:7:"enclose";s:1:""";s:10:"fieldnames";s:4:"true";}s:3:"map";a:3:{s:14:"only_specified";s:0:"";s:7:"product";a:2:{s:2:"db";a:0:{}s:4:"file";a:0:{}}s:8:"customer";a:2:{s:2:"db";a:0:{}s:4:"file";a:0:{}}}s:7:"product";a:1:{s:6:"filter";a:8:{s:4:"name";s:0:"";s:3:"sku";s:0:"";s:4:"type";s:1:"0";s:13:"attribute_set";s:0:"";s:5:"price";a:2:{s:4:"from";s:0:"";s:2:"to";s:0:"";}s:3:"qty";a:2:{s:4:"from";s:0:"";s:2:"to";s:0:"";}s:10:"visibility";s:1:"0";s:6:"status";s:1:"0";}}s:8:"customer";a:1:{s:6:"filter";a:10:{s:9:"firstname";s:0:"";s:8:"lastname";s:0:"";s:5:"email";s:0:"";s:5:"group";s:1:"0";s:10:"adressType";s:15:"default_billing";s:9:"telephone";s:0:"";s:8:"postcode";s:0:"";s:7:"country";s:0:"";s:6:"region";s:0:"";s:10:"created_at";a:2:{s:4:"from";s:0:"";s:2:"to";s:0:"";}}}}'
                    ]
                );
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