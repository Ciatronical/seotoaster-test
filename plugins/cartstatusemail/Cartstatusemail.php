<?php


class Cartstatusemail extends Tools_Plugins_Abstract {

    const DISPLAY_NAME = 'Email remarketing';
    const PRODUCT_RULE_ALL = 'all';
    const PRODUCT_RULE_ANY = 'any';
    const GATEWAY_QUOTE    = 'Quote';
    const CARTSTATUS_SECURE_TOKEN = 'CartStatus';

    /**
     * System layout
     *
     * @var null
     */
    protected $_layout = null;

    /**
     * Subscribed status for subscription
     */
    const SUBSCRIBED_STATUS_SUBSCRIBED = 'subscribed';

    /**
     * Unsubscribed status for subscription
     */
    const SUBSCRIBED_STATUS_UNSUBSCRIBED = 'unsubscribed';

    /**
     * queue status not sent
     */
    const QUEUE_STATUS_NOT_SENT = '0';

    /**
     * queue status sent
     */
    const QUEUE_STATUS_SENT = '1';

    /**
     * queue status canceled
     */
    const QUEUE_STATUS_CANCELED = '2';

	protected $_dependsOn = array(
		'shopping'
	);

    /**
     * Accepted cart statuses for cart restoring
     *
     * @var array
     */
    public static $acceptedCartStatuses = array(
        Models_Model_CartSession::CART_STATUS_NEW,
        Models_Model_CartSession::CART_STATUS_PROCESSING
    );

	protected function _init() {
		$missedPlugins = array_diff($this->_dependsOn, Tools_Plugins_Tools::getEnabledPlugins(true));

		if (!empty($missedPlugins)) {
			throw new Exceptions_SeotoasterPluginException('Required plugins should be enabled: <b>' . implode(',', $missedPlugins) . '</b>');
		}
        $this->_websiteConfig = Zend_Registry::get('website');

        $this->_layout = new Zend_Layout();
        $this->_layout->setLayoutPath(Zend_Layout::getMvcInstance()->getLayoutPath());

        if ($viewScriptPath = Zend_Layout::getMvcInstance()->getView()->getScriptPaths()) {
            $this->_view->setScriptPath($viewScriptPath);
        }
        $this->_view->addScriptPath(__DIR__ . '/system/views/');
	}

    public function beforeController() {
        $this->_addCartStatusEmail();
    }

	public function merchandisingAction(){
		if (!Tools_Security_Acl::isAllowed(Shopping::RESOURCE_STORE_MANAGEMENT)){
			throw new Exceptions_SeotoasterPluginException('Forbidden');
		}
        $this->_view->websiteConfig = $this->_websiteConfig;
        $this->_view->mailTemplates = Application_Model_Mappers_TemplateMapper::getInstance()->findByType(Application_Model_Models_Template::TYPE_MAIL);

		echo $this->_view->render('merchandisingTab.phtml');
	}

    protected function _addCartStatusEmail(){
        $this->_injectContent($this->_view->render('cartStatusEmail.phtml'));
    }

    public function sendCartStatusEmailsAction(){
        if($this->_request->isPost()){
            $cacheHelper            = Zend_Controller_Action_HelperBroker::getExistingHelper('cache');
            $cartStatusQueueMapper  = Cartstatusemail_Models_Mapper_CartstatusemailQueueMapper::getInstance();
            if (null === ($cartStatusConfigData = $cacheHelper->load('cartstatus_configdata', 'cart_status'))){
                $settingsCartStatus     = Cartstatusemail_Models_Mapper_CartstatusemailSettingsMapper::getInstance()->fetchAll();
                $cartStatusDbtable      = new Cartstatusemail_Models_Dbtables_CartstatusemailSettingsDbtable();
                $cartStatusQueueModel   = new Cartstatusemail_Models_Models_CartstatusemailQueueModel();
                if(!empty($settingsCartStatus)){
                    foreach($settingsCartStatus as $statusCart){
                        $cartStatus     = $statusCart->getCartStatus();
                        $newCartStatus  = $statusCart->getCartStatus();
                        $periodHours    = $statusCart->getPeriodHours();
                        $productsIds    = $statusCart->getProductsIds();
                        $emailTemplate  = $statusCart->getEmailTemplate();
                        $emailFrom      = $statusCart->getEmailFrom();
                        $emailMessage   = $statusCart->getEmailMessage();
                        $productRule    = $statusCart->getProductsRule();
                        $productsIds    = explode(',', $productsIds);
                        $cartStatusId   = $statusCart->getId();
                        $where = '';
                        if(isset($cartStatus) && ($cartStatus == Models_Model_CartSession::CART_STATUS_PENDING || $cartStatus == Models_Model_CartSession::CART_STATUS_PROCESSING || $cartStatus == Models_Model_CartSession::CART_STATUS_CANCELED)){
                            $where = $cartStatusDbtable->getAdapter()->quoteInto('gateway <> ?', self::GATEWAY_QUOTE);
                        }
                        if(isset($cartStatus) && ($cartStatus == Tools_Misc::CS_ALIAS_PENDING)){
                            $cartStatus  = Models_Model_CartSession::CART_STATUS_PENDING;
                            $where = $cartStatusDbtable->getAdapter()->quoteInto('gateway = ?', self::GATEWAY_QUOTE);
                        }
                        if(isset($cartStatus) && $cartStatus == Tools_Misc::CS_ALIAS_PROCESSING){
                            $cartStatus  = Models_Model_CartSession::CART_STATUS_PROCESSING;
                            $where = $cartStatusDbtable->getAdapter()->quoteInto('gateway = ?', self::GATEWAY_QUOTE);
                        }
                        if(isset($cartStatus) && $cartStatus == Tools_Misc::CS_ALIAS_LOST_OPPORTUNITY){
                            $cartStatus  = Models_Model_CartSession::CART_STATUS_CANCELED;
                            $where = $cartStatusDbtable->getAdapter()->quoteInto('gateway = ?', self::GATEWAY_QUOTE);
                        }
                        if(strlen($where)>1){
                            $where .= ' AND '. $cartStatusDbtable->getAdapter()->quoteInto('shcs.status = ?', $cartStatus);
                        }else{
                            $where = $cartStatusDbtable->getAdapter()->quoteInto('shcs.status = ?', $cartStatus);
                        }
                        $limitPeriod = $periodHours+24;
                        $where .= " AND DATE_SUB(NOW(), INTERVAL $periodHours HOUR) > `updated_at`";
                        $where .= " AND DATE_SUB(NOW(), INTERVAL $limitPeriod HOUR) < `updated_at`";

                        if($productRule == self::PRODUCT_RULE_ALL || $productRule == self::PRODUCT_RULE_ANY){
                            $idsQuantity = count($productsIds);
                            if($idsQuantity > 1){
                                foreach($productsIds as $key => $ids){
                                    if($key == 0){
                                        $where .= ' AND ('. $cartStatusDbtable->getAdapter()->quoteInto('product_id = ?', $ids);
                                    }else{
                                        $where .= ' OR '. $cartStatusDbtable->getAdapter()->quoteInto('product_id = ?', $ids);
                                    }
                                }
                                $where .= ')';
                            }else{
                                $where .= ' AND '. $cartStatusDbtable->getAdapter()->quoteInto('product_id = ?', $productsIds[0]);
                            }
                        }

                        $where .= ' AND ('.$cartStatusDbtable->getAdapter()->quoteInto('pcs.status = ?', self::SUBSCRIBED_STATUS_SUBSCRIBED);
                        $where .= ' OR '.new Zend_Db_Expr('pcs.status IS NULL').')';

                        if($productRule == self::PRODUCT_RULE_ALL){
                            $select = $cartStatusDbtable->select()
                                ->setIntegrityCheck(false)
                                ->from(array('shcs' => 'shopping_cart_session'), array('shcs.id', 'cartCount' => 'count(shcs.id)', 'shcs.user_id'))
                                ->joinleft(array('shcsc' => 'shopping_cart_session_content'), 'shcs.id = shcsc.cart_id', array('cartId' => 'shcs.id'))
                                ->joinleft(array('u' => 'user'), 'shcs.user_id = u.id', array('userEmail' => 'u.email', 'userFullName' => 'u.full_name'))
                                ->joinLeft(array('pcs' => 'plugin_cartstatusemail_subscribe'), 'pcs.user_id=u.id', array())
                                ->where($where)->group('shcs.id')->having('cartCount > '.($idsQuantity-1));
                        }else{
                            $select = $cartStatusDbtable->select()
                                ->setIntegrityCheck(false)
                                ->from(array('shcs' => 'shopping_cart_session'), array('shcs.id', 'cartCount' => 'count(shcs.id)', 'shcs.user_id'))
                                ->joinleft(array('shcsc' => 'shopping_cart_session_content'), 'shcs.id = shcsc.cart_id', array('cartId' => 'shcs.id'))
                                ->joinleft(array('u' => 'user'), 'shcs.user_id = u.id', array('userEmail' => 'u.email', 'userFullName' => 'u.full_name'))
                                ->joinLeft(array('pcs' => 'plugin_cartstatusemail_subscribe'), 'pcs.user_id=u.id', array())
                                ->where($where)->group('shcs.id');
                        }
                        $resultCarts = $cartStatusDbtable->getAdapter()->fetchAssoc($select);
                        if(!empty($resultCarts)){
                            foreach($resultCarts as $cart){
                                $cartId     = $cart['cartId'];
                                $userEmail  = $cart['userEmail'];
                                $userId = $cart['user_id'];
                                if($userEmail != null){
                                    $emailQueueExist = $cartStatusQueueMapper->selectFromQueue($cartStatusId, $cartId, $userEmail);
                                    if(empty($emailQueueExist)){
                                        $cartStatusSubscribeMapper = Cartstatusemail_Models_Mapper_CartstatusemailSubscribeMapper::getInstance();
                                        $subscribeExists = $cartStatusSubscribeMapper->findByUserId($userId);
                                        $addToQueue = false;
                                        if (empty($subscribeExists)) {
                                            $cartStatusSubscribeModel = new Cartstatusemail_Models_Models_CartstatusemailSubscribeModel();
                                            $cartStatusSubscribeModel->setUserId($userId);
                                            $cartStatusSubscribeModel->setCode(sha1(uniqid(strtotime('now'))));
                                            $cartStatusSubscribeModel->setStatus(self::SUBSCRIBED_STATUS_SUBSCRIBED);
                                            $cartStatusSubscribeMapper->save($cartStatusSubscribeModel);
                                            $addToQueue = true;
                                        } elseif ($subscribeExists['status'] === self::SUBSCRIBED_STATUS_SUBSCRIBED) {
                                            $addToQueue = true;
                                        }

                                        if ($addToQueue) {
                                            $cartStatusQueueModel->setCartId($cartId);
                                            $cartStatusQueueModel->setStatus(0);
                                            $cartStatusQueueModel->setCartStatusId($cartStatusId);
                                            $cartStatusQueueModel->setUserEmail($userEmail);
                                            $cartStatusQueueModel->setUserFullName($cart['userFullName']);
                                            $cartStatusQueueModel->setCartStatus($newCartStatus);
                                            $cartStatusQueueModel->setEmailMessage($emailMessage);
                                            $cartStatusQueueModel->setEmailFrom($emailFrom);
                                            $cartStatusQueueModel->setEmailTemplate($emailTemplate);
                                            $cartStatusQueueMapper->save($cartStatusQueueModel);
                                        }
                                    }

                                }
                            }

                        }
                    }
                }
                $cartStatusConfigData = 1;
                $cacheHelper->save('cartstatus_configdata',  $cartStatusConfigData, 'cart_status', Helpers_Action_Cache::CACHE_SHORT);
                $this->_responseHelper->fail('');
            } elseif (!Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_CONTENT)){
                $emailsToSend = $cartStatusQueueMapper->getAllQueue();
                $selectActiveActionEmailTriggers = $cartStatusQueueMapper->getDbtable()->select()->setIntegrityCheck(false)->from('email_triggers_actions', array('trigger', 'id'));
                $activeActionEmailTriggers = $cartStatusQueueMapper->getDbTable()->getAdapter()->fetchAssoc($selectActiveActionEmailTriggers);
                if(!empty($emailsToSend)){
                    foreach($emailsToSend as $email){
                        $subscriptionExists = Cartstatusemail_Models_Mapper_CartstatusemailSubscribeMapper::getInstance()->findByEmail($email->getUserEmail());
                        $unsubscribeCode = '';
                        if (!empty($subscriptionExists)) {
                            $unsubscribeCode = $subscriptionExists['code'];
                        }
                        $orderId = $email->getCartId();
                        if (!Zend_Registry::isRegistered('postPurchaseCart')) {
                             $cart = Models_Mapper_CartSessionMapper::getInstance()->find(
                                $orderId
                            );
                            if ($cart instanceof Models_Model_CartSession) {
                                $productMapper = Models_Mapper_ProductMapper::getInstance();
                                $cartContent = $cart->getCartContent();
                                foreach ($cartContent as $key => $product) {
                                    $productObject = $productMapper->find($product['product_id']);
                                    if ($productObject instanceof Models_Model_Product) {
                                        $cartContent[$key]['mpn'] = $productObject->getMpn();
                                        $cartContent[$key]['photo'] = $productObject->getPhoto();
                                        $cartContent[$key]['productUrl'] = $productObject->getPage()->getUrl();
                                        $cartContent[$key]['taxRate'] = Tools_Tax_Tax::calculateProductTax($productObject, null, true);
                                    }
                                }
                                $cart->setCartContent($cartContent);
                                $billingAddressId = $cart->getBillingAddressId();
                                if (null !== $billingAddressId) {
                                    $cart->setBillingAddressId(Tools_ShoppingCart::getAddressById($billingAddressId));
                                }
                                $shippingAddressId = $cart->getShippingAddressId();
                                if (null !== $shippingAddressId) {
                                    $cart->setShippingAddressId(Tools_ShoppingCart::getAddressById($shippingAddressId));
                                }

                            }
                            Zend_Registry::set('postPurchaseCart', $cart);
                            if ($cart instanceof Models_Model_CartSession && !Zend_Registry::isRegistered('postPurchasePickup') && $cart->getShippingService() === 'pickup') {
                                $pickupLocationConfigMapper = Store_Mapper_PickupLocationConfigMapper::getInstance();
                                $pickupLocationData = $pickupLocationConfigMapper->getCartPickupLocationByCartId($cart->getId());
                                if (empty($pickupLocationData)) {
                                    $shoppingConfig = Models_Mapper_ShoppingConfig::getInstance()->getConfigParams();
                                    $pickupLocationData = array(
                                        'name' => $shoppingConfig['company'],
                                        'address1' => $shoppingConfig['address1'],
                                        'address2' => $shoppingConfig['address2'],
                                        'country' => $shoppingConfig['country'],
                                        'city' => $shoppingConfig['city'],
                                        'state' => $shoppingConfig['state'],
                                        'zip' => $shoppingConfig['zip'],
                                        'phone' => $shoppingConfig['phone']
                                    );
                                }
                                $pickupLocationData['map_link'] = 'https://maps.google.com/?q=' . $pickupLocationData['address1'] . '+' . $pickupLocationData['city'] . '+' . $pickupLocationData['state'];
                                $pickupLocationData['map_src'] = Tools_Geo::generateStaticGmaps($pickupLocationData, 640,
                                    300);
                                Zend_Registry::set('postPurchasePickup', $pickupLocationData);
                            }
                        }

                        $cartStatus = $email->getCartStatus();
                        $orderModel = Models_Mapper_CartSessionMapper::getInstance()->find($orderId);
                        $userId = 0;
                        if ($orderModel instanceof Models_Model_CartSession) {
                            $userId = $orderModel->getUserId();
                        }

                        $emailTriggerName = $this->_prepareEmailTriggerName($email->getCartStatus());
                        if (!array_key_exists($emailTriggerName, $activeActionEmailTriggers)) {
                            $this->_responseHelper->fail('missing action email trigger');
                        }
                        $email->registerObserver(new Tools_Mail_Watchdog(array(
                            'trigger' => $emailTriggerName,
                            'unsubscribeCode' => $unsubscribeCode,
                            'userId' => $userId,
                            'cartStatus' => $cartStatus
                        )));
                        $email->notifyObservers();
                    }
                    $this->_responseHelper->success('');
                }else{
                    $this->_responseHelper->fail('');
                }
            } else {
                $this->_responseHelper->fail('');
            }
        }
    }

    protected function _prepareEmailTriggerName($cartStatus){
        switch ($cartStatus) {
            case Models_Model_CartSession::CART_STATUS_NEW:
                $emailTriggerName = Cartstatusemail_Tools_CartstatusemailMailWatchdog::TRIGGER_CART_STATUS_ABANDONED;
                break;
            case Models_Model_CartSession::CART_STATUS_PENDING:
                $emailTriggerName = Cartstatusemail_Tools_CartstatusemailMailWatchdog::TRIGGER_CART_STATUS_ACTION_REQUIRE;
                break;
            case Models_Model_CartSession::CART_STATUS_PROCESSING:
                $emailTriggerName = Cartstatusemail_Tools_CartstatusemailMailWatchdog::TRIGGER_CART_STATUS_TECHNICAL_PROCESSING;
                break;
            case Models_Model_CartSession::CART_STATUS_COMPLETED:
                $emailTriggerName = Cartstatusemail_Tools_CartstatusemailMailWatchdog::TRIGGER_CART_STATUS_PAYMENT_RECEIVED;
                break;
            case Models_Model_CartSession::CART_STATUS_SHIPPED:
                $emailTriggerName = Cartstatusemail_Tools_CartstatusemailMailWatchdog::TRIGGER_CART_STATUS_ITEMS_SHIPPED;
                break;
            case Models_Model_CartSession::CART_STATUS_DELIVERED:
                $emailTriggerName = Cartstatusemail_Tools_CartstatusemailMailWatchdog::TRIGGER_CART_STATUS_ITEMS_DELIVERED;
                break;
            case Models_Model_CartSession::CART_STATUS_REFUNDED:
                $emailTriggerName = Cartstatusemail_Tools_CartstatusemailMailWatchdog::TRIGGER_CART_STATUS_REFUNDED_PURCHASE;
                break;
            case Tools_Misc::CS_ALIAS_PENDING:
                $emailTriggerName = Cartstatusemail_Tools_CartstatusemailMailWatchdog::TRIGGER_CART_STATUS_NEWQUOTE;
                break;
            case Tools_Misc::CS_ALIAS_PROCESSING:
                $emailTriggerName = Cartstatusemail_Tools_CartstatusemailMailWatchdog::TRIGGER_CART_STATUS_QUOTESENT;
                break;
            case Tools_Misc::CS_ALIAS_LOST_OPPORTUNITY:
                $emailTriggerName = Cartstatusemail_Tools_CartstatusemailMailWatchdog::TRIGGER_CART_STATUS_LOST_OPPORTUNITY;
                break;
            default:
                return false;
                break;
        }
        return $emailTriggerName;
    }

    /**
     * recover customer's cart and log him into the system if code provided
     *
     * @throws Exceptions_SeotoasterPluginException
     */
    public function cartRecoveryAction()
    {
        $code = filter_var($this->_request->getParam('code'), FILTER_SANITIZE_STRING);
        $usedUserId = filter_var($this->_request->getParam('userId'), FILTER_SANITIZE_NUMBER_INT);
        if ($code && $usedUserId) {
            $cartRestoredMapper = Cartstatusemail_Models_Mapper_CartstatusemailRestoredCartMapper::getInstance();
            $cartRestoredData = $cartRestoredMapper->findByCodeUserId($code, $usedUserId);
            if (!empty($cartRestoredData)) {
                $cartRestoredModel = $cartRestoredData[0];
                $verificationCode = $cartRestoredModel->getCode();
                if ($code === $verificationCode) {
                    $cartId = $cartRestoredModel->getCartId();
                    $userId = $cartRestoredModel->getUserId();
                    $userModel = Application_Model_Mappers_UserMapper::getInstance()->find($userId);
                    if ($userModel instanceof Application_Model_Models_User) {
                        $userRole = $userModel->getRoleId();
                        if (!in_array($userRole,
                            Cartstatusemail_Tools_CartstatusemailMailWatchdog::$cartRecoveryProtectedRoles)
                        ) {
                            $productMapper = Models_Mapper_ProductMapper::getInstance();
                            $cart = Models_Mapper_CartSessionMapper::getInstance()->find($cartId);
                            if (!$cart instanceof Models_Model_CartSession) {
                                $this->_redirector->gotoUrl($this->_websiteUrl);
                            }
                            $cartStatus = $cart->getStatus();
                            if (in_array($cartStatus, self::$acceptedCartStatuses)) {
                                $this->_logMemberInSystem($userModel);

                                $cartSession = Tools_ShoppingCart::getInstance();
                                $cartSession->setContent(array());
                                $cartSession->save();
                                $cartSession->setCartId($cartId);
                                $cartSession->setShippingAddressKey($cart->getShippingAddressId());
                                $cartSession->setBillingAddressKey($cart->getBillingAddressId());
                                $notFreebiesInCart = array();
                                $freebiesInCart = array();
                                $productsFreebiesRelation = array();
                                $cartContent = $cart->getCartContent();
                                $discount = $cart->getDiscount();
                                $discountRate = $cart->getDiscountTaxRate();
                                foreach ($cartContent as $key => $product) {
                                    if ($product['freebies'] === '1') {
                                        $freebiesInCart[$product['product_id']] = $product['product_id'];
                                    } else {
                                        $notFreebiesInCart[$product['product_id']] = $product['product_id'];
                                    }
                                }
                                if (!empty($freebiesInCart)) {
                                    $where = $productMapper->getDbTable()->getAdapter()->quoteInto(
                                        'sphp.freebies_id IN (?)',
                                        $freebiesInCart
                                    );
                                    $where .= ' AND ' . $productMapper->getDbTable()->getAdapter()->quoteInto(
                                            'sphp.product_id IN (?)',
                                            $notFreebiesInCart
                                        );
                                    $select = $productMapper->getDbTable()->getAdapter()->select()
                                        ->from(
                                            array('spfs' => 'shopping_product_freebies_settings'),
                                            array(
                                                'freebiesGroupKey' => new Zend_Db_Expr("CONCAT(sphp.freebies_id, '_', sphp.product_id)"),
                                                'price_value'
                                            )
                                        )
                                        ->joinleft(
                                            array('sphp' => 'shopping_product_has_freebies'),
                                            'spfs.prod_id = sphp.product_id'
                                        )
                                        ->where($where);
                                    $productFreebiesSettings = $productMapper->getDbTable()->getAdapter()->fetchAssoc($select);
                                }

                                if (!empty($productFreebiesSettings)) {
                                    foreach ($productFreebiesSettings as $prodInfo) {
                                        if (array_key_exists($prodInfo['freebies_id'], $freebiesInCart)) {
                                            if (isset($productsFreebiesRelation[$prodInfo['freebies_id']])) {
                                                $productsFreebiesRelation[$prodInfo['freebies_id']][$prodInfo['product_id']] = $prodInfo['product_id'];
                                            } else {
                                                $productsFreebiesRelation[$prodInfo['freebies_id']] = array($prodInfo['product_id'] => $prodInfo['product_id']);
                                            }
                                        }
                                    }
                                }

                                foreach ($cartContent as $key => $product) {
                                    $productObject = $productMapper->find($product['product_id']);
                                    if ($productObject instanceof Models_Model_Product) {
                                        if ($product['freebies'] === '1' && !empty($productsFreebiesRelation)) {
                                            foreach ($productsFreebiesRelation[$product['product_id']] as $realProductId) {
                                                $itemKey = $this->_generateStorageKey(
                                                    $productObject,
                                                    array(0 => 'freebies_' . $realProductId)
                                                );
                                                if (!$cartSession->findBySid($itemKey)) {
                                                    $productObject->setFreebies(1);
                                                    $cartSession->add(
                                                        $productObject,
                                                        array(0 => 'freebies_' . $realProductId),
                                                        $product['qty']
                                                    );
                                                }
                                            }
                                        } else {
                                            $options = array();
                                            if (is_array($product['options'])) {
                                                $options = $this->_parseProductOptions($product['options']);
                                            }
                                            $productObject->setPrice($product['price']);
                                            $productObject->setOriginalPrice($product['original_price']);
                                            $cartSession->add($productObject, $options, $product['qty']);
                                        }
                                    }
                                }
                                $cartSession->setDiscount($discount);
                                $cartSession->setShippingData(null);
                                $cartSession->setDiscountTaxRate($discountRate);
                                $cartSession->calculate(true);
                                $cartSession->save();

                                $restoredDate = date(Tools_System_Tools::DATE_MYSQL, strtotime('now'));
                                $cartRestoredModel->setRestoredAt($restoredDate);
                                $cartRestoredMapper->save($cartRestoredModel);
                                $checkoutPage = Tools_Misc::getCheckoutPage();
                                if (!$checkoutPage instanceof Application_Model_Models_Page) {
                                    throw new Exceptions_SeotoasterPluginException('Error rendering cart. Please select a checkout page');
                                }
                                $this->_redirector->gotoUrl($this->_websiteUrl . $checkoutPage->getUrl());
                            }
                        }
                    }
                }
            }
        }
        $this->_redirector->gotoUrl($this->_websiteUrl);

    }

    /**
     *
     * Prepare product options
     *
     * @param array $options
     * @return array
     */
    protected function _parseProductOptions($options)
    {
        $parsedOption = array();
        foreach ($options as $option) {
            $parsedOption[$option['id']] = $option['option_id'];
        }
        return $parsedOption;
    }


    /**
     * Log user into the system
     *
     * @param Application_Model_Models_User $userModel user object
     * @throws Exceptions_SeotoasterException
     */
    private function _logMemberInSystem(Application_Model_Models_User $userModel)
    {
        $userModel->setLastLogin(date(Tools_System_Tools::DATE_MYSQL));
        $userModel->setIpaddress($_SERVER['REMOTE_ADDR']);
        $userModel->setPassword(null);
        $this->_sessionHelper->setCurrentUser($userModel);
        Application_Model_Mappers_UserMapper::getInstance()->save($userModel);
    }

    /**
     * Unsubscribe from subscription view
     */
    public function unsubscribeAction()
    {
        $code = trim(filter_var($this->_request->getParam('code', false), FILTER_SANITIZE_STRING));
        if ($code) {
            $subscribeMapper = Cartstatusemail_Models_Mapper_CartstatusemailSubscribeMapper::getInstance();
            $subscriptionExists = $subscribeMapper->findByCode($code);
            if (!empty($subscriptionExists)) {
                if ($subscriptionExists['status'] === self::SUBSCRIBED_STATUS_SUBSCRIBED) {
                    $this->_view->unsubscribeCode = $code;
                    $this->_view->subscribeEmail = $subscriptionExists['email'];
                    $this->_layout->content = $this->_view->render('unsubscribe.phtml');
                    echo $this->_layout->render();
                    exit;
                }
            }
        }

        $this->_redirector->gotoUrl($this->_websiteUrl);
    }

    /**
     * Remove from subscription
     */
    public function removeSubscriptionAction()
    {
        $code = trim(filter_var($this->_request->getParam('code', false), FILTER_SANITIZE_STRING));
        $email = trim(filter_var($this->_request->getParam('email', false), FILTER_SANITIZE_STRING));
        $tokenToValidate = $this->_request->getParam(Tools_System_Tools::CSRF_SECURE_TOKEN, false);
        $valid = Tools_System_Tools::validateToken($tokenToValidate, self::CARTSTATUS_SECURE_TOKEN);
        if (!$valid) {
            exit;
        }
        if ($code && $email) {
            $subscribeMapper = Cartstatusemail_Models_Mapper_CartstatusemailSubscribeMapper::getInstance();
            $queueMapper = Cartstatusemail_Models_Mapper_CartstatusemailQueueMapper::getInstance();
            $subscriptionExists = $subscribeMapper->findByCodeEmail($code, $email);
            if (!empty($subscriptionExists)) {
                $subscribeMapper->updateStatus($code, self::SUBSCRIBED_STATUS_UNSUBSCRIBED);
                $queueMapper->updateQueueStatusByEmail($email, Cartstatusemail::QUEUE_STATUS_CANCELED);
                $this->_responseHelper->success('');
            }
        }
        $this->_responseHelper->fail('');
    }

}
