<?php

/**
 * payment plugin Paypal
 *
 * Class Paypal
 */
 
//include "/var/www/html/plugins/paypalexpress/Debug.php";  
 
class Paypal extends Tools_PaymentGateway {

    /**
     * Sandbox button end point
     * @var string
     */
    private $buttonSandBoxEndPoin = 'https://www.sandbox.paypal.com/'; //https://www.paypal.com/cgi-bin/webscr
    /**
     * Sandbox validate endpoint
     * @var string
     */
    private $buttonSandBoxValidateEndPoin = 'https://www.sandbox.paypal.com/cgi-bin/webscr';

    /**
     * Paypal validate endpoint
     * @var string
     */
    private $buttonValidateEndPoin = 'https://www.paypal.com/cgi-bin/webscr';

    /**
     * Paypal va
     * @var string
     */
    private $buttonLiveEndPoin = 'https://www.paypal.com/cgi-bin/webscr'; //https://www.paypal.com/cgi-bin/webscr

    /**
     * Sandbox direct payment end point
     *
     * @var string
     */
    private $creditCardSandBoxEndPoin = 'https://api-3t.sandbox.paypal.com/nvp';

    /**
     * Direct payment end point
     *
     * @var string
     */
    private $creditCardLiveEndPoin = 'https://api-3t.paypal.com/nvp';

    /**
     * Api version
     *
     * @var string
     */
    private $API_VERSOIN = '64.0';

    protected $_allowedStatuses = array(Models_Model_CartSession::CART_STATUS_NEW, Models_Model_CartSession::CART_STATUS_ERROR);

    /**
     * Subscribe types
     */
    const TYPE_SUBSCRIBE = 'subscribe';

    const TYPE_SUBSCRIBE_SIGNUP = 'subscr_signup';

    const TYPE_SUBSCRIBE_PAYMENT = 'subscr_payment';

    const TYPE_SUBSCRIBE_CANCEL = 'subscr_cancel';

    /**
     * Option thank you page (where user redirect after successful purchase)
     */
    const OPTION_THANKYOU = 'option_storethankyou';

    /**
     * Default button label
     */
    const BUTTON_LABEL = 'PayPal';

    /**
     * Secure token
     */
    const PAYPAL_SECURE_TOKEN = 'PaypalToken';

    /**
     * @param $options
     * @param $seotoasterData
     */
    public function  __construct($options, $seotoasterData) {
		parent::__construct($options, $seotoasterData);
        $this->_layout = new Zend_Layout();
        $this->_layout->setLayoutPath(Zend_Layout::getMvcInstance()->getLayoutPath());

        if(($scriptPaths = Zend_Layout::getMvcInstance()->getView()->getScriptPaths()) !== false) {
            $this->_view->setScriptPath($scriptPaths);
        }
        $this->_view->addScriptPath(__DIR__ . '/system/views/');
                        
    }

    /**
     * @param array $requestedParams
     * @return mixed
     */
    public function run($requestedParams = array()) {
        $dispatchersResult = parent::run($requestedParams);
		if($dispatchersResult) {
			return $dispatchersResult;
		}
        
    }

    /**
     * Provide selection between Credit cart (direct payment) and paypal button
     *
     * @return string
     */
    public function _makeOptionCardbutton(){
       return $this->_view->render("cardbutton.phtml");
    }

    /**
     * Create new customer address
     *
     * @param int $customerId user Id
     * @param array $address
     * @param null $type
     * @return mixed|null
     */
    private function _addAddress($customerId, $address, $type = null){
		$addressTable = new Models_DbTable_CustomerAddress();
		if (!empty($address)){
			if ($type !== null) {
				$address['address_type'] = $type;
			}
			$address = Tools_Misc::clenupAddress($address);
			$address['id'] = Tools_Misc::getAddressUniqKey($address);
			$address['user_id'] = $customerId;
			if (null === ($row = $addressTable->find($address['id'])->current())) {
				$row = $addressTable->createRow();
			}
			$row->setFromArray($address);

			return $row->save();
		}
		return null;
	}

    /**
     * Get shipping as billing
     */
    public function shippingAsBillingAction(){
        $shippingToBilling = $this->_request->getParam('shippingToBilling');
        if($shippingToBilling == '1'){
            $shippingAddressKey = Tools_ShoppingCart::getInstance()->getAddressKey(Models_Model_Customer::ADDRESS_TYPE_SHIPPING);
            if($shippingAddressKey != null){
                $paymentParams = Tools_ShoppingCart::getAddressById($shippingAddressKey);
                echo json_encode(array('error'=>'0','shipping'=>$paymentParams));                                        
            }
        }
        else{
             echo json_encode(array('error'=>'1')); 
        }
    }

    /**
     * Credit card (direct payment)
     *
     * @return string
     */
    public function _makeOptionCreditcard(){
        $paypalConfigMapper = Paypal_Models_Mapper_PaypalConfigMapper::getInstance();
        $paypalSettings = $paypalConfigMapper->selectSettings();
        $useSandbox = $paypalSettings[0]->getUseSandbox();
        $apiSignature = $paypalSettings[0]->getApiSignature();
        $apiUser = $paypalSettings[0]->getApiUser();
        $cartContent = Tools_ShoppingCart::getInstance();
        $cartId = $cartContent->getCartId();
        $cart = Models_Mapper_CartSessionMapper::getInstance()->find($cartId);
        if ($cart instanceof  Models_Model_CartSession) {
            $gateway = $cart->getGateway();
            $cartStatus = $cart->getStatus();
            if (!in_array($cartStatus, $this->_allowedStatuses) && $gateway == 'Paypal') {
                $this->_view->cartStatusError = true;
                unset($this->_sessionHelper->storeCartSessionKey);
                unset($this->_sessionHelper->storeCartSessionConversionKey);
                $cartContent->clean();
            }
        }
        $productList = array_values($cartContent->getContent());
        $this->_view->translator = $this->_translator;
        if(!empty($productList)){
            $this->_view->email = $paypalSettings[0]->getEmail();
            $this->_view->apiSignature = $apiSignature;
            $this->_view->apiUser = $apiUser;
            $this->_view->apiPassword = $paypalSettings[0]->getApiPassword();
            $this->_view->useSandBox = $useSandbox;
            $this->_view->productList = $productList;
            $this->_view->currency = Models_Mapper_ShoppingConfig::getInstance()->getConfigParam('currency');
            $this->_view->allowShipping = true;
            $this->_view->countryList =  $this->_prepareCountryList();
            if(!empty($apiSignature) && !empty($apiUser)) {
                    $cartParams = $cartContent->calculate();
                    $this->_view->totalAmount = $cartParams['total'];
                    $this->_view->shippingPaypal = $cartParams['shipping'];
                    $this->_view->totalTax = $cartParams['totalTax'];
                    
            }
            if(Models_Mapper_ShoppingConfig::getInstance()->getConfigParam('country') != ''){
                $predefinedCountry = Models_Mapper_ShoppingConfig::getInstance()->getConfigParam('country');
            }
            else{
                $predefinedCountry = 'US';
            }
            $billingAddressKey = Tools_ShoppingCart::getInstance()->getAddressKey(Models_Model_Customer::ADDRESS_TYPE_BILLING);
            if($billingAddressKey != null){
                $paymentParams = Tools_ShoppingCart::getAddressById($billingAddressKey);
                if(isset($paymentParams['state'])){
                    $state = Tools_Geo::getStateById($paymentParams['state']);
                    $paymentParams['state'] = $state['id'];
                    $this->_view->state = $paymentParams['state'];
                }
                $this->_view->firstName = $paymentParams['firstname'];
                $this->_view->lastName = $paymentParams['lastname'];
                $this->_view->address1 = $paymentParams['address1'];
                $this->_view->address2 = $paymentParams['address2'];
                $this->_view->billingEmail = $paymentParams['email'];
                $this->_view->city = $paymentParams['city'];
                $this->_view->zip = $paymentParams['zip'];
                $this->_view->phone = $paymentParams['phone'];
                $predefinedCountry = $paymentParams['country'];
                              
            }
            $listOfStates = $this->stateCheck($predefinedCountry);
            if($listOfStates['error'] != '1'){
                $this->_view->listOfState = $listOfStates[0];
            }
            $this->_view->predefinedCountry = $predefinedCountry;
            return $this->_view->render("creditcard.phtml");
            
        }
        
    }

    /**
     * Paypal button on generated quote page
     *
     * @return null|string
     * @throws Exceptions_SeotoasterException
     * @throws Exceptions_SeotoasterPluginException
     */
    public function _makeOptionQuote()
    {
        $paypalSettings = Paypal_Models_Mapper_PaypalConfigMapper::getInstance()->fetchAll();
        if (!empty($paypalSettings)) {
            $pageHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('page');
            $front = Zend_Controller_Front::getInstance();
            $productMapper = Models_Mapper_ProductMapper::getInstance();
            $quote = Quote_Models_Mapper_QuoteMapper::getInstance()->find(
                $pageHelper->clean($front->getRequest()->getParams('page'))
            );
            if (!$quote instanceof Quote_Models_Model_Quote) {
                throw new Exceptions_SeotoasterPluginException('Quote not found');
            }
            $cart = Models_Mapper_CartSessionMapper::getInstance()->find($quote->getCartId());
            if (!$cart instanceof Models_Model_CartSession) {
                throw new Exceptions_SeotoasterException('Cart not found');
            }
            if ($quote->getStatus() === Quote_Models_Model_Quote::STATUS_LOST) {
                return null;
            }
            $cartContent = $cart->getCartContent();
            $discount = $cart->getDiscount();
            $discountRate = $cart->getDiscountTaxRate();
            if (!$cartContent) {
                return null;
            }
            $status = $cart->getStatus();
            if ($status != Models_Model_CartSession::CART_STATUS_COMPLETED && $status != Models_Model_CartSession::CART_STATUS_DELIVERED && $status != Models_Model_CartSession::CART_STATUS_SHIPPED) {
                if($status === Models_Model_CartSession::CART_STATUS_PENDING && $cart->getGateway() !== 'Quote'){
                    return '';
                }
                $cartSession = Tools_ShoppingCart::getInstance();
                $cartSession->setContent(array());
                $cartSession->save();
                $cartSession->setCartId($quote->getCartId());
                $cartSession->setShippingAddressKey($cart->getShippingAddressId());
                $cartSession->setBillingAddressKey($cart->getBillingAddressId());
                $notFreebiesInCart = array();
                $freebiesInCart = array();
                $productsFreebiesRelation = array();
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
                $cartSession->setShippingData(array('price' => $cart->getShippingPrice()));
                $cartSession->setDiscountTaxRate($discountRate);
                $cartSession->calculate(true);
                $cartSession->save();


                $useSandbox = $paypalSettings[0]->getUseSandbox();
                $this->_view->email = $paypalSettings[0]->getEmail();
                $this->_view->useSandBox = $useSandbox;
                $this->_view->productList = $cartContent;
                $this->_view->translator = $this->_translator;
                $cartId = $cartSession->getCartId();
                $this->_view->cartId = $cartId;
                $this->_view->totalAmount = round($cartSession->getTotal(), 2);
                $this->_view->totalTax = round($cartSession->getTotalTax(), 2);
                $shippingData = $cartSession->getShippingData();
                $this->_view->shipping = round($shippingData['price'], 2);
                $this->_view->currency = Models_Mapper_ShoppingConfig::getInstance()->getConfigParam('currency');
                if ($useSandbox == 1) {
                    $this->_view->endPoint = $this->buttonSandBoxEndPoin; //https://www.sandbox.paypal.com/
                } else {
                    $this->_view->endPoint = $this->buttonLiveEndPoin; //'https://www.paypal.com/cgi-bin/webscr'
                }

                $typeSubscribe = array_search(self::TYPE_SUBSCRIBE, $this->_options);
                if (isset($this->_options[1]) && isset($this->_options[2]) && isset($this->_options[3]) && $typeSubscribe) {
                    //subscribe Config
                    $quantityPayments = $this->_options[1];
                    $period = $this->_options[3];
                    $subscribeCycle = $this->_options[2];

                    $this->_view->subscribeCycle = $subscribeCycle;
                    $this->_view->quantityPayments = $quantityPayments;
                    $this->_view->period = $period;
                    return $this->_view->render("subscribe_button.phtml");
                }

                $shippingAddressKey = Tools_ShoppingCart::getInstance()->getAddressKey(Models_Model_Customer::ADDRESS_TYPE_SHIPPING);
                if($shippingAddressKey != null){
                    $paymentParams = Tools_ShoppingCart::getAddressById($shippingAddressKey);
                    if(isset($paymentParams['state'])){
                       $state = Tools_Geo::getStateById($paymentParams['state']);
                       $paymentParams['state'] = $state['state'];
                       $this->_view->firstName = $paymentParams['firstname'];
                       $this->_view->lastName = $paymentParams['lastname'];
                       $this->_view->address1 = $paymentParams['address1'];
                       $this->_view->address2 = $paymentParams['address2'];
                       $this->_view->shippingEmail = $paymentParams['email'];
                       $this->_view->city = $paymentParams['city'];
                       $this->_view->state = $paymentParams['state'];
                       $this->_view->zip = $paymentParams['zip'];
                       $this->_view->country = $paymentParams['country'];
                       $this->_view->allowShipping = true;
                    }
                }
                return $this->_view->render("button.phtml");
            }

        } elseif (Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_PLUGINS)) {
            return $this->_translator->translate(
                'Number of time periods between each recurrence or time period is missing'
            );
        }
    }

    /**
     * Generate product unique key in cart
     *
     * @param Models_Model_Product $item
     * @param array $options
     * @return string
     */
    private function _generateStorageKey($item, $options = array())
    {
        return substr(md5($item->getName() . $item->getSku() . http_build_query($options)), 0, 10);
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
     * Get subscription info
     */
    public function getSubscribeDataAction(){
        $cartStorage = Tools_ShoppingCart::getInstance();
        $cartId = $cartStorage->getCartId();
        $cartSession = Models_Mapper_CartSessionMapper::getInstance()->find($cartId);
        $period = filter_var($this->_request->getParam('paymentPeriod'), FILTER_SANITIZE_STRING);
        $quantityPayments = filter_var($this->_request->getParam('paymentQty'), FILTER_SANITIZE_NUMBER_INT);
        if(!empty($cartSession)){
            $status = $cartSession->getStatus();
            if($status === Models_Model_CartSession::CART_STATUS_COMPLETED || $status === Models_Model_CartSession::CART_STATUS_PENDING){
               if($status === Models_Model_CartSession::CART_STATUS_PENDING  && $cartSession->getGateway() === 'Quote'){

               }else{
                    $this->_responseHelper->fail($this->_translator->translate('Already Payed'));
               }
            }
            $total = $cartSession->getTotal();
            $finalTotal = round($total/$quantityPayments, 2);
            $this->updateCartStatus($cartId, Models_Model_CartSession::CART_STATUS_PROCESSING);
            $this->_responseHelper->success(array('total' => $finalTotal));
        }
        $this->_responseHelper->fail('');
    }

    /**
     * Ipn subscription action
     */
    public function  subscribeIpnAction(){
        $data = $this->_request->getParams();
        if(isset($data['txn_type']) && ($data['txn_type'] === self::TYPE_SUBSCRIBE_PAYMENT || $data['txn_type'] === self::TYPE_SUBSCRIBE_SIGNUP)){
            $currency = filter_var($data['mc_currency'], FILTER_SANITIZE_STRING);
            $address = filter_var($data['address_street'], FILTER_SANITIZE_STRING);
            $verifySign = filter_var($data['verify_sign'], FILTER_SANITIZE_STRING);
            $firstName = filter_var($data['first_name'], FILTER_SANITIZE_STRING);
            $lastName = filter_var($data['last_name'], FILTER_SANITIZE_STRING);
            $payerEmail = filter_var($data['payer_email'], FILTER_SANITIZE_STRING);
            $addressState = filter_var($data['address_state'], FILTER_SANITIZE_STRING);
            $ipnTrackingId = filter_var($data['ipn_track_id'], FILTER_SANITIZE_STRING);
            $cartId = filter_var($data['custom'], FILTER_SANITIZE_STRING);
            $subscriptionId = $data['subscr_id'];
            $zip = filter_var($data['address_zip'], FILTER_SANITIZE_STRING);
            $addressName = filter_var($data['address_name'], FILTER_SANITIZE_STRING);

            $paypalTransactionMapper = Paypal_Models_Mapper_PaypalTransactionMapper::getInstance();
            $subscribeExist = $paypalTransactionMapper->getSubscribeBySubscribeId($subscriptionId);

            $cartSessionMapper = Models_Mapper_CartSessionMapper::getInstance();
            $cartContent = $cartSessionMapper->find(intval($cartId));
            if(!empty($cartContent)){
                if($data['txn_type'] === self::TYPE_SUBSCRIBE_SIGNUP){
                    $country = filter_var($data['address_country_code'], FILTER_SANITIZE_STRING);
                    $city = filter_var($data['address_city'], FILTER_SANITIZE_STRING);
                    $recurTimes = filter_var($data['recur_times'], FILTER_SANITIZE_NUMBER_INT);
                    $subscribeDate = filter_var($data['subscr_date'], FILTER_SANITIZE_STRING);

                    $period = filter_var($data['period3'], FILTER_SANITIZE_STRING);

                    $subscribeAmount = $data['mc_amount3'];
                    $periodData = explode(' ', $period);
                    $subscribeCycle = $periodData[0];
                    $subscribePeriodType = $periodData[1];
                }
                if($data['txn_type'] === self::TYPE_SUBSCRIBE_PAYMENT){
                    $paymentStatus = filter_var($data['payment_status'], FILTER_SANITIZE_STRING);
                    $pendingReason = filter_var($data['pending_reason'], FILTER_SANITIZE_STRING);
                    $paymentDate = filter_var($data['payment_date'], FILTER_SANITIZE_STRING);
                    $payerId = filter_var($data['payer_id'], FILTER_SANITIZE_STRING);
                    $txnId = filter_var($data['txn_id'], FILTER_SANITIZE_STRING);
                    if($paymentStatus === 'Completed'){
                        $paymentStatus = Models_Model_CartSession::CART_STATUS_COMPLETED;
                    }
                    if($paymentStatus === 'Pending'){
                        $paymentStatus = Models_Model_CartSession::CART_STATUS_PENDING;
                    }

                    $paymentData = array(
                      'status' => $paymentStatus,
                      'subscriptionAmountPayed' => $data['mc_gross'],
                      'pendingReason' => $pendingReason,
                      'subscriptionDatePayed'=> $paymentDate,
                      'payerId' => $payerId,
                      'txnId'  => $txnId
                    );
                }
                $paypalConfigMapper = Paypal_Models_Mapper_PaypalConfigMapper::getInstance();
                $paypalSettings = $paypalConfigMapper->selectSettings();
                $useSandbox = $paypalSettings[0]->getUseSandbox();
                if($useSandbox == 1) {
                    $endPoint = $this->buttonSandBoxValidateEndPoin; //https://www.sandbox.paypal.com/
                }
                else {
                    $endPoint = $this->buttonValidateEndPoin; //'https://www.paypal.com/cgi-bin/webscr'
                }
                $ipnData = http_build_query(array_merge(array('cmd' => '_notify-validate'), $_POST));
                $validateIpn = $this->getTransactionValidation($ipnData, $endPoint);

                //validation for saving
                if (trim($validateIpn) === 'VERIFIED') {
                    if($data['txn_type'] === self::TYPE_SUBSCRIBE_SIGNUP && empty($subscribeExist)){
                        //saving billing address//
                        $addressType = Models_Model_Customer::ADDRESS_TYPE_BILLING;
                        $billCountryResult = '';
                        $billStateResult = '';
                        $countries = Tools_Geo::getCountries(true);
                        if(isset($country)){
                            if(in_array($country, $countries)){
                                $billCountryResult = array_search($country, $countries);
                            }
                            if(array_key_exists($country, $countries)){
                                    $billCountryResult = $country;
                                }
                            }
                            $states = Tools_Geo::getState(null, true);
                            if(isset($addressState)){
                                if(in_array($addressState, $states)){
                                    $billStateResult = array_search($addressState, $states);
                                }
                            }
                            $billingAddressArray = array();
                            $billingAddressArray['firstname'] = isset($firstName)?$firstName:'';
                            $billingAddressArray['lastname']  = isset($lastName)?$lastName:'';
                            $billingAddressArray['company']   = '';
                            $billingAddressArray['email']     = isset($payerMail)?$payerMail:'';
                            $billingAddressArray['address1']  = isset($address)?$address:'';
                            $billingAddressArray['address2']  = '';
                            $billingAddressArray['country']   = $billCountryResult;
                            $billingAddressArray['city']      = isset($city)?$city:'';
                            $billingAddressArray['state']     = $billStateResult;
                            $billingAddressArray['zip']       = isset($zip)?$zip:'';
                            $billingAddressArray['phone']     = '';
                            $billingAddressArray['mobile']    = '';
                            $addressId = $this->_addAddress($cartContent->getUserId(), $billingAddressArray, $addressType);
                            $cartContent->setBillingAddressId($addressId);
                            $cartSessionMapper->save($cartContent);

                            $paypalTransactionModel = new Paypal_Models_Models_PaypalTransactionModel();
                            $paypalTransactionModel->setTxnId('');
                            $paypalTransactionModel->setPayerId('');
                            $paypalTransactionModel->setPayerMail($payerEmail);
                            $paypalTransactionModel->setAmount($subscribeAmount);
                            $paypalTransactionModel->setShippingAmount(0);
                            $paypalTransactionModel->setTax(0);
                            $paypalTransactionModel->setCurrency($currency);
                            $paypalTransactionModel->setPaymentStatus('');
                            $paypalTransactionModel->setStatus(Models_Model_CartSession::CART_STATUS_PROCESSING);
                            $paypalTransactionModel->setPaymentType(self::TYPE_SUBSCRIBE);
                            $paypalTransactionModel->setPaymentId($cartId);
                            $paypalTransactionModel->setPaymentDate('');
                            $paypalTransactionModel->setPFirstName($firstName);
                            $paypalTransactionModel->setPLastName($lastName);
                            $paypalTransactionModel->setPCountry($country);
                            $paypalTransactionModel->setPCountryCode('');
                            $paypalTransactionModel->setPAddressState($addressState);
                            $paypalTransactionModel->setPAddressCity($city);
                            $paypalTransactionModel->setPAddressZip($zip);
                            $paypalTransactionModel->setPAddressName($addressName);
                            $paypalTransactionModel->setCartId($cartId);
                            $paypalTransactionModel->setPendingReason('');
                            //subscribe info
                            $paypalTransactionModel->setSubscribePeriod($subscribeCycle);
                            $paypalTransactionModel->setSubscribePeriodType($subscribePeriodType);
                            $paypalTransactionModel->setSubscribeQuantity($recurTimes);
                            $paypalTransactionModel->setSubscribeStatus('');
                            $paypalTransactionModel->setSubscribeAmount($subscribeAmount);
                            $paypalTransactionModel->setSubscribeDate($subscribeDate);
                            $paypalTransactionModel->setSubscriptionId($subscriptionId);
                            $paypalTransactionMapper->save($paypalTransactionModel);
                    }elseif($data['txn_type'] === self::TYPE_SUBSCRIBE_PAYMENT && !empty($subscribeExist)){
                        $previousPaymentDate = $subscribeExist[0]->getSubscriptionDatePayed();
                        if($previousPaymentDate !== $paymentDate) {
                            $previousPayedAmount = $subscribeExist[0]->getSubscriptionAmountPayed();
                            if($previousPayedAmount !== ''){
                                $paymentData['subscriptionAmountPayed'] = $paymentData['subscriptionAmountPayed'] + $previousPayedAmount;
                            }
                            $paypalTransactionMapper->updateSubscription($paymentData, $subscriptionId);
                            $cartSessionDbTable = new Models_DbTable_CartSession();
                            $where = $cartSessionDbTable->getAdapter()->quoteInto('id=?', $cartId);
                            $data = array(
                              'total' => $paymentData['subscriptionAmountPayed']
                            );
                            $cartSessionDbTable->getAdapter()->update('shopping_cart_session', $data, $where);
                            $this->updateCartStatus($cartId, $paymentData['status']);
                            //sending email or new order
                            $this->_sessionHelper->storeCartSessionKey = $cartId;
                            $cartContent->registerObserver(new Tools_Mail_Watchdog(array(
                                    'trigger' => Tools_StoreMailWatchdog::TRIGGER_NEW_ORDER
                                )));
                            $cartContent->notifyObservers();
                            //end of sending new order email
                        }
                    }elseif(isset($data['txn_type']) && $data['txn_type'] === self::TYPE_SUBSCRIBE_CANCEL && !empty($subscribeExist)){
                        $paymentDate = filter_var($data['payment_date'], FILTER_SANITIZE_STRING);
                        $paymentData = array(
                            'status' => Models_Model_CartSession::CART_STATUS_CANCELED,
                            'subscriptionDatePayed' => $paymentDate
                        );
                        $paypalTransactionMapper->updateSubscription($paymentData, $subscriptionId);
                        $this->updateCartStatus($cartId, $paymentData['status']);
                    }else{
                        $this->_responseHelper->fail('');
                    }
                }
            }else{
                $this->_responseHelper->fail('');
            }

        }
    }

    /**
     * Paypal button
     *
     * @return string
     */
    public function _makeOptionButton(){
        $paypalConfigMapper = Paypal_Models_Mapper_PaypalConfigMapper::getInstance();
        $paypalSettings = $paypalConfigMapper->selectSettings();
        $useSandbox = $paypalSettings[0]->getUseSandbox();
        $apiSignature = $paypalSettings[0]->getApiSignature();
        $apiUser = $paypalSettings[0]->getApiUser();
        $cartContent = Tools_ShoppingCart::getInstance();
        $productList = array_values($cartContent->getContent());
        $this->_view->translator = $this->_translator;
        if(isset($this->_options[1])){
            $this->_view->customButton = true;
        }
        $customLabel = self::BUTTON_LABEL;
        if(isset($this->_options[2])){
            $customLabel = $this->_options[2];
        }
        $this->_view->customLabel = $customLabel;
        if(!empty ($productList)){
            $this->_view->email = $paypalSettings[0]->getEmail();
            $this->_view->apiSignature = $apiSignature;
            $this->_view->apiUser = $apiUser;
            $this->_view->apiPassword = $paypalSettings[0]->getApiPassword();
            $this->_view->useSandBox = $useSandbox;
            $this->_view->productList = $productList;
            $cartParams = $cartContent->calculate();
            $cartId = $cartContent->getCartId();
            $this->_view->totalTax = round($cartParams['totalTax'],2);
            $this->_view->shipping = round($cartParams['shipping'],2);
            $this->_view->totalAmount = round($cartParams['total'],2);
            $this->_view->discount = round($cartParams['discount'],2);
            $this->_view->cartId = $cartId;
            $shippingAddressKey = Tools_ShoppingCart::getInstance()->getAddressKey(Models_Model_Customer::ADDRESS_TYPE_SHIPPING);
            if($shippingAddressKey != null){
                $paymentParams = Tools_ShoppingCart::getAddressById($shippingAddressKey);
                if(isset($paymentParams['state'])){
                        $state = Tools_Geo::getStateById($paymentParams['state']);
                        $paymentParams['state'] = $state['state'];
                        $this->_view->firstName = $paymentParams['firstname'];
                        $this->_view->lastName = $paymentParams['lastname'];
                        $this->_view->address1 = $paymentParams['address1'];
                        $this->_view->address2 = $paymentParams['address2'];
                        $this->_view->shippingEmail = $paymentParams['email'];
                        $this->_view->city = $paymentParams['city'];
                        $this->_view->state = $paymentParams['state'];
                        $this->_view->zip = $paymentParams['zip'];
                        $this->_view->country = $paymentParams['country'];
                        $this->_view->allowShipping = true;
                                        
                }
            }
            $this->_view->currency = Models_Mapper_ShoppingConfig::getInstance()->getConfigParam('currency');
            if($useSandbox == 1) {
               $this->_view->endPoint = $this->buttonSandBoxEndPoin; //https://www.sandbox.paypal.com/
            }
            else {
               $this->_view->endPoint = $this->buttonLiveEndPoin; //'https://www.paypal.com/cgi-bin/webscr'
            }
            return $this->_view->render("button.phtml");
                            
        }
    }

    /**
     * Get country list
     *
     * @return array
     */
    private function _prepareCountryList(){
        $data = Tools_Geo::getCountries(true);
		asort($data);
		return $data;
	}

    /**
     * Prepare states list
     *
     * @param string $country
     * @return array
     */
    public function stateCheck($country){
        $pairs = true;
        $stateData = Tools_Geo::getState($country, $pairs);
        if(!empty ($stateData)){
            $states = '';
            foreach($stateData as $short=>$state){
               $states .= '<option value="'.$short.'">'.$state.'</option>';
            }
           return array('error'=>'0',$states);
        }
        else{
           return  array('error'=>'1');
        }
    }

    /**
     * Prepare shipping data before starting payment with paypal button
     */
    public function getButtonShippingDataAction(){
       $cartStorage = Tools_ShoppingCart::getInstance();
       $cartId = $cartStorage->getCartId();
       $cartSession = Models_Mapper_CartSessionMapper::getInstance()->find($cartId);
       $amount = $this->_request->getParam('amount');
       if(!empty($cartSession)){
           $cartSummary = $cartStorage->calculate();
           $cartSummary['total'] = round($cartSummary['total'], 2);
           if (round($amount, 2) !== $cartSummary['total']) {
               $checkout = Tools_Misc::getCheckoutPage();
               $this->_responseHelper->fail(array('error'=> 1,'redirect'=>$this->_websiteUrl.$checkout->getUrl()));
           }
           $status = $cartSession->getStatus();
           if($status == Models_Model_CartSession::CART_STATUS_COMPLETED || $status == Models_Model_CartSession::CART_STATUS_PENDING){
               if($status === Models_Model_CartSession::CART_STATUS_PENDING  && $cartSession->getGateway() === 'Quote'){

               }else{
                   $thankyouPage = Application_Model_Mappers_PageMapper::getInstance()->fetchByOption(self::OPTION_THANKYOU,
                       true);
                   if (!$thankyouPage) {
                       $this->_responseHelper->fail(array(
                           'error' => 1,
                           'redirect' => $this->_websiteUrl . $this->_websiteHelper->getDefaultPage()
                       ));
                   } else {
                       $this->_sessionHelper->storeCartSessionKey = $cartId;
                       $this->_sessionHelper->storeCartSessionConversionKey = $cartId;
                       $cartStorage->clean();
                       $this->_responseHelper->fail(array(
                           'error' => 1,
                           'redirect' => $this->_websiteUrl . $thankyouPage->getUrl()
                       ));
                   }

                   return;
               }
           }
           //save emails status info
           $paypalTransactionMapper = Paypal_Models_Mapper_PaypalTransactionMapper::getInstance();
           $transactionExists = $paypalTransactionMapper->findByCartId($cartId);
           if (!empty($transactionExists)) {
               $paypalTransaction = $transactionExists[0];
           } else{
               $paypalTransaction  = new Paypal_Models_Models_PaypalTransactionModel();
               $paypalTransaction->setCartId($cartId);
               $paypalTransaction->setPaymentId($cartId);
           }

           if (isset($this->_sessionHelper->storeIsNewCustomer))  {
               $paypalTransaction->setCustomerEmailSent(0);
           } else {
               $paypalTransaction->setCustomerEmailSent(1);
           }
           $paypalTransaction->setEmailSent(0);
           $paypalTransactionMapper->save($paypalTransaction);
       }
       //affiliate block  
       $enabledPlugins = Tools_Plugins_Tools::getEnabledPlugins();
       foreach ($enabledPlugins as $plugin) {
          if($plugin->getName() == 'seosambaaffiliatenetwork'){
              $affiliate = Tools_Factory_PluginFactory::createPlugin('seosambaaffiliatenetwork');
              $affiliateStatus = $affiliate->affiliateSave($cartId, 'paypal', 'button');
          }
       }
       //affiliate end
       $this->updateCartStatus($cartId, Models_Model_CartSession::CART_STATUS_PROCESSING);
       echo json_encode(array('error'=>0));
       
    }

    /**
     * Success action
     */
    public function successAction(){
        $this->_redirector->gotoUrl($this->_websiteUrl.'plugin/shopping/run/thankyou/');
    }

    public function successIpnAction(){
        $cartId = Tools_ShoppingCart::getInstance()->getCartId();
        if ($cartId) {
            $paypalTransactionMapper = Paypal_Models_Mapper_PaypalTransactionMapper::getInstance();
            $transactionExists = $paypalTransactionMapper->findByCartId($cartId);
            $emailSent = false;
            if (!empty($transactionExists)) {
                $paypalTransactionConfig = $transactionExists[0];
                $emailSent = $paypalTransactionConfig->getEmailSent();
            } else{
                $paypalTransactionConfig  = new Paypal_Models_Models_PaypalTransactionModel();
            }
            if (!$emailSent) {
                $paypalTransactionConfig->setCartId($cartId);
                $paypalTransactionConfig->setEmailSent(1);
                $paypalTransactionMapper->save($paypalTransactionConfig);
                $this->_redirector->gotoUrl($this->_websiteUrl.'plugin/shopping/run/thankyou/');
            }
            if (isset($this->_sessionHelper->storeIsNewCustomer)) {
                $cartSession = Models_Mapper_CartSessionMapper::getInstance()->find($cartId);
                $status = $cartSession->getStatus();
                if ($status === Models_Model_CartSession::CART_STATUS_COMPLETED || $status === Models_Model_CartSession::CART_STATUS_PENDING) {
                    $userMapper = Application_Model_Mappers_UserMapper::getInstance();
                    $userData = $userMapper->find($cartSession->getUserId());
                    $newCustomerPassword = uniqid('customer_' . time());
                    $userData->setPassword($newCustomerPassword);
                    $userMapper->save($userData);
                    $customer = Models_Mapper_CustomerMapper::getInstance()->find($cartSession->getUserId());
                    $customer->setPassword($newCustomerPassword);
                    $customer->registerObserver(new Tools_Mail_Watchdog(array(
                        'trigger' => Tools_StoreMailWatchdog::TRIGGER_NEW_CUSTOMER
                    )));
                    $customer->notifyObservers();
                    $paypalTransactionConfig->setCartId($cartId);
                    $paypalTransactionConfig->setCustomerEmailSent(1);
                    $paypalTransactionMapper->save($paypalTransactionConfig);
                    unset($this->_sessionHelper->storeIsNewCustomer);
                }
            }
            $thankYouPage = Application_Model_Mappers_PageMapper::getInstance()->fetchByOption(Shopping::OPTION_THANKYOU,
                true);
            if ($thankYouPage instanceof Application_Model_Models_Page) {
                Tools_ShoppingCart::getInstance()->clean();
                $this->_sessionHelper->storeCartSessionKey = $cartId;
                $this->_sessionHelper->storeCartSessionConversionKey = $cartId;
                $this->_redirector->gotoUrl($this->_websiteUrl . $thankYouPage->getUrl());
            } else {
                $this->_redirector->gotoUrl($this->_websiteUrl);
            }
        }
    }

    /**
     * Paypal ipn response action
     */
    public function ipnAction(){
        $txnId = urldecode($this->_request->getParam('txn_id'));
        $payerId = urldecode($this->_request->getParam('payer_id'));
        $payerMail = urldecode($this->_request->getParam('payer_email'));
        $amount = urldecode($this->_request->getParam('mc_gross'));
        $shipAmount = urldecode($this->_request->getParam('mc_shipping1'));
        $tax = urldecode($this->_request->getParam('tax'));
        $currency = urldecode($this->_request->getParam('mc_currency'));
        $paymentStatus = urldecode($this->_request->getParam('payment_status'));
        $status = urldecode($this->_request->getParam('payer_status'));
        $pstTime = date_parse(urldecode($this->_request->getParam('payment_date')));
        $paymentDate = $pstTime['year'].'-'.$pstTime['month'].'-'.$pstTime['day'].' '.$pstTime['hour'].':'.$pstTime['minute'].':'.$pstTime['second'];
        $pFirstName = urldecode($this->_request->getParam('first_name'));
        $pLastName = urldecode($this->_request->getParam('last_name'));
        $pCountry = urldecode($this->_request->getParam('address_country'));
        $pCountryCode = urldecode($this->_request->getParam('address_country_code'));
        $pAddressState = urldecode($this->_request->getParam('address_state'));
        $pAddressCity = urldecode($this->_request->getParam('address_city'));
        $pAddressZip = urldecode($this->_request->getParam('address_zip'));
        $pPhone = urldecode($this->_request->getParam('contact_phone'));
        $pAddressStreet = urldecode($this->_request->getParam('address_street'));
        $cartId = urldecode($this->_request->getParam('custom'));
        $pending = urldecode($this->_request->getParam('pending_reason'));
        $pendingReason = '';
        if(isset($pending)){
          $pendingReason = $pending; 
        }

        if ($paymentStatus === 'Refunded') {
            $paymentStatus = Models_Model_CartSession::CART_STATUS_REFUNDED;
        }

        if($paymentStatus == 'Completed'){
            $paymentStatus = Models_Model_CartSession::CART_STATUS_COMPLETED;
        }
        if($paymentStatus == 'Pending'){
            $paymentStatus = Models_Model_CartSession::CART_STATUS_PENDING;
        }

        $cartSession = Models_Mapper_CartSessionMapper::getInstance();
        $cartContent = $cartSession->find($cartId);
        $paypalTransactionMapper = Paypal_Models_Mapper_PaypalTransactionMapper::getInstance();

        $paypalConfigMapper = Paypal_Models_Mapper_PaypalConfigMapper::getInstance();
        $paypalSettings = $paypalConfigMapper->selectSettings();
        $useSandbox = $paypalSettings[0]->getUseSandbox();
        if($useSandbox == 1) {
            $endPoint = $this->buttonSandBoxValidateEndPoin; //https://www.sandbox.paypal.com/
        }
        else {
            $endPoint = $this->buttonValidateEndPoin; //'https://www.paypal.com/cgi-bin/webscr'
        }
        $ipnData = http_build_query(array_merge(array('cmd' => '_notify-validate'), $_POST));
        $validateIpn = $this->getTransactionValidation($ipnData, $endPoint);
        //validation for saving
        if (trim($validateIpn) === 'VERIFIED' && $cartContent != null) {
            $transactionExists = $paypalTransactionMapper->findByCartId($cartId);
            $emailSent = false;
            if (!empty($transactionExists)) {
                $paypalTransactionConfig = $transactionExists[0];
                $emailSent = $paypalTransactionConfig->getEmailSent();
            } else{
                $paypalTransactionConfig  = new Paypal_Models_Models_PaypalTransactionModel();
            }

            $cartTotal = $cartContent->getTotal();

            //saving billing address//
            $cartSessionMapper = Models_Mapper_CartSessionMapper::getInstance();
            $cartSession = $cartSessionMapper->find($cartId);
            $addressType = Models_Model_Customer::ADDRESS_TYPE_BILLING;
            $billCountryResult = '';
            $billStateResult = '';
            $countries = Tools_Geo::getCountries(true);
            if(isset($pCountryCode)){
                if(in_array($pCountryCode, $countries)){
                    $billCountryResult = array_search($pCountryCode, $countries);
                }
                if(array_key_exists($pCountryCode, $countries)){
                    $billCountryResult = $pCountryCode;
                }
            }
            $states = Tools_Geo::getState(null, true);
            if(isset($pAddressState)){
                if(in_array($pAddressState, $states)){
                    $billStateResult = array_search($pAddressState, $states);
                }
            }
            $billingAddressArray = array();
            $billingAddressArray['firstname'] = isset($pFirstName)?$pFirstName:'';
            $billingAddressArray['lastname']  = isset($pLastName)?$pLastName:'';
            $billingAddressArray['company']   = '';
            $billingAddressArray['email']     = isset($payerMail)?$payerMail:'';
            $billingAddressArray['address1']  = isset($pAddressStreet)?$pAddressStreet:'';
            $billingAddressArray['address2']  = '';
            $billingAddressArray['country']   = $billCountryResult;
            $billingAddressArray['city']      = isset($pAddressCity)?$pAddressCity:'';
            $billingAddressArray['state']     = $billStateResult;
            $billingAddressArray['zip']       = isset($pAddressZip)?$pAddressZip:'';
            $billingAddressArray['phone']     = isset($pPhone)?$pPhone:'';
            $billingAddressArray['mobile']    = isset($pPhone)?$pPhone:'';

            $addressId = $this->_addAddress($cartSession->getUserId(), $billingAddressArray, $addressType);
            $cartSession->setBillingAddressId($addressId);
            $cartSessionMapper->save($cartSession);
            //end of saving billing address//

            if($amount != $cartTotal && $paymentStatus !== Models_Model_CartSession::CART_STATUS_REFUNDED){
                $reason =  $this->_translator->translate('Amount not right');
                $status = Models_Model_CartSession::CART_STATUS_ERROR;
                $paypalTransactionConfig->setTxnId($txnId);
                $paypalTransactionConfig->setPayerId($payerId);
                $paypalTransactionConfig->setPayerMail($payerMail);
                $paypalTransactionConfig->setAmount($amount);
                $paypalTransactionConfig->setShippingAmount($shipAmount);
                $paypalTransactionConfig->setTax($tax);
                $paypalTransactionConfig->setCurrency($currency);
                $paypalTransactionConfig->setPaymentStatus($paymentStatus);
                $paypalTransactionConfig->setStatus($status);
                $paypalTransactionConfig->setPaymentType('button');
                $paypalTransactionConfig->setPaymentId($cartId);
                $paypalTransactionConfig->setPaymentDate($paymentDate);
                $paypalTransactionConfig->setPFirstName($pFirstName);
                $paypalTransactionConfig->setPLastName($pLastName);
                $paypalTransactionConfig->setPCountry($pCountry);
                $paypalTransactionConfig->setPCountryCode($pCountryCode);
                $paypalTransactionConfig->setPAddressState($pAddressState);
                $paypalTransactionConfig->setPAddressCity($pAddressCity);
                $paypalTransactionConfig->setPAddressZip($pAddressZip);
                $paypalTransactionConfig->setPAddressName($pAddressStreet);
                $paypalTransactionConfig->setCartId($cartId);
                $paypalTransactionConfig->setPendingReason($reason);
                $paypalTransactionMapper->save($paypalTransactionConfig);
                $this->updateCartStatus($cartId, Models_Model_CartSession::CART_STATUS_ERROR);
            }
            else{
                //affiliate block
                $enabledPlugins = Tools_Plugins_Tools::getEnabledPlugins();
                foreach ($enabledPlugins as $plugin) {
                    if($plugin->getName() == 'seosambaaffiliatenetwork'){
                        $affiliate = Tools_Factory_PluginFactory::createPlugin('seosambaaffiliatenetwork');
                        $affiliateStatus = $affiliate->affiliateSale($cartId, $amount);
                    }
                }
                //affiliate end
                $paypalTransactionConfig->setTxnId($txnId);
                $paypalTransactionConfig->setPayerId($payerId);
                $paypalTransactionConfig->setPayerMail($payerMail);
                $paypalTransactionConfig->setAmount($amount);
                $paypalTransactionConfig->setShippingAmount($shipAmount);
                $paypalTransactionConfig->setTax($tax);
                $paypalTransactionConfig->setCurrency($currency);
                $paypalTransactionConfig->setPaymentStatus($paymentStatus);
                $paypalTransactionConfig->setStatus($status);
                $paypalTransactionConfig->setPaymentType('button');
                $paypalTransactionConfig->setPaymentId($cartId);
                $paypalTransactionConfig->setPaymentDate($paymentDate);
                $paypalTransactionConfig->setPFirstName($pFirstName);
                $paypalTransactionConfig->setPLastName($pLastName);
                $paypalTransactionConfig->setPCountry($pCountry);
                $paypalTransactionConfig->setPCountryCode($pCountryCode);
                $paypalTransactionConfig->setPAddressState($pAddressState);
                $paypalTransactionConfig->setPAddressCity($pAddressCity);
                $paypalTransactionConfig->setPAddressZip($pAddressZip);
                $paypalTransactionConfig->setPAddressName($pAddressStreet);
                $paypalTransactionConfig->setCartId($cartId);
                $paypalTransactionConfig->setPendingReason($pendingReason);
                $paypalTransactionConfig->setEmailSent(1);
                $paypalTransactionMapper->save($paypalTransactionConfig);
                $this->updateCartStatus($cartId, $paymentStatus);
                //sending email for new order
                if (!$emailSent) {
                    $this->_sessionHelper->storeCartSessionKey = $cartId;
                    $this->_sessionHelper->storeCartSessionConversionKey = $cartId;
                    $cartSession->registerObserver(new Tools_Mail_Watchdog(array(
                        'trigger' => Tools_StoreMailWatchdog::TRIGGER_NEW_ORDER
                    )));

                    $customerEmailSent = $paypalTransactionConfig->getCustomerEmailSent();
                    if (!$customerEmailSent) {
                        $userMapper = Application_Model_Mappers_UserMapper::getInstance();
                        $userData = $userMapper->find($cartSession->getUserId());
                        $newCustomerPassword = uniqid('customer_' . time());
                        $userData->setPassword($newCustomerPassword);
                        $userMapper->save($userData);
                        $customer = Models_Mapper_CustomerMapper::getInstance()->find($cartSession->getUserId());
                        $customer->setPassword($newCustomerPassword);
                        $customer->registerObserver(new Tools_Mail_Watchdog(array(
                            'trigger' => Tools_StoreMailWatchdog::TRIGGER_NEW_CUSTOMER
                        )));
                        $customer->notifyObservers();
                        unset($this->_sessionHelper->storeIsNewCustomer);
                    }

                    if (class_exists('Tools_AppsServiceWatchdog')) {
                        $cartSession->registerObserver(new Tools_AppsServiceWatchdog());
                    }
                    $cartSession->notifyObservers();
                }
                //end of sending new order email
             }
        }
                 
    }

    /**
     * Direct payment action
     */
    public function payCreditCAction(){
       if ($this->_request->isPost()) {
            $data =  $this->_request->getParams(); 
			$creditCardNum = preg_replace(array('/ /','/-/','/\//'), '', trim($data['cardnumber']));
            $sessionHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('session');
            $customer    = $sessionHelper->getCurrentUser();
            $cartStorage = Tools_ShoppingCart::getInstance();
            $cartId = $cartStorage->getCartId();
            $cart = Models_Mapper_CartSessionMapper::getInstance()->find($cartId);
           if($cart instanceof  Models_Model_CartSession) {
               $cartStatus = $cart->getStatus();
               $gateway = $cart->getGateway();
               if (!in_array($cartStatus, $this->_allowedStatuses) && $gateway == 'Paypal') {
                   $payResult['error'] = 1;
                   $payResult['message'] = "We can't confirm your order at the moment due to a technical difficulty. If you do not receive an email in the coming hours confirming your purchase, please contact us";
                   echo json_encode($payResult);
                   exit;
               }
           }
           $this->updateCartStatus($cartId, Models_Model_CartSession::CART_STATUS_PROCESSING);
           $cartSummary = $cartStorage->calculate();
            $cartSummary['totalTax'] = round($cartSummary['totalTax'], 2);
			$cartSummary['shipping'] = round($cartSummary['shipping'], 2);
			$cartSummary['total'] = round($cartSummary['total'], 2);
            if (round($data['amt'], 2) !== $cartSummary['total']) {
                $checkout = Tools_Misc::getCheckoutPage();
                $this->_responseHelper->fail(array('error'=> 1,'redirect'=>$this->_websiteUrl.$checkout->getUrl()));
            }

            $dataForPayment = array(
                'PAYMENTACTION'     =>  'Sale',
                'IPADDRESS'         =>  $_SERVER['REMOTE_ADDR'],
                'CREDITCARDTYPE'    =>  $data['type'],
                'ACCT'              =>  $creditCardNum,
                'EXPDATE'           =>  str_pad($data['expiration_date_month'], 2, '0', STR_PAD_LEFT).$data['expiration_date_year'],
                'CVV2'              =>  trim($data['verification_number']),
                'EMAIL'             =>  trim($data['email']),
                'FIRSTNAME'         =>  trim($data['firstname']),
                'LASTNAME'          =>  trim($data['lastname']),
                'STREET'            =>  trim($data['billing_address1']),
                'STREET2'           =>  trim($data['billing_address2']),
                'CITY'              =>  trim($data['city']),
                'STATE'             =>  isset($data['state'])?$data['state']:'',
                'COUNTRYCODE'       =>  $data['country'],
                'ZIP'               =>  trim($data['zip']),
                'PHONENUM'          =>  trim($data['phone']),
                'AMT'               =>  $cartSummary['total'],
                'ITEMAMT'			=> ($cartSummary['total']-$cartSummary['totalTax']-$cartSummary['shipping']),
				'SHIPPINGAMT'		=>  $cartSummary['shipping'] !== '' ? $cartSummary['shipping'] : '0.00',
                'TAXAMT'			=>	$cartSummary['totalTax'] !== '' ? $cartSummary['totalTax'] : '0.00',
                'CURRENCYCODE'      =>  $data['currency_code'],
                'DESC'              =>  $data['desc']
            );
            $shippingAddressKey = $cartStorage->getAddressKey(Models_Model_Customer::ADDRESS_TYPE_SHIPPING);
            if($shippingAddressKey != null){
                $sessionHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('session');
                $customer = $sessionHelper->getCurrentUser();
                $customerShipping = Tools_ShoppingCart::getAddressById($shippingAddressKey);
                $state = Tools_Geo::getStateById($customerShipping['state']);
                $customerShipping['state'] = $state['state'];
                $dataForPayment = $dataForPayment + array(
                    'SHIPTONAME'     => $customerShipping['firstname'] . ' ' . $customerShipping ['lastname'],
                    'SHIPTOSTREET'   => isset($customerShipping ['address1'])?$customerShipping ['address1']:'',
                    'SHIPTOSTREET2'  => isset($customerShipping ['address2'])?$customerShipping ['address2']:'',
                    'SHIPTOCITY'     => $customerShipping ['city'],
                    'SHIPTOSTATE'    => $customerShipping ['state'],
                    'SHIPTOZIP'      => isset($customerShipping ['zip'])?$customerShipping ['zip']:'',
                    'SHIPTOCOUNTRY'  => $customerShipping ['country'],
                    'SHIPTOPHONENUM' => isset($customerShipping ['phone'])?$customerShipping ['phone']:''
                );
            }
            $payment = $this->createDoDirectPayment($dataForPayment);
            if (preg_match('/success/i', $payment['ACK']) && isset($payment['TRANSACTIONID'])) {
                $infoPayment = $this->getTransactionDetails($payment['TRANSACTIONID']);
                if (preg_match('/success/i', $infoPayment['ACK'])) {
                    $paypalTransactionMapper = Paypal_Models_Mapper_PaypalTransactionMapper::getInstance();
                    $paypalTransactionConfig  = new Paypal_Models_Models_PaypalTransactionModel();
                    //affiliate block  
                    $enabledPlugins = Tools_Plugins_Tools::getEnabledPlugins();
                    foreach ($enabledPlugins as $plugin) {
                        if($plugin->getName() == 'seosambaaffiliatenetwork'){
                            $affiliate = Tools_Factory_PluginFactory::createPlugin('seosambaaffiliatenetwork');
                            $affiliateStatus = $affiliate->affiliateSave($cartId, 'paypal', 'creditcard');
                            $affiliateStatus = $affiliate->affiliateSale($cartId, $infoPayment['AMT']);
                        }
           
                    }
                    //affiliate end
                    
                    //saving billing address//
                    $customer  = Tools_ShoppingCart::getInstance()->getCustomer();
                    $addressType = Models_Model_Customer::ADDRESS_TYPE_BILLING;
                    $billingAddressArray = array();
                    $billingAddressArray['firstname'] = isset($data['firstname'])?$data['firstname']:'';
                    $billingAddressArray['lastname']  = isset($data['lastname'])?$data['lastname']:'';
                    $billingAddressArray['company']   = '';
                    $billingAddressArray['email']     = isset($data['email'])?$data['email']:'';
                    $billingAddressArray['address1']  = isset($data['billing_address1'])?$data['billing_address1']:'';
                    $billingAddressArray['address2']  = isset($data['billing_address2'])?$data['billing_address2']:'';
                    $billingAddressArray['country']   = isset($data['country'])?$data['country']:'';
                    $billingAddressArray['city']      = isset($data['city'])?$data['city']:'';
                    $billingAddressArray['state']     = isset($data['state'])?$data['state']:'';
                    $billingAddressArray['zip']       = isset($data['zip'])?$data['zip']:'';
                    $billingAddressArray['phone']     = isset($data['phone'])?$data['phone']:'';
                    $billingAddressArray['mobile']    = '';

                    $addressId = Models_Mapper_CustomerMapper::getInstance()->addAddress($customer, $billingAddressArray, $addressType);
                    $cartStorage->setAddressKey($addressType, $addressId)->save()->saveCartSession($customer);
                    //end of saving billing address//
                    
                    
                    $paypalTransactionConfig->setTxnId($infoPayment['TRANSACTIONID']);
                    $paypalTransactionConfig->setPayerId($infoPayment['PAYERID']);
                    $paypalTransactionConfig->setPayerMail($infoPayment['EMAIL']);
                    $paypalTransactionConfig->setAmount($infoPayment['AMT']);
                    $paypalTransactionConfig->setShippingAmount($cartSummary['shipping'] !== '' ? $cartSummary['shipping'] : '0');    
                    $paypalTransactionConfig->setTax($cartSummary['totalTax'] !== '' ? $cartSummary['totalTax'] : '0');
                    $paypalTransactionConfig->setCurrency($payment['CURRENCYCODE']);    
                    $pendingReason = '';
                    if(isset($infoPayment['PENDINGREASON'])){
                       $pendingReason = $infoPayment['PENDINGREASON']; 
                    }
                    $paymentStatus = $infoPayment['PAYMENTSTATUS'];
                    if($infoPayment['PAYMENTSTATUS'] == 'Completed'){
                        $paymentStatus = Models_Model_CartSession::CART_STATUS_COMPLETED;
                    }
                    if($infoPayment['PAYMENTSTATUS'] == 'Pending'){
                        $paymentStatus = Models_Model_CartSession::CART_STATUS_PENDING;
                    }
                    $paypalTransactionConfig->setPaymentStatus($paymentStatus);
                    $paypalTransactionConfig->setStatus($infoPayment['ACK']);
                    $paypalTransactionConfig->setPaymentType('creditcard');
                    $paypalTransactionConfig->setPaymentId($cartId);
                    $paypalTransactionConfig->setPaymentDate($infoPayment['ORDERTIME']);
                    $paypalTransactionConfig->setPFirstName($data['firstname']);
                    $paypalTransactionConfig->setPLastName($data['lastname']);
                    $paypalTransactionConfig->setPCountry(isset($infoPayment['SHIPTOCOUNTRYNAME'])?$infoPayment['SHIPTOCOUNTRYNAME']:'');
                    $paypalTransactionConfig->setPCountryCode(isset($infoPayment['COUNTRYCODE'])?($infoPayment['COUNTRYCODE']):'');
                    $paypalTransactionConfig->setPAddressState(isset($infoPayment['SHIPTOSTATE'])?$infoPayment['SHIPTOSTATE']:'');
                    $paypalTransactionConfig->setPAddressCity(isset($infoPayment['SHIPTOCITY'])?$infoPayment['SHIPTOCITY']:'');
                    $paypalTransactionConfig->setPAddressZip(isset($infoPayment['SHIPTOZIP'])?$infoPayment['SHIPTOZIP']:'');   
                    $paypalTransactionConfig->setPAddressName(isset($infoPayment['SHIPTOSTREET'])?$infoPayment['SHIPTOSTREET']:'');
                    $paypalTransactionConfig->setCartId($cartId);
                    $paypalTransactionConfig->setPendingReason($pendingReason);
                    $paypalTransactionMapper->save($paypalTransactionConfig); 
                    $this->updateCartStatus($cartId, $paymentStatus);
                    echo json_encode(array('done' => true)); return;
                }
                echo json_encode(array('done' => false)); return;
            }
            $this->updateCartStatus($cartId, Models_Model_CartSession::CART_STATUS_ERROR);
            $paypalError = $payment['L_SHORTMESSAGE0'];
            if($payment['L_LONGMESSAGE0'] !=''){
                $paypalError = $payment['L_LONGMESSAGE0'];
            }
            echo json_encode(array('done' => false, 'errorText'=>$paypalError)); return;
        }
    }

    /**
     * Call to api
     *
     * @param string $methodName
     * @param string $nvpStr
     * @return array
     */
    public function hashCall($methodName, $nvpStr){
        $paypalConfigMapper = Paypal_Models_Mapper_PaypalConfigMapper::getInstance();
        $paypalSettings = $paypalConfigMapper->selectSettings();
        $useSandBox = $paypalSettings[0]->getUseSandbox();
        $apiSignature = $paypalSettings[0]->getApiSignature();
        $userApiName  = $paypalSettings[0]->getApiUser();  
        $userApiPass  = $paypalSettings[0]->getApiPassword();
		if($useSandBox == 1) {
			$endPoint = $this->creditCardSandBoxEndPoin;
		}
		else {
			$endPoint = $this->creditCardLiveEndPoin;
		}
        $coreData = array (
            'METHOD'        =>  $methodName,
            'VERSION'       =>  $this->API_VERSOIN,
            'PWD'           =>  $userApiPass,
            'USER'          =>  $userApiName,
            'SIGNATURE'     =>  $apiSignature
        );
        $nvpRequest = http_build_query($coreData) . $nvpStr;
        $responce = $this->getTransactionValidation($nvpRequest, $endPoint);
		return $this->parseResponse($responce);
	}

    /**
     * Validate transaction
     *
     * @param array $postdata
     * @param string $endPoint
     * @return mixed
     */
    public function getTransactionValidation($postdata,$endPoint) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $endPoint);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_VERBOSE, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
		$response = curl_exec($curl);
		curl_close ($curl);
		return $response;
	}

    /**
     * Parse response from gateway
     *
     * @param $response
     * @return array
     */
    public function parseResponse($response){
        $data = array();
        $tmp  = explode('&', $response);
        foreach ($tmp as $item) {
            $val = explode('=', $item);
            if (isset($val[0]) && isset($val[1])) {
                $data[$val[0]] = urldecode($val[1]);
            }
        }
        return $data;
    }

    /**
     * Direct payment call
     *
     * @param array $data
     * @return array
     */
    public function createDoDirectPayment(array $data)
    {
        $stringRequest = '&'.http_build_query($data);
        return $this->hashCall('DoDirectPayment', $stringRequest);
    }

    /**
     * Get transaction details
     *
     * @param integer $transactionId
     * @return array
     */
    public function getTransactionDetails($transactionId)
    {
        $stringRequest = '&TRANSACTIONID='.urlencode($transactionId);
        return $this->hashCall('GetTransactionDetails', $stringRequest);
    }

    /**
     * Config action
     */
    public function configAction(){
        if(Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_PLUGINS)){
            $paypalConfigMapper = Paypal_Models_Mapper_PaypalConfigMapper::getInstance();
            $paypalModelConfig  = new Paypal_Models_Models_PaypalConfigModel();
            if($this->_request->isPost()){
                $secureToken = $this->_request->getParam(Tools_System_Tools::CSRF_SECURE_TOKEN, false);
                $tokenValid = Tools_System_Tools::validateToken($secureToken, self::PAYPAL_SECURE_TOKEN);
                if (!$tokenValid) {
                    $this->_responseHelper->fail('');
                }
                $apiPassword = filter_var($this->_request->getParam('password'), FILTER_SANITIZE_STRING);
                if (empty($apiPassword)) {
                    $paypalSettings = $paypalConfigMapper->selectSettings();
                    $apiPassword = $paypalSettings[0]->getApiPassword();
                }
                $email = $this->_request->getParam('email');
                $apiSignature = $this->_request->getParam('apiSignature');
                $apiUser = $this->_request->getParam('user');
                $useSandBox = $this->_request->getParam('useSandBox');
                $paypalModelConfig->setEmail($email);
                $paypalModelConfig->setApiSignature($apiSignature);
                $paypalModelConfig->setApiUser($apiUser);
                $paypalModelConfig->setApiPassword($apiPassword);
                $paypalModelConfig->setUseSandbox($useSandBox);
                
                
                //writeLog($paypalModelConfig);
                
                $paypalConfigMapper->save($paypalModelConfig);
                $this->_responseHelper->success('');
            }
            else{
                $this->_view->translator = $this->_translator;
                $paypalSettings = $paypalConfigMapper->selectSettings();
                $this->_view->email = $paypalSettings[0]->getEmail();
                $this->_view->apiSignature = $paypalSettings[0]->getApiSignature();
                $this->_view->apiUser = $paypalSettings[0]->getApiUser();
                $this->_view->apiPassword = $paypalSettings[0]->getApiPassword();
                $this->_view->useSandBox = $paypalSettings[0]->getUseSandbox();
                $this->_layout->content = $this->_view->render('paypalConfig.phtml');
                echo $this->_layout->render();
                              
            }
                       
        }
    }

    public function refund($orderId, $refundAmount, $refundNotes)
    {
        $payResult = array('error' => 1);
        $this->_view->translator = $this->_translator;
        $transactionMapper = Paypal_Models_Mapper_PaypalTransactionMapper::getInstance();
        $transForRefund = $transactionMapper->findByCartId($orderId);
        if (empty($transForRefund) || !$transForRefund[0] instanceof Paypal_Models_Models_PaypalTransactionModel) {
            $payResult['errorMessage'] = $this->_translator->translate("There is no order with such ID");
            return $payResult;
        }
        $transForRefund = $transForRefund[0];

        $dataForPayment = array(
            'TRANSACTIONID' => $transForRefund->getTxnId(),
            'CURRENCYCODE' => $transForRefund->getCurrency(),
            'NOTE' => $refundNotes
        );


        $realRefundAmount = $transForRefund->getAmount();
        if ($realRefundAmount > $refundAmount) {
            $dataForPayment['REFUNDTYPE'] = 'Partial';
            $dataForPayment['AMT'] = $refundAmount;
        } else {
            $dataForPayment['REFUNDTYPE'] = 'Full';
        }

        $stringRequest = '&'.http_build_query($dataForPayment);
        $refundPayment = $this->hashCall('RefundTransaction', $stringRequest);

        if($refundPayment['ACK'] == "Success") {
            $transForRefund->setRefundTransactionId($refundPayment['REFUNDTRANSACTIONID']);
            $transForRefund->setStatus(Models_Model_CartSession::CART_STATUS_REFUNDED);
            $transForRefund->setRefundReason($refundNotes);
            $transactionMapper->save($transForRefund);
            $this->updateCartStatus($orderId, Models_Model_CartSession::CART_STATUS_REFUNDED);
            $payResult['error'] = 0;
            $payResult['refundMessage'] = $refundPayment['ACK'];
        }
        if ($refundPayment['ACK'] == "Failure") {
            $payResult['errorMessage'] = $refundPayment['L_LONGMESSAGE0'];

        }
        return $payResult;
    }


}
