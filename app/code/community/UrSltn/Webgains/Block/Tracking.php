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
 * Class UrSltn_Webgains_Block_Tracking
 */
class UrSltn_Webgains_Block_Tracking extends Mage_Core_Block_Template
{
    /**
     * Get tracking code
     *
     * @return string[]
     */
    public function getTrackingCode()
    {
        $action = Mage::app()->getFrontController()->getAction()->getFullActionName();
        $trackingCode = [];

        if ($action == 'checkout_onepage_success' || $action == 'checkout_multishipping_success') {
            $lastOrderId = Mage::getSingleton('checkout/session')->getData('last_order_id');
            $order = Mage::getSingleton('sales/order');
            $order->load($lastOrderId);

            if ($order->getId()) {
                /** @var UrSltn_Webgains_Helper_Data $helper */
                $helper = $this->helper('ursltn_webgains');

                $lineItems = [];

                $items = $order->getAllItems();

                if (!empty($items)) {
                    /** @var Mage_Sales_Model_Order_Item $item */
                    foreach ($items as $item) {
                        $lineItems[] = [
                            'product'  => [
                                'id'            => $item->getProduct()->getId(),
                                'name'          => $item->getName(),
                                'unit_price'    => (float)$item->getPrice(),
                                'voucher'       => '',
                                'event_id'      => $helper->getTrackingEventId()
                            ],
                            'quantity' => (float)$item->getQtyOrdered(),
                            'subtotal' => (float)$item->getBaseRowTotal()
                        ];
                    }
                }

                if (!empty($lineItems)) {
                    $trackingCode = [
                        'version'       => '1.2',
                        'page'          => ['type' => ''],
                        'user'          => [
                            'user_id'   => $order->getCustomerId(),
                            'language'  => Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_LOCALE)
                        ],
                        'transaction'   => [
                            'event_id'      => $helper->getTrackingEventId(),
                            'order_id'      => $order->getIncrementId(),
                            'currency'      => $order->getOrderCurrencyCode(),
                            'comment'       => '',
                            'checksum'      => '',
                            'total'         => (float)$order->getSubtotal(),
                            'vouchers'      => [$order->getDiscountDescription()],
                            'line_items'    => $lineItems
                        ]
                    ];
                }
            }
        }

        return $trackingCode;
    }
}