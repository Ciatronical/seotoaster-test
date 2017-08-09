<?php
/**
 * Cart status email mail watchdog
 */
class Cartstatusemail_Tools_CartstatusemailMailWatchdog implements Interfaces_Observer {

    /**
     * Abandoned trigger.
     *
     */
    const TRIGGER_CART_STATUS_ABANDONED             = 'cartstatusemail_abandoned';

    /**
     * new quote trigger.
     *
     */
    const TRIGGER_CART_STATUS_NEWQUOTE              = 'cartstatusemail_newquote';

    /**
     * quote sent trigger.
     *
     */
    const TRIGGER_CART_STATUS_QUOTESENT             = 'cartstatusemail_quotesent';

    /**
     * completed purchase trigger.
     *
     */
    const TRIGGER_CART_STATUS_PAYMENT_RECEIVED      = 'cartstatusemail_paymentreceived';

    /**
     * items shipped trigger.
     *
     */
    const TRIGGER_CART_STATUS_ITEMS_SHIPPED         = 'cartstatusemail_itemsshipped';

    /**
     * items shipped trigger.
     *
     */
    const TRIGGER_CART_STATUS_ITEMS_DELIVERED       = 'cartstatusemail_itemsdelivered';

    /**
     * refunded purchase trigger.
     *
     */
    const TRIGGER_CART_STATUS_REFUNDED_PURCHASE     = 'cartstatusemail_refundedpurchase';

    /**
     * pending trigger
     *
     */

    const TRIGGER_CART_STATUS_ACTION_REQUIRE        = 'cartstatusemail_actionrequire';

    /**
     * processing trigger
     *
     */

    const TRIGGER_CART_STATUS_TECHNICAL_PROCESSING  = 'cartstatusemail_technicalprocessing';

    /**
     * canceled trigger
     *
     */
    const TRIGGER_CART_STATUS_LOST_OPPORTUNITY      = 'cartstatusemail_lostopportunity';


    const ROLE_CUSTOMER = 'customer';


    /**
     * Options passed from the toaster system mail watchdog
     *
     * @var array
     */
    protected $_options             = array();

    /**
     * Toaster mailer
     *
     * @var Tools_Mail_Mailer Toaster mailer instance
     */
    protected $_mailer              = null;

    /**
     * Toaster entity parser
     *
     * @var Tools_Content_EntityParser
     */
    protected $_entityParser        = null;

    /**
     * Toaster db config helper
     *
     * @var null|Helpers_Action_Config
     */
    protected $_configHelper        = null;

    /**
     * Toaster website helper
     *
     * @var null|Helpers_Action_Website
     */
    protected $_websiteHelper       = null;


    /**
     * Seotoaster translator instance
     *
     * @var Helpers_Action_Language
     */
    protected $_translator          = null;

    /**
     * Cartstatus email queue instance
     *
     * @var null|Cartstatusemail_Models_Models_CartstatusemailQueueModel
     */
    protected $_object               = null;

    protected $_storeConfig;

    protected $_cartStatusQueueMapper;

    protected $_template = null;

    /**
     * Roles restriction list for cart restoring
     *
     * @var array
     */
    public static $cartRecoveryProtectedRoles = array(
        Tools_Security_Acl::ROLE_SUPERADMIN,
        Tools_Security_Acl::ROLE_ADMIN,
        Shopping::ROLE_SALESPERSON,
        Tools_Security_Acl::ROLE_USER
    );


    /**
     * Init all necessary helpers and assign correct mail message
     *
     * @param array $options
     */
    public function __construct($options = array()) {
        // get global options
        $this->_options       = $options;

        // initialize helpers
        $this->_storeConfig             = Models_Mapper_ShoppingConfig::getInstance()->getConfigParams();
        $this->_entityParser            = new Tools_Content_EntityParser();
        $this->_configHelper            = Zend_Controller_Action_HelperBroker::getStaticHelper('config');
        $this->_websiteHelper           = Zend_Controller_Action_HelperBroker::getStaticHelper('website');
        $this->_translator              = Zend_Controller_Action_HelperBroker::getStaticHelper('language');
        $this->_cartStatusQueueMapper   = Cartstatusemail_Models_Mapper_CartstatusemailQueueMapper::getInstance();
        $this->_sessionHelper           = Zend_Controller_Action_HelperBroker::getStaticHelper('session');
        // initialize mailer and set correct message
        $this->_mailer        = Tools_Mail_Tools::initMailer();

    }

    /**
     * Mail watchdog entry point. Everything begins here
     *
     * @param Cartstatusemail_Models_Models_CartstatusemailQueueModel $object
     * @return bool
     * @throws Exceptions_SeotoasterException
     */

    public function notify($object) {
        if (!$object){
            return false;
        }

        $this->_object = $object;

        $template = $object->getEmailTemplate();
        if($template != '' && $template != null && $template != '0'){
           $this->_options['template'] = $template;
        }
        
        if($object->getEmailMessage() != '' && $object->getEmailMessage() != null){
            $this->_options['message'] = $object->getEmailMessage();
        }
        if($object->getEmailFrom() != '' && $object->getEmailFrom() != null){
            $this->_options['from'] = $object->getEmailFrom();
        }

        if (isset($this->_options['template']) && !empty($this->_options['template']) ){
            $this->_template = $this->_preparseEmailTemplate();
        } else {
            throw new Exceptions_SeotoasterException('Missing template for action email trigger');
        }

        $this->_mailer
            ->setMailFromLabel($this->_storeConfig['company'])
            ->setSubject($this->_options['subject']);

        if (!empty($this->_options['from'])){
            $this->_mailer->setMailFrom($this->_options['from']);
        } elseif (!empty($this->_storeConfig['email'])) {
            $this->_mailer->setMailFrom($this->_storeConfig['email']);
        } else {
            $this->_mailer->setMailFrom($this->_configHelper->getAdminEmail());
        }


        // generate sender method for the specific trigger and execute it if exists
        if (isset($this->_options['trigger'])) {
            $methodName = '_send'. str_replace(' ', '', ucwords(str_replace('_', ' ', $this->_options['trigger']))) . 'Mail';
            if (method_exists($this, $methodName)){
                $this->$methodName();
            }
        }
    }

    /**
     * Sending emails
     *
     */

    protected function _send(){
        if (!$this->_mailer->getMailFrom() || !$this->_mailer->getMailTo()) {
            throw new Exceptions_SeotoasterException('Missing required "from" and "to" fields');
        }
        $this->_mailer->setBody($this->_entityParser->parse($this->_template));

        return ($this->_mailer->send() !== false);
    }

    /**
     * Sender method for the 'cartsatusemail_abandoned' trigger
     *
     * @return bool
     */
    protected function _sendCartstatusemailAbandonedMail() {
        $userFullName = $this->_object->getUserFullName();
        $userEmail    = $this->_object->getUserEmail();
        $cartId       = $this->_object->getCartId();
        $basketData   = $this->_prepareBasket($cartId);
        $recoveryLink = $this->_prepareRecoveryLink($this->_options['userId'], $this->_object->getCartId(), $this->_options['cartStatus']);
        

        $this->_entityParser->addToDictionary(array('cart:recovery'=>$recoveryLink));
        $this->_entityParser->addToDictionary(array('cart:basket'=>$basketData));
        $this->_entityParser->addToDictionary(array('user:fullname' => $userFullName));

        $unsubscribeLink = $this->_prepareUnsubscribeLink($this->_options['unsubscribeCode']);

        $this->_entityParser->addToDictionary(array('unsubscribe:link' =>$unsubscribeLink));

        switch ($this->_options['recipient']) {
            case self::ROLE_CUSTOMER:
                $this->_mailer->setMailToLabel($userFullName)->setMailTo($userEmail);
                break;
            default:
                error_log('Unsupported recipient '.$this->_options['recipient'].' given');
                $this->_cartStatusQueueMapper->updateQueueStatus($this->_object->getId(), '1', true);
                return false;
                break;
        }

        $this->_mailer->setSubject($this->_options['subject']);
        $this->_cartStatusQueueMapper->updateQueueStatus($this->_object->getId(), '1', true);
        return $this->_send();
    }

    /**
     * Sender method for the 'cartstatusemail_newquote' trigger
     *
     * @return bool
     */
    protected function _sendCartstatusemailQuotesentMail() {
        $userFullName = $this->_object->getUserFullName();
        $userEmail    = $this->_object->getUserEmail();
        $cartId       = $this->_object->getCartId();
        $basketData   = $this->_prepareBasket($cartId);
        $recoveryLink = $this->_prepareRecoveryLink($this->_options['userId'], $this->_object->getCartId(), $this->_options['cartStatus']);
        $quoteMapper = Quote_Models_Mapper_QuoteMapper::getInstance();
        $quote = $quoteMapper->findByCartId($cartId);
        if($quote instanceof Quote_Models_Model_Quote){
            $quoteId = $quote->getId();
            $this->_entityParser->objectToDictionary($quote);
        } else {
            $quoteId = 0;
        }

        $this->_entityParser->addToDictionary(array('cart:recovery'=>$recoveryLink));
        $this->_entityParser->addToDictionary(array('cart:basket'=>$basketData));
        $this->_entityParser->addToDictionary(array('user:fullname' => $userFullName));
        $this->_entityParser->addToDictionary(array('quote:id' => $quoteId));
        $unsubscribeLink = $this->_prepareUnsubscribeLink($this->_options['unsubscribeCode']);

        $this->_entityParser->addToDictionary(array('unsubscribe:link' =>$unsubscribeLink));
        

        switch ($this->_options['recipient']) {
            case self::ROLE_CUSTOMER:
                $this->_mailer->setMailToLabel($userFullName)->setMailTo($userEmail);
                break;
            default:
                error_log('Unsupported recipient '.$this->_options['recipient'].' given');
                $this->_cartStatusQueueMapper->updateQueueStatus($this->_object->getId(), '1', true);
                return false;
                break;
        }
        $this->_cartStatusQueueMapper->updateQueueStatus($this->_object->getId(), '1', true);
        return $this->_send();
    }

    /**
     * Sender method for the 'cartstatusemail_newquote' trigger
     *
     * @return bool
     */
    protected function _sendCartstatusemailNewquoteMail() {
        $userFullName = $this->_object->getUserFullName();
        $userEmail    = $this->_object->getUserEmail();
        $cartId       = $this->_object->getCartId();
        $basketData   = $this->_prepareBasket($cartId);
        $recoveryLink = $this->_prepareRecoveryLink($this->_options['userId'], $this->_object->getCartId(), $this->_options['cartStatus']);
        $this->_entityParser->addToDictionary(array('cart:recovery'=>$recoveryLink));
        $this->_entityParser->addToDictionary(array('cart:basket'=>$basketData));
        $quoteMapper = Quote_Models_Mapper_QuoteMapper::getInstance();
        $quote = $quoteMapper->findByCartId($cartId);
        if ($quote instanceof Quote_Models_Model_Quote) {
            $quoteId = $quote->getId();
            $this->_entityParser->objectToDictionary($quote);
        } else {
            $quoteId = 0;
        }

        $this->_entityParser->addToDictionary(array('quote:id' => $quoteId));
        $this->_entityParser->addToDictionary(array('user:fullname' => $userFullName));
        $unsubscribeLink = $this->_prepareUnsubscribeLink($this->_options['unsubscribeCode']);

        $this->_entityParser->addToDictionary(array('unsubscribe:link' =>$unsubscribeLink));
        

        switch ($this->_options['recipient']) {
            case self::ROLE_CUSTOMER:
                $this->_mailer->setMailToLabel($userFullName)->setMailTo($userEmail);
                break;
            default:
                error_log('Unsupported recipient '.$this->_options['recipient'].' given');
                $this->_cartStatusQueueMapper->updateQueueStatus($this->_object->getId(), '1', true);
                return false;
                break;
        }
        $this->_cartStatusQueueMapper->updateQueueStatus($this->_object->getId(), '1', true);
        return $this->_send();
    }

    /**
     * Sender method for the 'cartstatusemail_paymentreceived' trigger
     *
     * @return bool
     */
    protected function _sendCartstatusemailPaymentreceivedMail() {
        $userFullName = $this->_object->getUserFullName();
        $userEmail    = $this->_object->getUserEmail();
        $cartId       = $this->_object->getCartId();
        $basketData   = $this->_prepareBasket($cartId);
        $recoveryLink = $this->_prepareRecoveryLink($this->_options['userId'], $this->_object->getCartId(), $this->_options['cartStatus']);
        $this->_entityParser->addToDictionary(array('cart:recovery'=>$recoveryLink));
        $this->_entityParser->addToDictionary(array('cart:basket'=>$basketData));
        $unsubscribeLink = $this->_prepareUnsubscribeLink($this->_options['unsubscribeCode']);

        $this->_entityParser->addToDictionary(array('unsubscribe:link' =>$unsubscribeLink));
        

        switch ($this->_options['recipient']) {
            case self::ROLE_CUSTOMER:
                $this->_mailer->setMailToLabel($userFullName)->setMailTo($userEmail);
                break;
            default:
                error_log('Unsupported recipient '.$this->_options['recipient'].' given');
                $this->_cartStatusQueueMapper->updateQueueStatus($this->_object->getId(), '1', true);
                return false;
                break;
        }
        $this->_cartStatusQueueMapper->updateQueueStatus($this->_object->getId(), '1', true);
        return $this->_send();
    }

    /**
     * Sender method for the 'cartstatusemail_itemsshipped' trigger
     *
     * @return bool
     */
    protected function _sendCartstatusemailItemsshippedMail() {
        $userFullName = $this->_object->getUserFullName();
        $userEmail    = $this->_object->getUserEmail();
        $cartId       = $this->_object->getCartId();
        $basketData   = $this->_prepareBasket($cartId);
        $recoveryLink = $this->_prepareRecoveryLink($this->_options['userId'], $this->_object->getCartId(), $this->_options['cartStatus']);
        $this->_entityParser->addToDictionary(array('cart:recovery'=>$recoveryLink));
        $this->_entityParser->addToDictionary(array('cart:basket'=>$basketData));
        $unsubscribeLink = $this->_prepareUnsubscribeLink($this->_options['unsubscribeCode']);

        $this->_entityParser->addToDictionary(array('unsubscribe:link' =>$unsubscribeLink));

        switch ($this->_options['recipient']) {
            case self::ROLE_CUSTOMER:
                $this->_mailer->setMailToLabel($userFullName)->setMailTo($userEmail);
                break;
            default:
                error_log('Unsupported recipient '.$this->_options['recipient'].' given');
                $this->_cartStatusQueueMapper->updateQueueStatus($this->_object->getId(), '1', true);
                return false;
                break;
        }
        $this->_cartStatusQueueMapper->updateQueueStatus($this->_object->getId(), '1', true);
        return $this->_send();
    }

    /**
     * Sender method for the 'cartstatusemail_itemsdelivered' trigger
     *
     * @return bool
     */
    protected function _sendCartstatusemailItemsdeliveredMail() {
        $userFullName = $this->_object->getUserFullName();
        $userEmail    = $this->_object->getUserEmail();
        $cartId       = $this->_object->getCartId();
        $basketData   = $this->_prepareBasket($cartId);
        $recoveryLink = $this->_prepareRecoveryLink($this->_options['userId'], $this->_object->getCartId(), $this->_options['cartStatus']);
        $this->_entityParser->addToDictionary(array('cart:recovery'=>$recoveryLink));
        $this->_entityParser->addToDictionary(array('cart:basket'=>$basketData));
        $unsubscribeLink = $this->_prepareUnsubscribeLink($this->_options['unsubscribeCode']);

        $this->_entityParser->addToDictionary(array('unsubscribe:link' =>$unsubscribeLink));
        
        switch ($this->_options['recipient']) {
            case self::ROLE_CUSTOMER:
                $this->_mailer->setMailToLabel($userFullName)->setMailTo($userEmail);
                break;
            default:
                error_log('Unsupported recipient '.$this->_options['recipient'].' given');
                $this->_cartStatusQueueMapper->updateQueueStatus($this->_object->getId(), '1', true);
                return false;
                break;
        }
        $this->_cartStatusQueueMapper->updateQueueStatus($this->_object->getId(), '1', true);
        return $this->_send();
    }

    /**
     * Sender method for the 'cartstatusemail_refundedpurchase' trigger
     *
     * @return bool
     */
    protected function _sendCartstatusemailRefundedpurchaseMail() {
        $userFullName = $this->_object->getUserFullName();
        $userEmail    = $this->_object->getUserEmail();
        $cartId       = $this->_object->getCartId();
        $basketData   = $this->_prepareBasket($cartId);
        $recoveryLink = $this->_prepareRecoveryLink($this->_options['userId'], $this->_object->getCartId(), $this->_options['cartStatus']);
        $this->_entityParser->addToDictionary(array('cart:recovery'=>$recoveryLink));
        $this->_entityParser->addToDictionary(array('cart:basket'=>$basketData));
        $unsubscribeLink = $this->_prepareUnsubscribeLink($this->_options['unsubscribeCode']);

        $this->_entityParser->addToDictionary(array('unsubscribe:link' =>$unsubscribeLink));
        
        switch ($this->_options['recipient']) {
            case self::ROLE_CUSTOMER:
                $this->_mailer->setMailToLabel($userFullName)->setMailTo($userEmail);
                break;
            default:
                error_log('Unsupported recipient '.$this->_options['recipient'].' given');
                $this->_cartStatusQueueMapper->updateQueueStatus($this->_object->getId(), '1', true);
                return false;
                break;
        }
        $this->_cartStatusQueueMapper->updateQueueStatus($this->_object->getId(), '1', true);
        return $this->_send();
    }

    /**
     * Sender method for the 'cartstatusemail_lostopportunity' trigger
     *
     * @return bool
     */
    protected function _sendCartstatusemailLostopportunityMail() {
        $userFullName = $this->_object->getUserFullName();
        $userEmail    = $this->_object->getUserEmail();
        $cartId       = $this->_object->getCartId();
        $basketData   = $this->_prepareBasket($cartId);
        $recoveryLink = $this->_prepareRecoveryLink($this->_options['userId'], $this->_object->getCartId(), $this->_options['cartStatus']);
        $this->_entityParser->addToDictionary(array('cart:recovery'=>$recoveryLink));
        $this->_entityParser->addToDictionary(array('cart:basket'=>$basketData));
        $quoteMapper = Quote_Models_Mapper_QuoteMapper::getInstance();
        $quote = $quoteMapper->findByCartId($cartId);
        if ($quote instanceof Quote_Models_Model_Quote) {
            $quoteId = $quote->getId();
            $this->_entityParser->objectToDictionary($quote);
        } else {
            $quoteId = 0;
        }

        $this->_entityParser->addToDictionary(array('quote:id' => $quoteId));
        $this->_entityParser->addToDictionary(array('user:fullname' => $userFullName));
        $unsubscribeLink = $this->_prepareUnsubscribeLink($this->_options['unsubscribeCode']);

        $this->_entityParser->addToDictionary(array('unsubscribe:link' =>$unsubscribeLink));

        switch ($this->_options['recipient']) {
            case self::ROLE_CUSTOMER:
                $this->_mailer->setMailToLabel($userFullName)->setMailTo($userEmail);
                break;
            default:
                error_log('Unsupported recipient '.$this->_options['recipient'].' given');
                $this->_cartStatusQueueMapper->updateQueueStatus($this->_object->getId(), '1', true);
                return false;
                break;
        }
        $this->_cartStatusQueueMapper->updateQueueStatus($this->_object->getId(), '1', true);
        return $this->_send();
    }

    /**
     * Sender method for the 'cartstatusemail_actionrequire' trigger
     *
     * @return bool
     */
    protected function _sendCartstatusemailActionrequireMail() {
        $userFullName = $this->_object->getUserFullName();
        $userEmail    = $this->_object->getUserEmail();
        $cartId       = $this->_object->getCartId();
        $basketData   = $this->_prepareBasket($cartId);
        $recoveryLink = $this->_prepareRecoveryLink($this->_options['userId'], $this->_object->getCartId(), $this->_options['cartStatus']);
        $this->_entityParser->addToDictionary(array('cart:recovery'=>$recoveryLink));
        $this->_entityParser->addToDictionary(array('cart:basket'=>$basketData));
        $unsubscribeLink = $this->_prepareUnsubscribeLink($this->_options['unsubscribeCode']);

        $this->_entityParser->addToDictionary(array('unsubscribe:link' =>$unsubscribeLink));

        switch ($this->_options['recipient']) {
            case self::ROLE_CUSTOMER:
                $this->_mailer->setMailToLabel($userFullName)->setMailTo($userEmail);
                break;
            default:
                error_log('Unsupported recipient '.$this->_options['recipient'].' given');
                $this->_cartStatusQueueMapper->updateQueueStatus($this->_object->getId(), '1', true);
                return false;
                break;
        }
        $this->_cartStatusQueueMapper->updateQueueStatus($this->_object->getId(), '1', true);
        return $this->_send();
    }

    /**
     * Sender method for the 'cartstatusemail_technicalprocessing' trigger
     *
     * @return bool
     */
    protected function _sendCartstatusemailTechnicalprocessingMail() {
        $userFullName = $this->_object->getUserFullName();
        $userEmail    = $this->_object->getUserEmail();
        $cartId       = $this->_object->getCartId();
        $basketData   = $this->_prepareBasket($cartId);
        $recoveryLink = $this->_prepareRecoveryLink($this->_options['userId'], $this->_object->getCartId(), $this->_options['cartStatus']);
        $this->_entityParser->addToDictionary(array('cart:recovery'=>$recoveryLink));
        $this->_entityParser->addToDictionary(array('cart:basket'=>$basketData));
        $unsubscribeLink = $this->_prepareUnsubscribeLink($this->_options['unsubscribeCode']);

        $this->_entityParser->addToDictionary(array('unsubscribe:link' =>$unsubscribeLink));

        switch ($this->_options['recipient']) {
            case self::ROLE_CUSTOMER:
                $this->_mailer->setMailToLabel($userFullName)->setMailTo($userEmail);
                break;
            default:
                error_log('Unsupported recipient '.$this->_options['recipient'].' given');
                $this->_cartStatusQueueMapper->updateQueueStatus($this->_object->getId(), '1', true);
                return false;
                break;
        }
        $this->_cartStatusQueueMapper->updateQueueStatus($this->_object->getId(), '1', true);
        return $this->_send();
    }

    /**
     * Prepare mail body using mail template form the trigger
     *
     * Mail template will be parsed for the:
     * 1. {emailmessage} instance
     * 2. standart toaster widgets
     * @return null
     */
    private function _preparseEmailTemplate(){
        $tmplName = $this->_options['template'];
        $tmplMessage = $this->_options['message'];
        $mailTemplate = Application_Model_Mappers_TemplateMapper::getInstance()->find($tmplName);

        if (!empty($mailTemplate)){
            $this->_entityParser->setDictionary(array(
                'emailmessage' => !empty($tmplMessage) ? $tmplMessage : ''
            ));
            //pushing message template to email template and cleaning dictionary
            $mailTemplate = $this->_entityParser->parse($mailTemplate->getContent());
            $this->_entityParser->setDictionary(array());

            $mailTemplate = $this->_entityParser->parse($mailTemplate);

            $themeData = Zend_Registry::get('theme');
            $extConfig = Zend_Registry::get('extConfig');
            $parserOptions = array(
                'websiteUrl'   => $this->_websiteHelper->getUrl(),
                'websitePath'  => $this->_websiteHelper->getPath(),
                'currentTheme' => $extConfig['currentTheme'],
                'themePath'    => $themeData['path'],
            );
            $parser = new Tools_Content_Parser($mailTemplate, Tools_Misc::getCheckoutPage()->toArray(), $parserOptions);

            return $parser->parseSimple();
        }

        return false;
    }

    /**
     * Prepare recovery link for cart
     *
     * @param string $userId system user id
     * @param int $cartId cart id
     * @param string $cartStatus cart status (completed, new, etc...)
     * @return string
     */
    protected function _prepareRecoveryLink($userId, $cartId, $cartStatus){
        $userModel = Application_Model_Mappers_UserMapper::getInstance()->find($userId);
        if ($userModel instanceof Application_Model_Models_User) {
            $userRole = $userModel->getRoleId();
            if (!in_array($userRole, self::$cartRecoveryProtectedRoles) && !in_array($this->_options['recipient'], self::$cartRecoveryProtectedRoles)) {
                $cartRestoredMapper = Cartstatusemail_Models_Mapper_CartstatusemailRestoredCartMapper::getInstance();
                $restoredDate = date(Tools_System_Tools::DATE_MYSQL, strtotime('now'));
                $code = sha1(uniqid(strtotime('now')));
                $restoredCartData = $cartRestoredMapper->findByCartId($cartId);
                if (!empty($restoredCartData)) {
                    $cartRestoredModel = $restoredCartData[0];
                } else {
                    $cartRestoredModel = new Cartstatusemail_Models_Models_CartstatusemailRestoredCartModel();
                    $cartRestoredModel->setCartId($cartId);
                    $cartRestoredModel->setUserId($userId);
                }
                $cartRestoredModel->setCode($code);
                $cartRestoredModel->setCartStatus($cartStatus);
                $cartRestoredModel->setSentAt($restoredDate);
                $cartRestoredMapper->save($cartRestoredModel);
                return $this->_websiteHelper->getUrl() . 'plugin/cartstatusemail/run/cartRecovery/code/' . $code . '/userId/'.$userId.'/';
            }
        }
        return '';

    }

    /**
     * Prepare link for unsubscribe
     *
     * @param string $code unsubscribe code
     * @return string
     */
    protected function _prepareUnsubscribeLink($code)
    {
        if (empty($code)) {
            return '';
        }

        return $this->_websiteHelper->getUrl() . 'plugin/cartstatusemail/run/unsubscribe/code/' . $code . '/';
    }

    /**
     * Prepare post purchase view
     *
     * @param int $cartId cart id
     * @return string
     */
    protected function _prepareBasket($cartId){
            $cartSession = Models_Mapper_CartSessionMapper::getInstance()->find($cartId);
            $view = new Zend_View();
            $websiteUrl = $this->_websiteHelper->getUrl();
            $view->websiteUrl = $websiteUrl;
            $view->setHelperPath(APPLICATION_PATH . '/views/helpers/');
            $view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
            $view->setScriptPath($this->_websiteHelper->getPath(). 'plugins/shopping/system/app/Widgets/Store/views');
            $view->mailReport = 1;
            $basket = '';
            if ($cartSession instanceof Models_Model_CartSession){
                $cartContent = $cartSession->getCartContent();
                $productMapper = Models_Mapper_ProductMapper::getInstance();
                $shoppingConfig = Models_Mapper_ShoppingConfig::getInstance()->getConfigParams();
                $view->shoppingConfig = $shoppingConfig;
                $shippingPrice = $cartSession->getShippingPrice();
                if($shippingPrice == null){
                    $cartSession->setShippingPrice(0);
                }
                if($cartContent != null){
                    foreach ($cartContent as $key=>$product){
                        $productObject = $productMapper->find($product['product_id']);
                        if($productObject !== null){
                            $cartContent[$key]['mpn']        = $productObject->getMpn();
                            $cartContent[$key]['photo']      = $productObject->getPhoto();
                            $cartContent[$key]['productUrl'] = $productObject->getPage()->getUrl();
                            $cartContent[$key]['taxRate']    = Tools_Tax_Tax::calculateProductTax($productObject, null, true);
                        }
                    }
                    $view->showPriceIncTax = $shoppingConfig['showPriceIncTax'];
                    $view->weightSign = $shoppingConfig['weightUnit'];
                    $view->cartContent = $cartContent;
                    $view->cart = $cartSession;
                    $basket = $view->render('post_purchase_report.phtml');
                }

            }
            return $basket;


    }


}
