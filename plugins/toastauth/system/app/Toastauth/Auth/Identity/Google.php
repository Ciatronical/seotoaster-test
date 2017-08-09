<?php

class Toastauth_Auth_Identity_Google extends Toastauth_Auth_Identity_Generic
{
    protected $_api;

    public function __construct($token)
    {
        $this->_api  = new Toastauth_Resource_Google($token, 'https://www.googleapis.com/oauth2/v1/userinfo');
        $this->_name = 'google';
        $this->_id   = $this->_api->getId();
    }

    public function getApi()
    {
        return $this->_api;
    }
}