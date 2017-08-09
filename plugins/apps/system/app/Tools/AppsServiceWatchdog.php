<?php

class Tools_AppsServiceWatchdog implements Interfaces_Observer
{

    public function notify($object)
    {
        if ($object instanceof Models_Model_CartSession) {
            $userId = $object->getUserId();
            $crmConfigMapper = Apps_Models_Mapper_AppsCrmMapper::getInstance();
            $enabledServices = Apps_Models_Mapper_AppsSettingsMapper::getInstance()->selectConfigByStatusCategory('1',
                Apps::SERVICE_TYPE_CRM);
            $commands = array();
            $data = array();
            $websiteHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('website');
            $websiteUrl = $websiteHelper->getUrl();
            $userDbtable = new Application_Model_DbTable_User();
            $where = $userDbtable->getAdapter()->quoteInto('id = ?', $userId);
            $select = $userDbtable->getAdapter()->select()->from('user', array('email', 'full_name'))->where($where);
            $user = $userDbtable->getAdapter()->fetchPairs($select);
            $userModel = Application_Model_Mappers_UserMapper::getInstance()->find($userId);
            if (!empty($enabledServices) && !empty($user)) {
                $billingAddressId = $object->getBillingAddressId();
                $shippingAddressId = $object->getShippingAddressId();
                $customerMapper = Models_Mapper_CustomerMapper::getInstance();
                $billingAddress = $customerMapper->getUserAddressByUserId($userId, $billingAddressId);
                $shippingAddress = $customerMapper->getUserAddressByUserId($userId, $shippingAddressId);
                $cartId = $object->getId();
                $total = $object->getTotal();
                $createdAt = $object->getCreatedAt();
                $userEmail = $userModel->getEmail();
                $userFullName = $userModel->getFullName();
                $mobilePhone = $userModel->getMobilePhone();
                foreach ($enabledServices as $service => $serviceInfo) {
                    $existingServiceConfig = $crmConfigMapper->getByDataTypeService('ecommerce', $service);
                    if (!empty($existingServiceConfig)) {
                        foreach ($existingServiceConfig as $existingService) {
                            $data['services'][$service]['type'] = Apps::SERVICE_TYPE_CRM;
                            $data['services'][$service]['clients'] = array($userEmail => $userFullName);
                            $data['services'][$service]['lists'] = explode(',', $existingService->getLists());
                            $data['services'][$service]['additionalList'] = $existingService->getAdditionalList();
                            $data['services'][$service]['purchaseInfo'] = array('full_name' => $userFullName,
                                'email' => $userEmail, 'mobile_phone' => $mobilePhone, 'billing_address_city'=>$billingAddress[$billingAddressId]['city'],
                                'billing_address_country' => $billingAddress[$billingAddressId]['country'],
                                'billing_address_street' => $billingAddress[$billingAddressId]['address1'],
                                'billing_address_zip' => $billingAddress[$billingAddressId]['zip'],
                                'shipping_address_city' =>$shippingAddress[$shippingAddressId]['city'],
                                'shipping_address_country' => $shippingAddress[$shippingAddressId]['country'],
                                'shipping_address_street' => $shippingAddress[$shippingAddressId]['address1'],
                                'shipping_address_zip' => $shippingAddress[$shippingAddressId]['zip'],
                                'amount' =>  $total,
                                'created_at' => $createdAt,
                                'cart_id' => $cartId,
                                'cart_content' => $object->getCartContent()
                            );
                            $data['services'][$service]['notes'][$userEmail] = $websiteUrl.'dashboard/orders/';

                            $commands[] = $service . 'ClientPurchase';

                        }
                    }
                }
                if (!empty($data)) {
                    Apps::apiCall('POST', 'apps', $commands, $data);
                }
            }
        }
    }


}

