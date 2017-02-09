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
 * Class UrSltn_Webgains_Model_Convert_Parser_Product
 */
class UrSltn_Webgains_Model_Convert_Parser_Product extends Mage_Catalog_Model_Convert_Parser_Product
{
    /**
     * Unparse (prepare data) loaded products
     *
     * @return Mage_Catalog_Model_Convert_Parser_Product
     */
    public function unparse()
    {
        $entityIds = $this->getData();

        foreach ($entityIds as $i => $entityId) {
            /** @var Mage_Catalog_Model_Product $product */
            $product = $this->getProductModel()
                ->setStoreId($this->getStoreId())
                ->load($entityId);
            $this->setProductTypeInstance($product);

            $position = Mage::helper('ursltn_webgains')->__('Line %d, SKU: %s', ($i+1), $product->getSku());
            $this->setPosition($position);

            $row = [
                'store'         => $this->getStore()->getCode(),
                'websites'      => '',
                'website'       => '',
                'attribute_set' => $this->getAttributeSetName(
                    $product->getEntityTypeId(), $product->getAttributeSetId()
                ),
                'type'          => $product->getTypeId(),
                'category_ids'  => join(',', $product->getCategoryIds())
            ];

            if ($this->getStore()->getCode() == Mage_Core_Model_Store::ADMIN_CODE) {
                $websiteCodes = [];
                foreach ($product->getWebsiteIds() as $websiteId) {
                    $websiteCode = Mage::app()->getWebsite($websiteId)->getCode();
                    $websiteCodes[$websiteCode] = $websiteCode;
                }
                $row['websites'] = join(',', $websiteCodes);
            } else {
                $row['websites'] = $this->getStore()->getWebsite()->getCode();
                if ($this->getVar('url_field')) {
                    $row['url'] = $product->getProductUrl(false);
                }
            }

            // Set default delivery cost if it does not exist
            if (!$product->getData('delivery_cost')) {
                $row['delivery_cost'] = Mage::helper('ursltn_webgains')->getDefaultDeliveryCost();
            }

            // Set default delivery period if it does not exist
            if (!$product->getData('delivery_period')) {
                $row['delivery_period'] = Mage::helper('ursltn_webgains')->getDefaultDeliveryPeriod();
            }

            // Set category_names
            $categoryIds = $product->getCategoryIds();

            if (empty($categoryIds)) {
                $row['category_names'] = 'NA';
            } else {
                $categoryNames = [];

                foreach ($categoryIds as $categoryId) {
                    $name = Mage::getModel('catalog/category')->load($categoryId)->getName();

                    if ($name !== '') {
                        $categoryNames[] = $name;
                    }
                }

                $row['category_names'] = implode(' -> ', $categoryNames);
            }

            // Set url
            if (empty($row['url'])) {
                $row['url'] = $product->getProductUrl();
            }

            // Set image_url
            if (empty($row['image_url'])) {
                $row['image_url'] = (string)Mage::helper('catalog/image')->init($product, 'image')->resize(265);
            }

            foreach ($product->getData() as $field => $value) {
                if (in_array($field, $this->_systemFields) || is_object($value)) {
                    continue;
                }

                $attribute = $this->getAttribute($field);
                if (!$attribute) {
                    continue;
                }

                if ($attribute->usesSource()) {
                    $option = $attribute->getSource()->getOptionText($value);
                    if ($value && empty($option) && $option != '0') {
                        $this->addException(
                            Mage::helper('ursltn_webgains')->__(
                                'Invalid option ID specified for %s (%s), skipping the record.', $field, $value
                            ),
                            Mage_Dataflow_Model_Convert_Exception::ERROR
                        );
                        continue;
                    }
                    if (is_array($option)) {
                        $value = join(self::MULTI_DELIMITER, $option);
                    } else {
                        $value = $option;
                    }
                    unset($option);
                } elseif (is_array($value)) {
                    continue;
                }

                $row[$field] = strip_tags($value);
            }

            if ($stockItem = $product->getStockItem()) {
                foreach ($stockItem->getData() as $field => $value) {
                    if (in_array($field, $this->_systemFields) || is_object($value)) {
                        continue;
                    }
                    $row[$field] = $value;
                }
            }

            $productMediaGallery = $product->getMediaGallery();
            $product->reset();

            $processedImageList = [];
            foreach ($this->_imageFields as $field) {
                if (isset($row[$field])) {
                    if ($row[$field] == 'no_selection') {
                        $row[$field] = null;
                    } else {
                        $processedImageList[] = $row[$field];
                    }
                }
            }
            $processedImageList = array_unique($processedImageList);

            $batchModelId = $this->getBatchModel()->getId();
            $this->getBatchExportModel()
                ->setId(null)
                ->setBatchId($batchModelId)
                ->setBatchData($row)
                ->setStatus(1)
                ->save();

            $baseRowData = [
                'store'     => $row['store'],
                'website'   => $row['website'],
                'sku'       => $row['sku']
            ];
            unset($row);

            foreach ($productMediaGallery['images'] as $image) {
                if (in_array($image['file'], $processedImageList)) {
                    continue;
                }

                $rowMediaGallery = [
                    '_media_image'          => $image['file'],
                    '_media_lable'          => $image['label'],
                    '_media_position'       => $image['position'],
                    '_media_is_disabled'    => $image['disabled']
                ];
                $rowMediaGallery = array_merge($baseRowData, $rowMediaGallery);

                $this->getBatchExportModel()
                    ->setId(null)
                    ->setBatchId($batchModelId)
                    ->setBatchData($rowMediaGallery)
                    ->setStatus(1)
                    ->save();
            }
        }

        return $this;
    }

    /**
     * Retrieve accessible external product attributes
     *
     * @return array
     */
    public function getExternalAttributes()
    {
        $productAttributes  = Mage::getResourceModel('catalog/product_attribute_collection')->load();
        $attributes         = $this->_externalFields;

        foreach ($productAttributes as $attr) {
            $code = $attr->getAttributeCode();
            if (in_array($code, $this->_internalFields) || $attr->getFrontendInput() == 'hidden') {
                continue;
            }
            $attributes[$code] = $code;
        }

        foreach ($this->_inventoryFields as $field) {
            $attributes[$field] = $field;
        }

        return $attributes;
    }
}
