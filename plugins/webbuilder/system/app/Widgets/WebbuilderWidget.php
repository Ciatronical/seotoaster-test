<?php

class Widgets_WebbuilderWidget extends Widgets_Abstract {

    const OPTION_READONLY     = 'readonly';

    const DEFAULT_THUMB_SIZE  = 250;

    /**
     * Cache flag, shows whether this widget cached
     *
     * @var bool
     */
    protected $_cacheable     = false;

    /**
     * Shows whether debug mode is on
     *
     * @var bool
     */
    protected $_debugMode     = false;

    /**
     * Website url
     *
     * @var string
     */
    protected $_websiteUrl    = '';

    /**
     * Toaster website helper
     *
     * @var Helpers_Action_Website
     */
    protected $_websiteHelper = null;

    /**
     * Toaster config helper
     *
     * @var Helpers_Action_Config
     */
    protected $_configHelper  = null;

    /**
     * Toaster session helper
     *
     * @var Helpers_Action_Session
     */
    protected $_sessionHelper = null;

    /**
     * Path to plugin's languages files
     *
     * @var string
     */
    protected $_languagesPath = 'system/languages/';


    protected function _init() {
        // init debug mode flag
        $this->_debugMode = Tools_System_Tools::debugMode();
        // if no options passed to the widget, we will show error immediately
        if (empty($this->_options)){
            $this->_error('Not enough parameters');
        }
        // init website related properties
        $this->_websiteHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('website');
        $this->_websiteUrl    = $this->_websiteHelper->getUrl();
        //$this->_initTranslator();

        // init view and set appropriate helpers
        $explodedClassName = explode('_', get_called_class());
        $this->_view       = new Zend_View(array('scriptPath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . end($explodedClassName) . DIRECTORY_SEPARATOR . 'views'));

        $this->_view->setHelperPath(APPLICATION_PATH . '/views/helpers/');
        $this->_view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
        $this->_view->addScriptPath($this->_websiteHelper->getPath() . 'seotoaster_core/application/views/scripts/');

        // Option hides the editing icons
        $readonly = false;
        if (end($this->_options) == self::OPTION_READONLY) {
            $readonly = true;
            unset($this->_options[key($this->_options)]);
        }

        $this->_view->readonly   = $readonly;
        $this->_view->websiteUrl = $this->_websiteUrl;

        // init helpers
        $this->_configHelper  = Zend_Controller_Action_HelperBroker::getStaticHelper('config');
        $this->_sessionHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('session');
    }

    protected function _load() {}

    /**
     * Throw an error message and add it to the error log file if debug mode is on
     *
     * @param string $message
     * @throws Exceptions_SeotoasterWidgetException
     */
    protected function _error($message) {
        $message = get_called_class() . ': ' . $message;
        if($this->_debugMode) {
            error_log($message);
        }
        throw new Exceptions_SeotoasterWidgetException($message);
    }

    protected function _initTranslator() {
        $websiteHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('website');
        $this->_translator = Zend_Registry::get('Zend_Translate');
        $langsPath = $websiteHelper->getPath() . 'plugins/webbuilder/' . $this->_languagesPath;
        if (is_dir($langsPath) && is_readable($langsPath)) {
            $locale = Zend_Registry::get('Zend_Locale');
            if (!file_exists($langsPath . $locale->getLanguage() . '.lng')) {
                if (Tools_System_Tools::debugMode()) {
                    error_log('Language file ' . $locale->getLanguage() . '.lng does not exist');
                }
                return false;
            }
            try {
                $this->_translator->addTranslation(array(
                    'content' => $langsPath . $locale->getLanguage() . '.lng',
                    'locale'  => $locale->getLanguage(),
                    'reload' => true
                ));
                Zend_Registry::set('Zend_Translate', $this->_translator);
            } catch (Exception $e) {
                if (Tools_System_Tools::debugMode()) {
                    error_log($e->getMessage());
                }
            }
        }
    }
}