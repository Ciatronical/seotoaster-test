<?php

class Toastauth_Resource_Linkedin extends Toastauth_Resource_Resource
{

    public function getProfile()
    {
        $rawData = parent::getProfile();

        return array(
            'id'    => $rawData['id'],
            'name'  => "$rawData[firstName] $rawData[lastName]",
            'email' => $rawData['emailAddress']
        );
    }

    protected function _getData($label, $url, $redirects = true)
    {
        if (!$this->_hasData($label)) {
            $value = Toastauth_OAuth2_Consumer::getData(
                $url,
                $this->accessToken['access_token'],
                $redirects,
                'oauth2_access_token'

            );
            $this->_setData($label, $value);
        }

        return $this->data[$label];
    }
}
