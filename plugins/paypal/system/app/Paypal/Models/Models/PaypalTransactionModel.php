<?php

/**
 * Paypal transaction model
 *
 * Class Paypal_Models_Models_PaypalTransactionModel
 */
class Paypal_Models_Models_PaypalTransactionModel extends Application_Model_Models_Abstract
{

    protected $_id = '';
    protected $_txnId = '';
    protected $_payerId = '';
    protected $_payerMail = '';
    protected $_amount = '';
    protected $_shippingAmount = '';
    protected $_tax = '';
    protected $_currency = '';
    protected $_paymentStatus = '';
    protected $_status = '';
    protected $_paymentType = '';
    protected $_paymentId = '';
    protected $_paymentDate = '';
    protected $_pFirstName = '';
    protected $_pLastName = '';
    protected $_pCountry = '';
    protected $_pCountryCode = '';
    protected $_pAddressState = '';
    protected $_pAddressCity = '';
    protected $_pAddressZip = '';
    protected $_pAddressName = '';
    protected $_cartId = '';
    protected $_pendingReason = '';
    protected $_subscribeStatus = '';
    protected $_subscribePeriod = '';
    protected $_subscribePeriodType = '';
    protected $_subscribeQuantity = '';
    protected $_subscribeAmount = '';
    protected $_subscribeDate = '';
    protected $_subscriptionId = '';
    protected $_subscriptionDatePayed = '';
    protected $_subscriptionAmountPayed = '';
    protected $_emailSent = 0;
    protected $_customerEmailSent = 0;
    protected $_refundTransactionId = '';
    protected $_refundReason = '';

    public function getId()
    {
        return $this->_id;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getCartId()
    {
        return $this->_cartId;
    }

    public function setCartId($cartId)
    {
        $this->_cartId = $cartId;
    }

    public function getTxnId()
    {
        return $this->_txnId;
    }

    public function setTxnId($txnId)
    {
        $this->_txnId = $txnId;
        return $this;
    }

    public function getPayerId()
    {
        return $this->_payerId;
    }

    public function setPayerId($payerId)
    {
        $this->_payerId = $payerId;
        return $this;
    }

    public function getPayerMail()
    {
        return $this->_payerMail;
    }

    public function setPayerMail($payerMail)
    {
        $this->_payerMail = $payerMail;
    }

    public function getAmount()
    {
        return $this->_amount;
    }

    public function setAmount($amount)
    {
        $this->_amount = $amount;
    }

    public function getShippingAmount()
    {
        return $this->_shippingAmount;
    }

    public function setShippingAmount($shippingAmount)
    {
        $this->_shippingAmount = $shippingAmount;
    }

    public function getTax()
    {
        return $this->_tax;
    }

    public function setTax($tax)
    {
        $this->_tax = $tax;
    }

    public function getCurrency()
    {
        return $this->_currency;
    }

    public function setCurrency($currency)
    {
        $this->_currency = $currency;
    }

    public function getPaymentStatus()
    {
        return $this->_paymentStatus;
    }

    public function setPaymentStatus($paymentStatus)
    {
        $this->_paymentStatus = $paymentStatus;
    }

    public function getStatus()
    {
        return $this->_status;
    }

    public function setStatus($status)
    {
        $this->_status = $status;
    }

    public function getPaymentType()
    {
        return $this->_paymentType;
    }

    public function setPaymentType($paymentType)
    {
        $this->_paymentType = $paymentType;
    }

    public function getPaymentId()
    {
        return $this->_paymentId;
    }

    public function setPaymentId($paymentId)
    {
        $this->_paymentId = $paymentId;
    }

    public function getPaymentDate()
    {
        return $this->_paymentDate;
    }

    public function setPaymentDate($paymentDate)
    {
        $this->_paymentDate = $paymentDate;
    }

    public function getPFirstName()
    {
        return $this->_pFirstName;
    }

    public function setPFirstName($pFirstName)
    {
        $this->_pFirstName = $pFirstName;
    }

    public function getPLastName()
    {
        return $this->_pLastName;
    }

    public function setPLastName($pLastName)
    {
        $this->_pLastName = $pLastName;
    }

    public function getPCountry()
    {
        return $this->_pCountry;
    }

    public function setPCountry($pCountry)
    {
        $this->_pCountry = $pCountry;
    }

    public function getPCountryCode()
    {
        return $this->_pCountryCode;
    }

    public function setPCountryCode($pCountryCode)
    {
        $this->_pCountryCode = $pCountryCode;
    }

    public function getPAddressState()
    {
        return $this->_pAddressState;
    }

    public function setPAddressState($pAddressState)
    {
        $this->_pAddressState = $pAddressState;
    }

    public function getPAddressCity()
    {
        return $this->_pAddressCity;
    }

    public function setPAddressCity($pAddressCity)
    {
        $this->_pAddressCity = $pAddressCity;
    }

    public function getPAddressZip()
    {
        return $this->_pAddressZip;
    }

    public function setPAddressZip($pAddressZip)
    {
        $this->_pAddressZip = $pAddressZip;
    }

    public function getPAddressName()
    {
        return $this->_pAddressName;
    }

    public function setPAddressName($pAddressName)
    {
        $this->_pAddressName = $pAddressName;
    }

    public function getPendingReason()
    {
        return $this->_pendingReason;
    }

    public function setPendingReason($pendingReason)
    {
        $this->_pendingReason = $pendingReason;
    }


    public function setSubscribePeriod($subscribePeriod)
    {
        $this->_subscribePeriod = $subscribePeriod;
        return $this;
    }

    public function getSubscribePeriod()
    {
        return $this->_subscribePeriod;
    }

    public function setSubscribeStatus($subscribeStatus)
    {
        $this->_subscribeStatus = $subscribeStatus;
        return $this;
    }

    public function getSubscribeStatus()
    {
        return $this->_subscribeStatus;
    }

    public function setSubscribeQuantity($subscribeQuantity)
    {
        $this->_subscribeQuantity = $subscribeQuantity;
        return $this;
    }

    public function getSubscribeQuantity()
    {
        return $this->_subscribeQuantity;
    }

    public function setSubscribePeriodType($subscribePeriodType)
    {
        $this->_subscribePeriodType = $subscribePeriodType;
        return $this;
    }

    public function getSubscribePeriodType()
    {
        return $this->_subscribePeriodType;
    }

    public function setSubscribeAmount($subscribeAmount)
    {
        $this->_subscribeAmount = $subscribeAmount;
        return $this;
    }

    public function getSubscribeAmount()
    {
        return $this->_subscribeAmount;
    }

    public function setSubscribeDate($subscribeDate)
    {
        $this->_subscribeDate = $subscribeDate;
        return $this;
    }

    public function getSubscribeDate()
    {
        return $this->_subscribeDate;
    }

    public function setSubscriptionId($subscriptionId)
    {
        $this->_subscriptionId = $subscriptionId;
        return $this;
    }

    public function getSubscriptionId()
    {
        return $this->_subscriptionId;
    }

    public function setSubscriptionAmountPayed($subscriptionAmountPayed)
    {
        $this->_subscriptionAmountPayed = $subscriptionAmountPayed;
        return $this;
    }

    public function getSubscriptionAmountPayed()
    {
        return $this->_subscriptionAmountPayed;
    }

    public function setSubscriptionDatePayed($subscriptionDatePayed)
    {
        $this->_subscriptionDatePayed = $subscriptionDatePayed;
        return $this;
    }

    public function getSubscriptionDatePayed()
    {
        return $this->_subscriptionDatePayed;
    }

    public function setEmailSent($emailSent)
    {
        $this->_emailSent = $emailSent;
        return $this;
    }

    public function getEmailSent()
    {
        return $this->_emailSent;
    }

    /**
     * @return int
     */
    public function getCustomerEmailSent()
    {
        return $this->_customerEmailSent;
    }

    /**
     * @param int $customerEmailSent
     * @return int
     */
    public function setCustomerEmailSent($customerEmailSent)
    {
        $this->_customerEmailSent = $customerEmailSent;

        return $this;
    }
    /**
     * @return string
     */
    public function getRefundTransactionId()
    {
        return $this->_refundTransactionId;
    }

    /**
     * @param string $refundTransactionId
     */
    public function setRefundTransactionId($refundTransactionId)
    {
        $this->_refundTransactionId = $refundTransactionId;
    }

    /**
     * @return string
     */
    public function getRefundReason()
    {
        return $this->_refundReason;
    }

    /**
     * @param string $refundReason
     */
    public function setRefundReason($refundReason)
    {
        $this->_refundReason = $refundReason;
    }
}

