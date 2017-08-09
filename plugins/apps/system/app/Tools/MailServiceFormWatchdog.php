<?php
    class Tools_MailServiceFormWatchdog implements Interfaces_Observer {
        
        public function notify($object){
            $requestHelper = Zend_Controller_Front::getInstance()->getRequest();
            $formParams    = $requestHelper->getParams();
            if(!empty($formParams) && isset($formParams['emailMarketing'])){
                $formName = $object->getName();
                $enabledServices = Apps_Models_Mapper_AppsSettingsMapper::getInstance()->selectConfigByStatusCategory('1', Apps::SERVICE_TYPE_EMAIL);
                $commands = array();
                if(!empty($enabledServices)){
                   foreach($enabledServices as $service => $serviceInfo){
                        $systemFormMapper = Apps_Models_Mapper_AppsSystemFormMapper::getInstance();
                        $existingForms = $systemFormMapper->getByFormNameService($formName, $service);
                        if(!empty($existingForms)){
                            $data['services'] = array($service => array('type' => Apps::SERVICE_TYPE_EMAIL));
                            $data['services'][$service]['clients'] = array($formParams['email']=>'');
                            if(isset($formParams['name'])){
                                $data['services'][$service]['clients'][$formParams['email']] =  $formParams['name'];
                            }
                            if(isset($formParams['lastname']) && isset($formParams['name'])){
                                $data['services'][$service]['clients'][$formParams['email']] =  $formParams['name'].' '.$formParams['lastname'];
                            }
                            $data['services'][$service]['lists'] = explode(',',$existingForms[0]->getLists());
                            $commands[] = $service.'Client';

                        }
                   }
                   Apps::apiCall('POST', 'apps', $commands, $data);
                }
            }
        }


        

}

