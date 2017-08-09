<?php

class Tools_SmsServiceFormWatchdog implements Interfaces_Observer
{

    public function notify($object)
    {
        $requestHelper = Zend_Controller_Front::getInstance()->getRequest();
        $formParams = $requestHelper->getParams();
        if (!empty($formParams)) {
            $mobilePhoneAdmin = Apps_Tools_Twilio::normalizePhoneNumberToE164($object->getMobile());
            $subscriber = array();
            $formName = $object->getName();
            $formUrl = $formParams['formUrl'];
            unset($formParams['formUrl']);
            $subscriber['form_url'] = $formUrl;
            $subscriber['form_name'] = $formName;
            $subscriber['sms_from_type'] = Apps::SMS_FROM_TYPE_FORM;
            if ($mobilePhoneAdmin) {
                $formId = $object->getId();
                unset($formParams['module']);
                unset($formParams['controller']);
                unset($formParams['action']);
                unset($formParams['recaptcha']);
                unset($formParams[md5($formName . $formId)]);
                unset($formParams['recaptcha_challenge_field']);
                unset($formParams['recaptcha_response_field']);
                unset($formParams['formPageId']);
                unset($formParams['submit']);
                unset($formParams['uploadLimitSize']);
                unset($formParams['formName']);
                if (isset($formParams['conversionPageUrl'])) {
                    unset($formParams['conversionPageUrl']);
                }
                $messageAdmin = 'Hello you\'ve received a request from: ' . $formUrl. PHP_EOL;

                foreach ($formParams as $name => $value) {
                    if (!$value) {
                        continue;
                    }
                    $messageAdmin .= $name . ': ' . (is_array($value) ? implode(', ', $value) : $value) . PHP_EOL;
                }
                $subscriber['subscriber']['admin'] = array(
                    'phone' => array($mobilePhoneAdmin),
                    'message' => $messageAdmin,
                    'owner_type' => Apps::SMS_OWNER_TYPE_ADMIN
                );
            }
            $enabledSms = $object->getEnableSms();
            if (isset($formParams['mobile']) && $enabledSms) {
                $mobilePhoneUser = Apps_Tools_Twilio::normalizePhoneNumberToE164($formParams['mobile']);
                $messageUser = $object->getReplyText();
                if ($mobilePhoneUser) {
                    $subscriber['subscriber']['user'] = array(
                        'phone' => array($mobilePhoneUser),
                        'message' => $messageUser,
                        'owner_type' => Apps::SMS_OWNER_TYPE_USER
                    );
                }
            }
            if (!empty($subscriber)) {
                Apps::apiCall('POST', 'apps', array('twilioSms'), $subscriber);
            }
        }
    }


}

