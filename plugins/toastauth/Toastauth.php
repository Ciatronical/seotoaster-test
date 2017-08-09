<?php

/**
 * Auth with social networks
 *
 * Class Toastauth
 */
class Toastauth extends Tools_Plugins_Abstract
{
    protected $_auth;

    /**
     * Help section
     */
    const SECTION_TOASTAUTH = 'toastauth';

    /**
     * Secure token
     */
    const TOASTERAUTH_TOKEN = 'ToastauthToken';

    /**
     * Help link
     *
     * @var array
     */
    private $_helpHashMap = array(
        self::SECTION_TOASTAUTH => 'single-sign-on-membership-cms.html'
    );

    protected function _init()
    {
        $this->_layout = new Zend_Layout();
        $this->_layout->setLayoutPath(Zend_Layout::getMvcInstance()->getLayoutPath());
        if (($scriptPaths = Zend_Layout::getMvcInstance()->getView()->getScriptPaths()) !== false) {
            $this->_view->setScriptPath($scriptPaths);
        }
        $this->_view->addScriptPath(__DIR__ . '/system/views/');
        $this->_auth = Toastauth_Auth::getInstance();
    }

    /**
     * Login user trough social network in system
     */
    public function loginAction()
    {
        $params = $this->_request->getParams();
        $role   = $this->_sessionHelper->getCurrentUser()->getRoleId();
        if ($role !== Tools_Security_Acl::ROLE_GUEST || empty($params['provider'])) {
            $this->_redirector->gotoUrlAndExit($this->_websiteUrl);
        }
        $provName           = filter_var($params['provider'], FILTER_SANITIZE_STRING);
        $state              = (int)$params['state'];
        $authSettingsMapper = Toastauth_Models_Mapper_ToastauthSettingsMapper::getInstance();
        $pageUrl            = Application_Model_Mappers_PageMapper::getInstance()->find($state)->getUrl();
        if (!empty($this->_sessionHelper->authError)) {
            $this->_redirector->gotoUrlAndExit($this->_websiteUrl . $pageUrl);
        }
        $adapterName   = 'Toastauth_Auth_Adapter_' . ucfirst($provName);
        $adapterExists = class_exists($adapterName);
        $provider      = $authSettingsMapper->getProvider($provName);
        if (!empty($params['code']) && $adapterExists) {
            $adapter = new $adapterName($params['code'], $provName, unserialize($provider['settings']));
        } else {
            $this->_sessionHelper->authError = "No access token";
            $this->_redirector->gotoUrlAndExit($this->_websiteUrl . $pageUrl);
        }
        $result = $this->_auth->authenticate($adapter);
        if (!$result->isValid()) {
            $this->_auth->clearIdentity($provName);
            $this->_redirector->gotoUrlAndExit($this->_websiteUrl . $pageUrl);
        }
        $this->_redirector->gotoUrlAndExit($this->_websiteUrl . 'plugin/toastauth/run/connect/');
    }

    /**
     * Connect to social network
     *
     * @throws Zend_Controller_Action_Exception
     */
    public function connectAction()
    {
        if (!$this->_auth->hasIdentity()) {
            throw new Zend_Controller_Action_Exception('Not logged in!', 404);
        }
        $providers = $this->_auth->getIdentity();
        foreach ($providers as $provider) {
            $id = $provider->getApi()->getId();
            if (!empty($id)) {
                $userData  = $provider->getApi()->getProfile();
                $userEmail = filter_var($userData['email'], FILTER_SANITIZE_EMAIL);
                $userName  = filter_var($userData['name'], FILTER_SANITIZE_STRING);
                $user      = Application_Model_Mappers_UserMapper::getInstance()->findByEmail($userEmail);
                if (!empty($user)) {
                    $userRole = $user->getRoleId();
                    if ($userRole == 'member' || $userRole == 'customer') {
                        $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Registry::get('dbAdapter'), 'user', 'email',
                            'email');
                        $authAdapter->setIdentity($userEmail);
                        $authAdapter->setCredential($userEmail);
                        $authResult = $authAdapter->authenticate();
                        if ($authResult->isValid()) {
                            $authUserData = $authAdapter->getResultRowObject(null, 'password');
                            if (null !== $authUserData) {
                                $user = new Application_Model_Models_User((array)$authUserData);
                                $user->setLastLogin(date(Tools_System_Tools::DATE_MYSQL));
                                $user->setIpaddress($_SERVER['REMOTE_ADDR']);
                                $this->_sessionHelper->setCurrentUser($user);
                                Application_Model_Mappers_UserMapper::getInstance()->save($user);
                            }
                        }
                    } else {
                        $this->_sessionHelper->authError = "Admins can't login through social networks. Please login through " . $this->_websiteUrl . 'go';
                    }
                } else {
                    $genPass         = chr(mt_rand(97, 122)) . substr(md5(time()), 3, 10);
                    $user            = new Application_Model_Models_User(
                        array('email' => $userEmail, 'fullName' => $userName, 'password' => $genPass)
                    );
                    $signUpObseerver = (
                    new Tools_Mail_Watchdog(array('trigger' => Tools_Mail_SystemMailWatchdog::TRIGGER_SIGNUP)));
                    $user->registerObserver($signUpObseerver);
                    $user->setRoleId(Tools_Security_Acl::ROLE_MEMBER);
                    if (isset($this->_helper->session->refererUrl)) {
                        $user->setReferer($this->_helper->session->refererUrl);
                    }
                    $signupResult = Application_Model_Mappers_UserMapper::getInstance()->save($user);
                    if (!$user->getId()) {
                        $user->setId($signupResult);
                    }
                    $user->notifyObservers();
                    $user->removeObserver($signUpObseerver);
                    $this->_sessionHelper->setCurrentUser($user);
                }
            }
        }
        $this->_redirector->gotoUrlAndExit($this->_websiteUrl);
    }

    /**
     * Config action
     */
    public function authConfigAction()
    {
        if (Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_PLUGINS)) {
            $authSettingsMapper = Toastauth_Models_Mapper_ToastauthSettingsMapper::getInstance();
            $providers          = $authSettingsMapper->getProviders();
            if ($this->_request->isPost()) {
                $data        = $this->_request->getParams();
                $secureToken = $this->_request->getParam(Tools_System_Tools::CSRF_SECURE_TOKEN, false);
                $tokenValid  = Tools_System_Tools::validateToken($secureToken, self::TOASTERAUTH_TOKEN);
                if (!$tokenValid) {
                    $this->_responseHelper->fail('');
                }
                $authSettingsModel = new Toastauth_Models_Models_ToastauthSettingsModel();
                foreach ($providers as $providerName => $provider) {
                    $provider['settings']                 = unserialize($provider['settings']);
                    $provider['settings']['redirect_uri'] = $this->_websiteUrl . 'plugin/toastauth/run/login/provider/' . $providerName;
                    if (empty($data[$providerName]['status'])) {
                        $authSettingsModel->setStatus('0');
                    } else {
                        $authSettingsModel->setStatus('1');
                    }
                    unset($data[$providerName]['status']);
                    $authSettingsModel->setSettings(serialize(array_replace($provider['settings'],
                        $data[$providerName])));
                    $authSettingsModel->setName($providerName);
                    $authSettingsMapper->save($authSettingsModel);
                }
                $this->_responseHelper->success($this->_translator->translate('Changed'));
            } else {
                foreach ($providers as &$provider) {
                    $provider['settings']      = unserialize($provider['settings']);
                    $provider['client_id']     = $provider['settings']['client_id'];
                    $provider['client_secret'] = $provider['settings']['client_secret'];
                    $provider['redirect_uri']  = $provider['settings']['redirect_uri'];
                    unset($provider['settings']);
                }
                $this->_view->translator  = $this->_translator;
                $this->_view->providers   = $providers;
                $this->_view->helpSection = self::SECTION_TOASTAUTH;
                $this->_view->hashMap     = $this->_helpHashMap;
                $this->_layout->content   = $this->_view->render('config.phtml');
                echo $this->_layout->render();
            }
        }
    }
}
