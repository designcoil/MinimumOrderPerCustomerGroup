<?php
class Designcoil_MinimumOrder_Model_Sales_Quote_Address extends Mage_Sales_Model_Quote_Address
{
    /**
     * Validate minimum amount
     *
     * @return bool
     */
    public function validateMinimumAmount()
    {
        $storeId = $this->getQuote()->getStoreId();
        if (!Mage::getStoreConfigFlag('sales/minimum_order/active', $storeId)) {
            return true;
        }

        if ($this->getQuote()->getIsVirtual() && $this->getAddressType() == self::TYPE_SHIPPING) {
            return true;
        } elseif (!$this->getQuote()->getIsVirtual() && $this->getAddressType() != self::TYPE_SHIPPING) {
            return true;
        }
        // check if applies to customer group
        if(Mage::helper('minimumorder')->canApplyToCustomer()) {
            $amount = Mage::getStoreConfig('sales/minimum_order/amount', $storeId);

            if ($this->getBaseSubtotalWithDiscount() < $amount) {
                return false;
            }
        }
        return true;
    }
}
