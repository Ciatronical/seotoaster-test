<?php

class Widgets_Auth_Auth extends Widgets_Abstract
{
    protected $_cacheable = false;
    private $_sessionHelper;
    private $_authDb;
    private $_website;
    private $_provider;
    private $_role;

    protected function  _init()
    {
        $this->_view          = new Zend_View(
            array(
                'scriptPath' => __DIR__ . '/views'
            )
        );
        $this->_website       = Zend_Controller_Action_HelperBroker::getStaticHelper('website');
        $this->_sessionHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('session');
        $this->_view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
        $this->_role = $this->_sessionHelper->getCurrentUser()->getRoleId();
    }

    protected function _load()
    {
        $this->_authDb   = Toastauth_Models_Mapper_ToastauthSettingsMapper::getInstance();
        $this->_provider = $this->_authDb->getProvider($this->_options[0]);
        if (1 === (int)$this->_provider['status']) {
            return $this->renderAuth();
        }
        throw new Exceptions_SeotoasterException('Can not render <strong>' . $this->_provider['name'] . '</strong>.');
    }

    protected function renderAuth()
    {
        $this->_view->providerName = $this->_provider['name'];
        if ($this->_role == Tools_Security_Acl::ROLE_GUEST) {
            $settings          = unserialize($this->_provider['settings']);
            $settings['state'] = $this->_toasterOptions['id'];
            $authUrl           = $settings['auth_url'];
            unset($settings['auth_url'], $settings['client_secret'], $settings['auth_url'], $settings['grant_type'], $settings['token_url']);
            $this->_view->providerUrl = $authUrl . '?' . http_build_query($settings);
            $this->_view->message     = $this->_sessionHelper->authError;
            unset($this->_sessionHelper->authError);
        } else {
            $this->_view->providerUrl = $this->_website->getUrl();
        }

        return $this->_view->render('login.phtml');
    }
}
