<?php

class Toastauth_Auth_Identity_Facebook extends Toastauth_Auth_Identity_Generic
{
    protected $_api;

    public function __construct($token)
    {
        $this->_api  = new Toastauth_Resource_Facebook($token, 'https://graph.facebook.com/me');
        $this->_name = 'facebook';
        $this->_id   = $this->_api->getId();
    }

    public function getApi()
    {
        return $this->_api;
    }
}