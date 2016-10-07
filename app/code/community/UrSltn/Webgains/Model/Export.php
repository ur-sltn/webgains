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
     * @param string|int $profileId
     * @return void
     * @throws Mage_Core_Exception
     */
    public function products($profileId)
    {
        $profile = Mage::getModel('dataflow/profile');

        $profile->load($profileId);

        if (!$profile->getId()) {
            Mage::throwException('Unable to load export profile.');
        }

        if ($profile->getDirection() !== 'export') {
            Mage::throwException('Invalid export profile specified.');
        }

        // Prevent duplicate runs
        if (Mage::registry('current_convert_profile') instanceof Mage_Dataflow_Model_Profile) {
            throw new UrSltn_Webgains_Exception(
                'Product export aborted. It looks like the profile has already run in this session.'
            );
        }

        Mage::register('current_convert_profile', $profile);
        $profile->run();
    }
}