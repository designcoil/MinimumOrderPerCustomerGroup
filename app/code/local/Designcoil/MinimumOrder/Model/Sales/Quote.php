<?php
class Designcoil_MinimumOrder_Model_Sales_Quote extends Mage_Sales_Model_Quote
{
    /**
     * Validate minimum amount
     *
     * @return bool
     */
    public function validateMinimumAmount($multishipping = false)
    {
        $storeId = $this->getStoreId();
        $minOrderActive = Mage::getStoreConfigFlag('sales/minimum_order/active', $storeId);
        $minOrderMulti  = Mage::getStoreConfigFlag('sales/minimum_order/multi_address', $storeId);
        $minAmount      = Mage::getStoreConfig('sales/minimum_order/amount', $storeId);

        if (!$minOrderActive) {
            return true;
        }

        $addresses = $this->getAllAddresses();

        if ($multishipping) {
            // check if applies to customer group
            if(Mage::helper('minimumorder')->canApplyToCustomer()) {
                if ($minOrderMulti) {
                    foreach ($addresses as $address) {
                        foreach ($address->getQuote()->getItemsCollection() as $item) {
                            $amount = $item->getBaseRowTotal() - $item->getBaseDiscountAmount();
                            if ($amount < $minAmount) {
                                return false;
                            }
                        }
                    }
                } else {
                    $baseTotal = 0;
                    foreach ($addresses as $address) {
                        /* @var $address Mage_Sales_Model_Quote_Address */
                        $baseTotal += $address->getBaseSubtotalWithDiscount();
                    }
                    if ($baseTotal < $minAmount) {
                        return false;
                    }
                }
            }
        } else {
            foreach ($addresses as $address) {
                /* @var $address Mage_Sales_Model_Quote_Address */
                if (!$address->validateMinimumAmount()) {
                    return false;
                }
            }
        }
        return true;
    }
}
