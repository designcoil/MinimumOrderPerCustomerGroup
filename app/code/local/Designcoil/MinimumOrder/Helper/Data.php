<?php
class Designcoil_MinimumOrder_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function canApplyToCustomer()
    {
        $group = Mage::getStoreConfig('sales/minimum_order/customer_groups',Mage::app()->getStore());
        $groupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
        if($group) {
            $group_array = explode(",",$group);
            if(in_array($groupId,$group_array)) {
                return true;
            } else {
                return false;
            }
        }
        
        return true;
    }

}