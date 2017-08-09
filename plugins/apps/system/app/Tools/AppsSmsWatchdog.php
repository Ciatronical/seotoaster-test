<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seotoaster
 * Date: 1/16/14
 * Time: 6:27 PM
 * To change this template use File | Settings | File Templates.
 */

class Tools_AppsSmsWatchdog implements Interfaces_Observer {

    private $_object;

    private $_customer = null;

    public function __construct($options = array()) {
        $this->_configHelper = Zend_Controller_Action_HelperBroker::getExistingHelper('config');
        $this->_websiteHelper = Zend_Controller_Action_HelperBroker::getExistingHelper('website');
        $this->_widcard = Application_Model_Mappers_ConfigMapper::getInstance()->getConfig();
        $this->_options = $options;
        $this->_entityParser = new Tools_Content_EntityParser();
    }

    public function notify($object) {
        if (!$object || $this->_options['service'] !== Application_Model_Models_TriggerAction::SERVICE_TYPE_SMS) {
            return false;
        }

        $this->_object = $object;

        if (isset($this->_options['trigger'])){
            $methodName = str_replace('store_', '', $this->_options['trigger']);
            $methodName = '_send'.ucfirst(strtolower(preg_replace('/\s*/', '', $methodName))).'Sms';
            if (method_exists($this, $methodName)) {
                $this->_customer = Models_Mapper_CustomerMapper::getInstance()->find($this->_object->getUserId());
                $this->_entityParser
                    ->objectToDictionary($this->_customer)
                    ->objectToDictionary($this->_object, 'order');
                $this->_entityParser->addToDictionary(array('store:name' => !empty($this->_storeConfig['company']) ? $this->_storeConfig['company'] : ''));
                $this->_entityParser->addToDictionary(array('company:name' => !empty($this->_widcard['wicOrganizationName']) ? $this->_widcard['wicOrganizationName'] : ''));
                $withBillingAddress = preg_replace('~\s+~', ' ',$this->_prepareAdddress($this->_customer, $this->_object->getBillingAddressId(), Tools_StoreMailWatchdog::BILLING_TYPE));
                $withShippingAddress = preg_replace('~\s+~', ' ',$this->_prepareAdddress($this->_customer, $this->_object->getShippingAddressId(), Tools_StoreMailWatchdog::SHIPPING_TYPE));
                if(isset($withBillingAddress)){
                    $this->_entityParser->addToDictionary(array('order:billingaddress'=> $withBillingAddress));
                }
                if(isset($withShippingAddress)){
                    $this->_entityParser->addToDictionary(array('order:shippingaddress'=> $withShippingAddress));
                }

                $this->$methodName();
            }
        }

    }

    private function _sendNeworderSms()
    {
        $subscriber = array();
        switch ($this->_options['recipient']) {
            case Tools_StoreMailWatchdog::RECIPIENT_CUSTOMER:
                $phone = $this->_getPhoneNumber($this->_customer);
                if (!empty($phone)) {
                    $subscriber['sms_from_type'] = Apps::SMS_FROM_TYPE_NEW_ORDER;
                    $subscriber['subscriber'][Tools_StoreMailWatchdog::RECIPIENT_CUSTOMER] = array(
                        'phone' => $phone,
                        'message' => $this->_entityParser->parse(strip_tags($this->_options['message'])),
                        'owner_type' => Apps::SMS_OWNER_TYPE_ADMIN
                    );
                }
                break;
            case Tools_StoreMailWatchdog::RECIPIENT_ADMIN:
                $phone = Apps_Tools_Twilio::normalizePhoneNumberToE164(Models_Mapper_ShoppingConfig::getInstance()->getConfigParam('phone'));
                if (!empty($phone)) {
                    $subscriber['sms_from_type'] = Apps::SMS_FROM_TYPE_NEW_ORDER;
                    $subscriber['subscriber'][Tools_StoreMailWatchdog::RECIPIENT_ADMIN] = array(
                        'phone' => array('billing' => $phone),
                        'message' => $this->_entityParser->parse(strip_tags($this->_options['message'])),
                        'owner_type' => Apps::SMS_OWNER_TYPE_ADMIN
                    );
                }
                break;
        }
        if (empty($phone)) {
            error_log('Unsupported recipient ' . $this->_options['recipient'] . ' given');
            return false;
        }
        $this->_sendSms($subscriber);
    }

    private function _sendTrackingnumberSms() {
        $subscriber = array();
        $phoneNumbers = $this->_getPhoneNumber($this->_customer);
        if(!empty($phoneNumbers)) {
            switch ($this->_options['recipient']) {
                case Tools_StoreMailWatchdog::RECIPIENT_CUSTOMER:
                    $subscriber['sms_from_type'] = Apps::SMS_FROM_TYPE_TRACKING_NUMBER;
                    $subscriber['subscriber'][Tools_StoreMailWatchdog::RECIPIENT_CUSTOMER] = array('phone' => $phoneNumbers, 'message' => $this->_entityParser->parse(strip_tags($this->_options['message'])), 'owner_type' => Apps::SMS_OWNER_TYPE_ADMIN);
                    break;
                    error_log('Unsupported recipient '.$this->_options['recipient'].' given');
                    return false;
                    break;
            }
            $this->_sendSms($subscriber);
        }
    }

    private function _sendSms($subscriber) {
        $response = Apps::apiCall('POST', 'apps', array('twilioSms'), $subscriber);
        return $response;
    }

    private function _getPhoneNumber($customer) {
        $phoneNumbers = array();
        foreach($customer->getAddresses() as $addressData) {
            if( ($addressData['id'] === $this->_object->getShippingAddressId()) || ($addressData['id'] === $this->_object->getBillingAddressId()) ) {
                /*if(!empty($addressData['phone'])) {
                    $phone = Apps_Tools_Twilio::normalizePhoneNumberToE164($addressData['phone']);
                    if($phone !== false) {
                        $phoneNumbers[$addressData['address_type']] = $phone;
                    }
                }*/
                if(isset($addressData['mobile']) && !empty($addressData['mobile'])) {
                    $mobileCountryPhoneCode = null;
                    if(!empty($addressData['mobilecountrycode'])) {
                        $mobileCountryPhoneCode = Zend_Locale::getTranslation($addressData['mobilecountrycode'], 'phoneToTerritory');
                    }
                    $phone = Apps_Tools_Twilio::normalizePhoneNumberToE164($addressData['mobile'], $mobileCountryPhoneCode);
                    if($phone !== false) {
                        $phoneNumbers[$addressData['address_type']] = $phone;
                    }
                }
            }
        }
        return array_unique($phoneNumbers);
    }

    private function _prepareAdddress($address, $addressId, $type){
        foreach($address->getAddresses() as $addressData){
            if($addressData['id'] == $addressId){
                foreach($addressData as $el => $value){
                    $this->_entityParser->addToDictionary(array('order:'.$type.$el => $value));
                }
                if(isset($addressData['state']) && $addressData['state'] != ''){
                    $state = Tools_Geo::getStateById($addressData['state']);
                    return $addressData['firstname'].' '.$addressData['lastname'].' '.$addressData['address1'].' '.$addressData['address2'].' '.$addressData['city'].' '.$state['state'].' '.$addressData['zip'].' '.$addressData['country'];
                }
                return $addressData['firstname'].' '.$addressData['lastname'].' '.$addressData['address1'].' '.$addressData['address2'].' '.$addressData['city'].' '.$addressData['zip'].' '.$addressData['country'];
            }
        }

    }

}