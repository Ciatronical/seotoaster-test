<?php

/**
 * Paypal configuration model
 *
 * Class Paypal_Models_Models_PaypalConfigModel
 */
class Paypal_Models_Models_PaypalConfigModel extends Application_Model_Models_Abstract
{

    protected $_id = '';
    protected $_email = '';
    protected $_apiSignature = '';
    protected $_apiUser = '';
    protected $_apiPassword = '';
    protected $_useSandbox = '';


    public function getId()
    {
        return $this->_id;
    }

    public function setId($id)
    {
        $this->_id = $id;
        return $this;
    }

    public function getEmail()
    {
        return $this->_email;
    }

    public function setEmail($email)
    {
        $this->_email = $email;
        return $this;
    }

    public function getApiSignature()
    {
        return $this->_apiSignature;
    }

    public function setApiSignature($apiSignature)
    {
        $this->_apiSignature = $apiSignature;
        return $this;
    }

    public function getApiUser()
    {
        return $this->_apiUser;
    }

    public function setApiUser($apiUser)
    {
        $this->_apiUser = $apiUser;
        return $this;
    }

    public function getApiPassword()
    {
        return $this->_apiPassword;
    }

    public function setApiPassword($apiPassword)
    {
        $this->_apiPassword = $apiPassword;
        return $this;
    }

    public function getUseSandbox()
    {
        return $this->_useSandbox;
    }

    public function setUseSandbox($useSandbox)
    {
        $this->_useSandbox = $useSandbox;
        return $this;
    }

}

