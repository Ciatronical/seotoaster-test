<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seotoaster
 * Date: 3/18/14
 * Time: 6:02 PM
 * To change this template use File | Settings | File Templates.
 */

class Api_Apps_Callrequest extends Api_Service_Abstract {

    protected $_user = null;

    protected $_sessionHelper = null;

    protected $_websiteHelper = null;

    protected $_accessList = array();

    public function init() {
        $acl = $this->getAcl();
        $resourceName = strtolower(__CLASS__.'_post');
        $acl->allow(null, $resourceName);
        Zend_Registry::set('acl', $acl);
        parent::init();
        $this->_sessionHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('session');
        $this->_websiteHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('website');
        $this->_translator    = Zend_Controller_Action_HelperBroker::getStaticHelper('language');
    }

    public function getAction() {

    }

    public function postAction()
    {
        $phoneNumber = $this->_request->getParam('clientPhoneNumber', '');
        $countryPhoneCode = Zend_Locale::getTranslation($this->_request->getParam('countryPhoneCode', ''), 'phoneToTerritory');
        $phoneNumber = Apps_Tools_Twilio::normalizePhoneNumberToE164($phoneNumber, $countryPhoneCode);
        if (empty($phoneNumber)) {
            return array('done' => false, 'message' => $this->_translator->translate('Please enter a phone number'));
        }
        $response = Apps::apiCall(
            'POST',
            'apps',
            array('twilioClickToCall'),
            array('callerPhoneNumber' => $phoneNumber)
        );
        if ($response === null) {
            if (Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_PLUGINS)) {
                $response['twilioClickToCall'] = array(
                    'done' => false,
                    'message' => $this->_translator->translate('Please open an account. You can find more information')
                        . ' <a href="http://www.seotoaster.com/seosamba-token-where-to-get-and-why-you-need-it.html">'
                            . $this->_translator->translate('here')
                        . '</a>');
            } else {
                $response['twilioClickToCall'] = array('done' => false);
            }
        }
        if ($response['twilioClickToCall']['done'] === false && !Tools_Security_Acl::isAllowed(
                Tools_Security_Acl::RESOURCE_PLUGINS
            )
        ) {
            $response['twilioClickToCall']['message'] = $this->_translator->translate("This service is temporarily not available.");
        }
        return $response['twilioClickToCall'];
    }

    public function putAction()
    {
        // TODO: Implement putAction() method.
    }

    public function deleteAction()
    {
        // TODO: Implement deleteAction() method.
    }

}