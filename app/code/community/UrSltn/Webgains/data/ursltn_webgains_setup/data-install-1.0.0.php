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

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

/**
 * Dataflow profile data
 */
$dataflowData = [
    [
        'name'         => 'Webgains Product Export',
        'actions_xml'  => '<action type="catalog/convert_adapter_product" method="load">\r\n    <var name="store"><![CDATA[0]]></var>\r\n</action>\r\n\r\n<action type="catalog/convert_parser_product" method="unparse">\r\n    <var name="store"><![CDATA[0]]></var>\r\n</action>\r\n\r\n<action type="dataflow/convert_mapper_column" method="map">\r\n</action>\r\n\r\n<action type="dataflow/convert_parser_csv" method="unparse">\r\n    <var name="delimiter"><![CDATA[,]]></var>\r\n    <var name="enclose"><![CDATA["]]></var>\r\n    <var name="fieldnames">true</var>\r\n</action>\r\n\r\n<action type="dataflow/convert_adapter_io" method="save">\r\n    <var name="type">file</var>\r\n    <var name="path">var/export</var>\r\n    <var name="filename"><![CDATA[webgains_product_export.csv]]></var>\r\n</action>\r\n\r\n',
        'gui_data'     => 'a:5:{s:4:"file";a:7:{s:4:"type";s:4:"file";s:8:"filename";s:27:"webgains_product_export.csv";s:4:"path";s:10:"var/export";s:4:"host";s:0:"";s:4:"user";s:0:"";s:8:"password";s:0:"";s:7:"passive";s:0:"";}s:5:"parse";a:5:{s:4:"type";s:3:"csv";s:12:"single_sheet";s:0:"";s:9:"delimiter";s:1:",";s:7:"enclose";s:1:""";s:10:"fieldnames";s:4:"true";}s:3:"map";a:3:{s:14:"only_specified";s:0:"";s:7:"product";a:2:{s:2:"db";a:0:{}s:4:"file";a:0:{}}s:8:"customer";a:2:{s:2:"db";a:0:{}s:4:"file";a:0:{}}}s:7:"product";a:1:{s:6:"filter";a:8:{s:4:"name";s:0:"";s:3:"sku";s:0:"";s:4:"type";s:1:"0";s:13:"attribute_set";s:0:"";s:5:"price";a:2:{s:4:"from";s:0:"";s:2:"to";s:0:"";}s:3:"qty";a:2:{s:4:"from";s:0:"";s:2:"to";s:0:"";}s:10:"visibility";s:1:"0";s:6:"status";s:1:"0";}}s:8:"customer";a:1:{s:6:"filter";a:10:{s:9:"firstname";s:0:"";s:8:"lastname";s:0:"";s:5:"email";s:0:"";s:5:"group";s:1:"0";s:10:"adressType";s:15:"default_billing";s:9:"telephone";s:0:"";s:8:"postcode";s:0:"";s:7:"country";s:0:"";s:6:"region";s:0:"";s:10:"created_at";a:2:{s:4:"from";s:0:"";s:2:"to";s:0:"";}}}}',
        'direction'    => 'export',
        'entity_type'  => 'product',
        'store_id'     => 0,
        'data_transfer'=> 'file'
    ]
];

foreach ($dataflowData as $bind) {
    Mage::getModel('dataflow/profile')->setData($bind)->save();
}

$installer->endSetup();
