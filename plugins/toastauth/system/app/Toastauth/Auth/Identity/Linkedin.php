<?php

class Toastauth_Auth_Identity_Linkedin extends Toastauth_Auth_Identity_Generic
{
    protected $_api;

    public function __construct($token)
    {
        $this->_api  = new Toastauth_Resource_Linkedin($token,
            'https://api.linkedin.com/v1/people/~:(id,first-name,last-name,email-address)?format=json');
        $this->_name = 'linkedin';
        $this->_id   = $this->_api->getId();
    }

    public function getApi()
    {
        return $this->_api;
    }
}