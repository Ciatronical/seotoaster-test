<?php

/**
 * Paypal saving transaction info mapper
 *
 * Class Paypal_Models_Mapper_PaypalTransactionMapper
 */
class Paypal_Models_Mapper_PaypalTransactionMapper extends Application_Model_Mappers_Abstract
{

    /**
     * @var string
     */
    protected $_dbTable = 'Paypal_Models_Dbtables_PaypalTransactionDbtable';

    /**
     * @var string
     */
    protected $_model = 'Paypal_Models_Models_PaypalTransactionModel';

    /**
     * Save paypal transaction info
     *
     * @param Paypal_Models_Models_PaypalTransactionModel $transaction
     * @throws Exceptions_SeotoasterException
     */
    public function save($transaction)
    {
        if (!$transaction instanceof $this->_model) {
            throw new Exceptions_SeotoasterException('Given parameter should be ' . $this->_model . ' instance');
        }
        $data = array(

            'txnId' => $transaction->getTxnId(),
            'payerId' => $transaction->getPayerId(),
            'payerMail' => $transaction->getPayerMail(),
            'amount' => $transaction->getAmount(),
            'shippingAmount' => $transaction->getShippingAmount(),
            'tax' => $transaction->getTax(),
            'currency' => $transaction->getCurrency(),
            'paymentStatus' => $transaction->getPaymentStatus(),
            'status' => $transaction->getStatus(),
            'paymentType' => $transaction->getPaymentType(),
            'paymentId' => $transaction->getPaymentId(),
            'paymentDate' => $transaction->getPaymentDate(),
            'pFirstName' => $transaction->getPFirstName(),
            'pLastName' => $transaction->getPLastName(),
            'pCountry' => $transaction->getPCountry(),
            'pCountryCode' => $transaction->getPCountryCode(),
            'pAddressState' => $transaction->getPAddressState(),
            'pAddressCity' => $transaction->getPAddressCity(),
            'pAddressZip' => $transaction->getPAddressZip(),
            'pAddressName' => $transaction->getPAddressName(),
            'cartId' => $transaction->getCartId(),
            'pendingReason' => $transaction->getPendingReason(),
            'subscribeStatus' => $transaction->getSubscribeStatus(),
            'subscribePeriod' => $transaction->getSubscribePeriod(),
            'subscribePeriodType' => $transaction->getSubscribePeriodType(),
            'subscribeQuantity' => $transaction->getSubscribeQuantity(),
            'subscribeAmount' => $transaction->getSubscribeAmount(),
            'subscribeDate' => $transaction->getSubscribeDate(),
            'subscriptionId' => $transaction->getSubscriptionId(),
            'subscriptionDatePayed' => $transaction->getSubscriptionAmountPayed(),
            'subscriptionAmountPayed' => $transaction->getSubscriptionDatePayed(),
            'emailSent' => $transaction->getEmailSent(),
            'customerEmailSent' => $transaction->getCustomerEmailSent(),
            'refundTransactionId' => $transaction->getRefundTransactionId(),
            'refundReason' => $transaction->getRefundReason()
        );

        $transactionId = $transaction->getId();
        if ($transactionId) {
            $where = $this->getDbTable()->getAdapter()->quoteInto('id = ?', $transactionId);
            $this->getDbTable()->update($data, $where);
        } else {
            $this->getDbTable()->insert($data);
        }

    }

    /**
     * Find transaction by subscribe id
     *
     * @param int $subscribeId subscription id
     * @return array|null
     */
    public function getSubscribeBySubscribeId($subscribeId)
    {
        $where = $this->getDbTable()->getAdapter()->quoteInto('subscriptionId = ?', $subscribeId);
        return $this->fetchAll($where);
    }

    /**
     * Update paypal subscription
     *
     * @param array $subscriptionData
     * @param int $subscribeId subscription id
     */
    public function updateSubscription($subscriptionData, $subscribeId)
    {
        $where = $this->getDbTable()->getAdapter()->quoteInto('subscriptionId = ?', $subscribeId);
        $this->getDbTable()->update($subscriptionData, $where);
    }

    /**
     * Delete transaction by cartId
     *
     * @param int $cartId cart id
     * @return mixed
     */
    public function deleteTransaction($cartId)
    {
        $where = $this->getDbTable()->getAdapter()->quoteInto('cartId = ?', $cartId);
        return $this->getDbTable()->delete($where);
    }

    /**
     * Find transaction by cart id
     *
     * @param int $cartId cart id
     * @param string $status paymentStatus
     * @return array|null
     */
    public function findByCartId($cartId, $status = false)
    {
        $where = $this->getDbTable()->getAdapter()->quoteInto('cartId = ?', $cartId);
        if ($status) {
            $where .= ' AND ' . $this->getDbTable()->getAdapter()->quoteInto('status= ?', $status);
        }
        return $this->fetchAll($where);
    }

}

