<?php


class Cartstatusemail_Models_Models_CartstatusemailRestoredCartModel extends Application_Model_Models_Abstract
{

    protected $_cartId = '';

    protected $_restoredAt = '';

    protected $_sentAt = '';

    protected $_code = '';

    protected $_userId = '';

    protected $_cartStatus = '';

    /**
     * @return string
     */
    public function getCartId()
    {
        return $this->_cartId;
    }

    /**
     * @param string $cartId
     * @return string
     */
    public function setCartId($cartId)
    {
        $this->_cartId = $cartId;

        return $this;
    }

    /**
     * @return string
     */
    public function getRestoredAt()
    {
        return $this->_restoredAt;
    }

    /**
     * @param string $restoredAt
     * @return string
     */
    public function setRestoredAt($restoredAt)
    {
        $this->_restoredAt = $restoredAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getSentAt()
    {
        return $this->_sentAt;
    }

    /**
     * @param string $sentAt
     * @return string
     */
    public function setSentAt($sentAt)
    {
        $this->_sentAt = $sentAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->_code;
    }

    /**
     * @param string $code
     * @return string
     */
    public function setCode($code)
    {
        $this->_code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->_userId;
    }

    /**
     * @param string $userId
     * @return string
     */
    public function setUserId($userId)
    {
        $this->_userId = $userId;

        return $this;
    }

    /**
     * @return string
     */
    public function getCartStatus()
    {
        return $this->_cartStatus;
    }

    /**
     * @param string $cartStatus
     * @return string
     */
    public function setCartStatus($cartStatus)
    {
        $this->_cartStatus = $cartStatus;

        return $this;
    }

}

