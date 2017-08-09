<?php

class Toastauth_Auth_Adapter_Linkedin extends Toastauth_Auth_Adapter_Adapter
{
    protected function _setRequestToken($requestToken)
    {
        $this->options['code']      = $requestToken;
        $this->options['token_url'] = $this->options['token_url'] . '?';
        unset($this->options['scope'], $this->options['response_type']);
        $accesstoken              = Toastauth_OAuth2_Consumer::getAccessToken($this->options);
        $accesstoken['timestamp'] = time();
        $this->accessToken        = $accesstoken;
    }

}