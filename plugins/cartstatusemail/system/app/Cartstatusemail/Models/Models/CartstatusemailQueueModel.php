<?php


class Cartstatusemail_Models_Models_CartstatusemailQueueModel extends Application_Model_Models_Abstract
{

    protected $_cartStatusId = '';
    protected $_status = 0;
    protected $_cartId = '';
    protected $_userEmail = '';
    protected $_userFullName = '';
    protected $_cartStatus = '';
    protected $_emailFrom = '';
    protected $_emailMessage = '';
    protected $_emailTemplate = '';
    protected $_sentAt = '';

    /**
     * @return string
     */
    public function getCartStatusId()
    {
        return $this->_cartStatusId;
    }

    /**
     * @param string $cartStatusId
     * @return string
     */
    public function setCartStatusId($cartStatusId)
    {
        $this->_cartStatusId = $cartStatusId;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * @param int $status
     * @return int
     */
    public function setStatus($status)
    {
        $this->_status = $status;

        return $this;
    }

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
    public function getUserEmail()
    {
        return $this->_userEmail;
    }

    /**
     * @param string $userEmail
     * @return string
     */
    public function setUserEmail($userEmail)
    {
        $this->_userEmail = $userEmail;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserFullName()
    {
        return $this->_userFullName;
    }

    /**
     * @param string $userFullName
     * @return string
     */
    public function setUserFullName($userFullName)
    {
        $this->_userFullName = $userFullName;

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

    /**
     * @return string
     */
    public function getEmailFrom()
    {
        return $this->_emailFrom;
    }

    /**
     * @param string $emailFrom
     * @return string
     */
    public function setEmailFrom($emailFrom)
    {
        $this->_emailFrom = $emailFrom;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmailMessage()
    {
        return $this->_emailMessage;
    }

    /**
     * @param string $emailMessage
     * @return string
     */
    public function setEmailMessage($emailMessage)
    {
        $this->_emailMessage = $emailMessage;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmailTemplate()
    {
        return $this->_emailTemplate;
    }

    /**
     * @param string $emailTemplate
     * @return string
     */
    public function setEmailTemplate($emailTemplate)
    {
        $this->_emailTemplate = $emailTemplate;

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

}

