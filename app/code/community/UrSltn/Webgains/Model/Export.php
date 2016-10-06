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
 * Class UrSltn_Webgains_Model_Export
 */
class UrSltn_Webgains_Model_Export
{
    /**
     * Export products
     *
     * @param string $format
     * @return void
     * @throws Mage_Core_Exception
     */
    public function products($format)
    {
        $products = Mage::getModel('catalog/product')->getCollection();
        $products->addAttributeToSelect('*');
        $products->addAttributeToFilter('status', 1);

        if ($products->getSize()) {
            foreach ($products as $product) {

            }
        }
    }

    public function formatCsv()
    {
        $format = [
            'ProductID'             => 'sku', // Mandatory
            'Name'                  => 'name', // Mandatory
            'Description'           => 'description', // Mandatory
            'Price'                 => 'price', // Mandatory (no currency. use decimal point ony)
            'Deeplink'              => 'product_url', // Mandatory
            'Image_URL'             => 'image_url', // Mandatory
            'Category'              => 'category_name', // Mandatory
            'Delivery_cost'         => 'delivery_cost', // Mandatory
            'Delivery_time'         => 'delivery_period', // Mandatory
            'Extra_price_field'     => '', // wholesale prices or specials
            'Thumbnail_image_URL'   => '',
            'Manufacturer'          => '', //(e.g. Sony).
            'Brand'                 => '', //(e.g. PlayStation).
            'Related_product_IDs'   => '', //(e.g. "ft567|fg876|fu987").
            'Promotions'            => '',
            'Availability'          => '', //Stock level number or "in stock".
            'Best_sellers'          => '' //leave blank for now
        ];
    }
}