<?php

class Tools_CrmServiceFormWatchdog implements Interfaces_Observer
{

    public function notify($object)
    {
        $requestHelper = Zend_Controller_Front::getInstance()->getRequest();
        $formParams = $requestHelper->getParams();
        if (!empty($formParams) && isset($formParams['crmContact'])) {
            $formName = $object->getName();
            $formId = $object->getId();
            $enabledServices = Apps_Models_Mapper_AppsSettingsMapper::getInstance()->selectConfigByStatusCategory('1',
                Apps::SERVICE_TYPE_CRM);
            $commands = array();
            $data = array();
            if (!empty($enabledServices)) {
                foreach ($enabledServices as $service => $serviceInfo) {
                    $systemFormMapper = Apps_Models_Mapper_AppsSystemFormMapper::getInstance();
                    $existingForm = $systemFormMapper->getByFormNameService($formName, $service);
                    if (!empty($existingForm)) {
                        $data['services'][$service]['type'] = Apps::SERVICE_TYPE_CRM;
                        $data['services'][$service]['clients'] = array($formParams['email'] => '');
                        if (isset($formParams['name'])) {
                            $data['services'][$service]['clients'][$formParams['email']] = $formParams['name'];
                        }
                        if (isset($formParams['lastname']) && isset($formParams['name'])) {
                            $data['services'][$service]['clients'][$formParams['email']] = $formParams['name'] . ' ' . $formParams['lastname'];
                        }
                        $data['services'][$service]['lists'] = explode(',', $existingForm[0]->getLists());
                        $data['services'][$service]['additionalList'] = $existingForm[0]->getAdditionalList();
                        unset($formParams[md5($formName.$formId)]);
                        foreach ($formParams as $paramName => $paramValue) {
                            $data['services'][$service]['customParams'][$paramName] = $paramValue;
                        }
                        $commands[] = $service . 'Client';

                    }
                }
                if (!empty($existingForm)) {
                    Apps::apiCall('POST', 'apps', $commands, $data);
                }
            }
        }
    }


}

