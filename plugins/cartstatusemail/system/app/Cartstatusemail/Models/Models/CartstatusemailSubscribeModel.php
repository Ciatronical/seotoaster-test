<?php


class Cartstatusemail_Models_Models_CartstatusemailSubscribeModel extends Application_Model_Models_Abstract {

    /**
     * Status of user subscription ('subscribed', 'unsubscribed')
     *
     * @var string
     */
    protected $_status = '';

    /**
     * unique unsubscribe code
     *
     * @var string
     */
    protected $_code = '';

    /**
     * system user id
     *
     * @var string
     */
    protected $_userId = '';

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * @param string $status
     * @return string
     */
    public function setStatus($status)
    {
        $this->_status = $status;

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


}

