<?php

class Widgets_Clicktocall_Clicktocall extends Widgets_Abstract {

    protected $_cacheable = false;

    private $_sessionHelper = null;

    private $_request = null;

    private $_website = null;

    protected function  _init() {
        parent::_init();
        $this->_view = new Zend_View(array(
            'scriptPath' => dirname(__FILE__) . '/views'
        ));
        $this->_view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
        $this->_website = Zend_Controller_Action_HelperBroker::getStaticHelper('website');
        $this->_view->websiteUrl = $this->_website->getUrl();
        $this->_sessionHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('session');
        $front = Zend_Controller_Front::getInstance();
        $this->_request = $front->getRequest();
    }

    protected function  _load() {
        $command     = $this->_options[0];
        $rendererName = '_render' . ucfirst($command);
        if(method_exists($this, $rendererName)) {
            return $this->$rendererName();
        }
        throw new Exceptions_SeotoasterException('Can not render <strong>' . $command . '</strong>.');
    }

    private function _renderCall() {
        $scriptAdded = Zend_Registry::isRegistered('appClickToCall') ? true : false;
        if($scriptAdded) {
            $this->_view->scriptAdded = $scriptAdded;
        }else {
            Zend_Registry::set('appClickToCall', $scriptAdded);
        }

        if (!empty($this->_options[1])) {
            $title = filter_var($this->_options[1], FILTER_SANITIZE_STRING);
        } else {
            $title = '';
        }
        if (!empty($this->_options[2])) {
            $buttonName = filter_var($this->_options[2], FILTER_SANITIZE_STRING);
        } else {
            $buttonName = 'Connect me!';
        }
        if (!empty($this->_options[3]) && !empty($this->_options[4]) && $this->_options[3] !== 'country') {
            $this->_view->formImage = filter_var(implode(':', array($this->_options[3], $this->_options[4])), FILTER_SANITIZE_URL);
        }
        $this->_view->countryCodes = array();
        $countryOptionKey = array_search('country', $this->_options);
        if($countryOptionKey !== false && isset($this->_options[$countryOptionKey + 1])) {
            $countryCodes = explode(',', $this->_options[$countryOptionKey + 1]);
            if(!empty($countryCodes)) {
                $this->_view->countryCodes = $countryCodes;
            }
        }
        $this->_view->formTitle = $title;
        $this->_view->formBtnName = $buttonName;
        return $this->_view->render('clicktocall.phtml');
    }


    public static function getWidgetMakerContent()
    {
        $translator    = Zend_Registry::get('Zend_Translate');
        $view          = new Zend_View(array('scriptPath' => dirname(__FILE__).'/views'));
        $websiteHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('website');
        $view->websiteUrl = $websiteHelper->getUrl();
        $data          = array(
            'title'   => $translator->translate('Click to call'),
            'content' => $view->render('wmcontent.phtml'),
            'icons'   => array($websiteHelper->getUrl().'system/images/widgets/clicktocall.png')
        );
        unset($view, $translator);

        return $data;
    }

}