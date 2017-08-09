<?php

/**
 * Main resource class
 */
class Toastauth_Resource_Resource
{
    protected $accessToken;

    protected $data = array();

    protected $endpoint;

    public function __construct($accessToken, $endPoint)
    {
        $this->accessToken = $accessToken;
        $this->endpoint    = $endPoint;
    }

    public function getProfile()
    {
        //https://www.googleapis.com/oauth2/v1/userinfo
        //https://graph.facebook.com/me
        //https://api.linkedin.com/v1/people/~:(id,first-name,last-name,email-address)?format=json
        return (array)json_decode($this->_getData('profile', $this->endpoint));
    }

    public function getId()
    {
        $profile = $this->getProfile();

        return $profile['id'];
    }

    protected function _getData($label, $url, $redirects = true)
    {
        if (!$this->_hasData($label)) {
            $value = Toastauth_OAuth2_Consumer::getData(
                $url,
                $this->accessToken['access_token'],
                $redirects
            );
            $this->_setData($label, $value);
        }

        return $this->data[$label];
    }

    protected function _setData($label, $value)
    {
        $this->data[$label] = $value;
    }

    protected function _hasData($label)
    {
        return isset($this->data[$label]) && (null !== $this->data[$label]);
    }
}