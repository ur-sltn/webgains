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
 * Class UrSltn_Webgains_Model_Convert_Mapper_Column
 */
class UrSltn_Webgains_Model_Convert_Mapper_Column extends Mage_Dataflow_Model_Convert_Mapper_Column
{
    /**
     * Map
     *
     * @return $this
     * @throws Exception
     */
    public function map()
    {
        $batchExport = $this->getBatchExportModel();

        $batchExportIds = $batchExport
            ->setBatchId($this->getBatchModel()->getId())
            ->getIdCollection();

        // Default mandatory mapping
        $attributesToSelect = [
            'sku'               => 'ProductID',
            'name'              => 'Name',
            'description'       => 'Description',
            'price'             => 'Price',
            'url'               => 'Deeplink',
            'image_url'         => 'Image_URL',
            'category_names'    => 'Category',
            'delivery_cost'     => 'Delivery_cost',
            'delivery_period'   => 'Delivery_time'
        ];

        //@Todo implement $otherAttributes
        $otherAttributes = [
            'Extra_price_field'     => '', // wholesale prices or specials
            'Thumbnail_image_URL'   => '',
            'Manufacturer'          => '', //(e.g. Sony).
            'Brand'                 => '', //(e.g. PlayStation).
            'Related_product_IDs'   => '', //(e.g. "ft567|fg876|fu987").
            'Promotions'            => '',
            'Availability'          => '', //Stock level number or "in stock".
            'Best_sellers'          => '' //leave blank for now
        ];

        $onlySpecified = (bool)$this->getVar('_only_specified') === true;

        if ($onlySpecified && ($this->getVar('map') && is_array($this->getVar('map')))) {
            // Ensure we have mandatory mapping
            $attributesToSelect = array_merge($this->getVar('map'), $attributesToSelect);
        }

        if (!$attributesToSelect) {
            $this->getBatchExportModel()
                ->setBatchId($this->getBatchModel()->getId())
                ->deleteCollection();

            throw new Exception(
                Mage::helper('ursltn_webgains')->__('Field mapping not defined.')
            );
        }

        foreach ($batchExportIds as $batchExportId) {
            $batchExport = $this->getBatchExportModel()->load($batchExportId);
            $row = $batchExport->getBatchData();

            $newRow = [];
            foreach ($attributesToSelect as $field => $mapField) {
                $newRow[$mapField] = isset($row[$field]) ? $row[$field] : null;
            }

            $batchExport->setBatchData($newRow)
                ->setStatus(2)
                ->save();
            $this->getBatchModel()->parseFieldList($batchExport->getBatchData());
        }

        return $this;
    }
}
