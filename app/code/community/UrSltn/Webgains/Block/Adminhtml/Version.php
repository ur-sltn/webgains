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

class UrSltn_Webgains_Block_Adminhtml_Version extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * Get element HTML
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $html = "<i style='color:#2C9A54'>";
        $html .= Mage::helper('ursltn_webgains')->getModuleVersion();
        $html .= "</i>";

        return $html;
    }
}