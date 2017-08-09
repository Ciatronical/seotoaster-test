<?php

class Toastauth_OAuth2_Consumer
{

    public static function getAccessToken($urlparams)
    {
        $authparams = array();
        if (!isset($urlparams['token_url'])) {
            throw new Exception('No token url specified');
        }
        if (isset($urlparams['client_id'])) {
            $authparams['client_id'] = $urlparams['client_id'];
        }
        if (isset($urlparams['client_secret'])) {
            $authparams['client_secret'] = $urlparams['client_secret'];
        }
        if (isset($urlparams['redirect_uri'])) {
            $authparams['redirect_uri'] = $urlparams['redirect_uri'];
        }
        if (isset($urlparams['scope'])) {
            $authparams['scope'] = $urlparams['scope'];
        }
        if (isset($urlparams['code'])) {
            $authparams['code'] = $urlparams['code'];
        }
        if (isset($urlparams['grant_type'])) {
            $authparams['grant_type'] = $urlparams['grant_type'];
        }

        $client = new Zend_Http_Client();
        $client->setUri($urlparams['token_url']);
        $client->setParameterPost($authparams);
        $response    = $client->request('POST');
        $contentType = explode(';', $response->getHeader("Content-type"));
        if ($contentType[0] == "application/json" || $contentType[0] == "text/javascript") {
            $token = json_decode($response->getBody(), true);
        } else {
            $token = null;
            parse_str($response->getBody(), $token);
        }
        if (array_key_exists('error', $token)) {
            $session            = Zend_Controller_Action_HelperBroker::getStaticHelper('session');
            $session->authError = $token['error']['message'];
        }

        return $token;
    }

    public static function getData($url, $accesstoken, $redirects = true, $accessTokenName = 'access_token')
    {
        $client = new Zend_Http_Client();
        $client->setUri($url);
        $client->setParameterGet($accessTokenName, $accesstoken);
        if ($redirects) {
            $response = $client->request('GET')->getBody();
        } else {
            $client->setConfig(array('maxredirects' => 0));
            $response = $client->request()->getHeader('Location');
            $client->setConfig(array('maxredirects' => 5));
        }

        return $response;
    }
}