<?php

/**
 * Main adapter class
 */
class Toastauth_Auth_Adapter_Adapter implements Zend_Auth_Adapter_Interface
{
    protected $accessToken;
    protected $options;
    protected $providerName;

    public function __construct($requestToken, $provName, $options = null)
    {
        $this->options      = $options;
        $this->providerName = $provName;
        $this->_setRequestToken($requestToken);
    }

    public function authenticate()
    {
        $result             = array();
        $result['code']     = Zend_Auth_Result::FAILURE;
        $result['identity'] = null;
        $result['messages'] = array();
        $identName          = 'Toastauth_Auth_Identity_' . ucfirst($this->providerName);
        $identity           = new $identName($this->accessToken);
        if (null !== $identity->getId()) {
            $result['code']     = Zend_Auth_Result::SUCCESS;
            $result['identity'] = $identity;
        }

        return new Zend_Auth_Result(
            $result['code'],
            $result['identity'],
            $result['messages']
        );
    }

    protected function _setRequestToken($requestToken)
    {
        $this->options['code']    = $requestToken;
        $accesstoken              = Toastauth_OAuth2_Consumer::getAccessToken($this->options);
        $accesstoken['timestamp'] = time();
        $this->accessToken        = $accesstoken;
    }
}