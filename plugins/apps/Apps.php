<?php


class Apps extends Tools_Plugins_Abstract {

    const SERVICE_TYPE_EMAIL = 'email';

    const SERVICE_TYPE_SMS = 'sms';

    const SERVICE_TYPE_CRM = 'crm';

    const SECTION_APPS = 'socialposter';

    const SMS_FROM_TYPE_FORM = 'form';

    const SMS_FROM_TYPE_NEW_ORDER = 'newOrder';

    const SMS_FROM_TYPE_TRACKING_NUMBER = 'trackingNumber';

    /**
     * Status when sms sent to admin
     */
    const SMS_OWNER_TYPE_ADMIN = 'admin';

    /**
     * Status when sms sent to the end user
     */
    const SMS_OWNER_TYPE_USER = 'user';

    /**
     * secure token
     */
    const APPS_SECURE_TOKEN = 'AppsToken';

    private $_helpHashMap  = array(
        self::SECTION_APPS => 'cms-applications.html'
    );

    protected $_securedActions = array(
        Tools_Security_Acl::ROLE_SUPERADMIN => array(
            'secured'
        )
    );

    /**
     * layout
     * @var null
     */
    protected $_layout = null;

    private $_websiteConfig = '';

    private $_categoryAlias = array('email' => 'Email marketing', 'sms' => 'SMS and phone services', 'crm' => 'CRM');

    /**
     * Init method.
     *
     * Use this method to init your plugin's data and variables
     * Use this method to init specific helpers, view, etc...
     */
    protected function _init() {
        $this->_websiteConfig = Zend_Controller_Action_HelperBroker::getStaticHelper('config')->getConfig();

        $this->_layout = new Zend_Layout();
        $this->_layout->setLayoutPath(Zend_Layout::getMvcInstance()->getLayoutPath());

        if(($scriptPaths = Zend_Layout::getMvcInstance()->getView()->getScriptPaths()) !== false) {
            $this->_view->setScriptPath($scriptPaths);
        }
        $this->_view->addScriptPath(__DIR__ . '/system/views/');
    }

    public function appsConfigAction(){
        if(Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_PLUGINS)) {
            $appsSettingsMapper = Apps_Models_Mapper_AppsSettingsMapper::getInstance();
            if($this->_request->isPost()){
                $secureToken = $this->_request->getParam(Tools_System_Tools::CSRF_SECURE_TOKEN, false);
                $tokenValid = Tools_System_Tools::validateToken($secureToken, self::APPS_SECURE_TOKEN);
                if (!$tokenValid) {
                    $this->_responseHelper->fail('');
                }
                $data = $this->_request->getParams();
                $appsSettingsModel = new Apps_Models_Models_AppsSettingsModel();
                if(isset($data['service'])){
                    $status = ($data['status'] === 'enable') ? 0 : 1;
                    $appsSettingsModel->setServiceName($data['service']);
                    $appsSettingsModel->setStatus($status);
                    $appsSettingsModel->setCategory($data['category']);
                    $appsSettingsMapper->save($appsSettingsModel);
                    $this->_responseHelper->success($this->_translator->translate('Changed'));
                }
                $this->_responseHelper->fail();
            }else{
                $this->_view->localServices = $appsSettingsMapper->selectConfig();
                $categoryAndServices = Apps::apiCall('GET', 'apps', array('services'), array('withcrm' => 'withcrm'));
                if(isset($categoryAndServices['done']) && $categoryAndServices['done'] === false) {
                    $categoryAndServices = null;
                }
                if($categoryAndServices !== null) {
                    if(isset($categoryAndServices['services']['categoryInfo'])){
                        $this->_view->categoryInfo = $categoryAndServices['services']['categoryInfo'];
                        unset($categoryAndServices['services']['categoryInfo']);
                    }
                    $categoryServicesInfo = array();
                    foreach ($categoryAndServices['services'] as $category => $services) {
                        foreach ($services as $serviceName => $serviceData) {
                            $categoryServicesInfo[$serviceName] = $category;
                        }
                    }

                    $pluginShopping = Application_Model_Mappers_PluginMapper::getInstance()->findByName('shopping');
                    if ($pluginShopping instanceof Application_Model_Models_Plugin) {
                        $this->_view->eccomerceWebsite = true;
                    }

                    $this->_view->categoryServicesInfo = $categoryServicesInfo;
                    $this->_view->categoryAndServices = $categoryAndServices;
                    $this->_view->categoryAlias = $this->_categoryAlias;
                    $allForms = Application_Model_Mappers_FormMapper::getInstance()->fetchAll();
                    $this->_view->forms = $allForms;
                    $this->_view->emailFormsView = $this->_view->render('emailForm.phtml');
                }

            }
            $this->_view->helpSection = self::SECTION_APPS;
            $this->_view->hashMap     = $this->_helpHashMap;
            $this->_layout->content = $this->_view->render('config.phtml');
            echo $this->_layout->render();

        }
    }

    public function chooseFormListAction(){
        if(Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_PLUGINS) && $this->_request->isPost()) {
            $formName = $this->_request->getParam('checkedForm');
            $service  = $this->_request->getParam('serviceName');
            $serviceType  = $this->_request->getParam('serviceType');
            $existingForms = Apps_Models_Mapper_AppsSystemFormMapper::getInstance()->getByFormNameService($formName, $service);
            $localLists = array();
            $lists = array();
            $additionalLists = array();
            $additionalLocalList = '';
            if(!empty($existingForms)){
                $localLists = explode(',', $existingForms[0]->getLists());
                $additionalLocalList =  $existingForms[0]->getAdditionalList();
            }
            if(isset($service)){
                $contactLists = Apps::apiCall('GET', 'apps', array($service.'List'));
                if(isset($contactLists[$service.'List']) && is_array($contactLists[$service.'List'])){
                    foreach($contactLists[$service.'List'] as $list){
                        $lists[$list['id']] = $list['name'];
                    }
                    $this->_view->lists = $lists;
                }else{
                    $this->_responseHelper->fail();
                }
                if ($serviceType === self::SERVICE_TYPE_CRM) {
                    $additionalLists = Apps::apiCall('GET', 'apps', array($service.'AdditionalList'));
                    if (!empty($additionalLists)) {
                        $additionalListsData = array();
                        foreach ($additionalLists[$service . 'AdditionalList'] as $addList) {
                            foreach ($addList as $listName => $listData) {
                                foreach ($listData as $dataValues) {
                                    $additionalListsData[$dataValues['id']] = $dataValues['name'];
                                }
                            }
                        }
                        $additionalLists = $additionalListsData;
                    }
                }
            }
            if (empty($additionalLists)) {
                $this->_responseHelper->success(array('localLists' => $localLists, 'list' => $lists));
            }
            $this->_responseHelper->success(array('localLists'=> $localLists, 'list' => $lists, 'additionalSelectionList' => $additionalLists, 'additionalLocalList' => $additionalLocalList));
        }
    }

    public function getListCrmAction()
    {
        if (Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_PLUGINS) && $this->_request->isPost()) {
            $service  = $this->_request->getParam('serviceName');
            $existingConfig = Apps_Models_Mapper_AppsCrmMapper::getInstance()->getByDataTypeService('ecommerce', $service);
            $localLists = array();
            $lists = array();
            $additionalLists = array();
            $additionalLocalList = '';
            if(!empty($existingConfig)){
                $localLists = explode(',', $existingConfig[0]->getLists());
                $additionalLocalList =  $existingConfig[0]->getAdditionalList();
            }
            if(isset($service)){
                $contactLists = Apps::apiCall('GET', 'apps', array($service.'ListEcommerce'));
                if(isset($contactLists[$service.'ListEcommerce']) && is_array($contactLists[$service.'ListEcommerce'])){
                    foreach($contactLists[$service.'ListEcommerce'] as $list){
                        $lists[$list['id']] = $list['name'];
                    }
                    $this->_view->lists = $lists;
                }else{
                    $this->_responseHelper->fail();
                }

                $additionalLists = Apps::apiCall('GET', 'apps', array($service.'AdditionalListEcommerce'));
                if (!empty($additionalLists)) {
                    $additionalListsData = array();
                    foreach ($additionalLists[$service . 'AdditionalListEcommerce'] as $addList) {
                        foreach ($addList as $listName => $listData) {
                            foreach ($listData as $dataValues) {
                                $additionalListsData[$dataValues['id']] = $dataValues['name'];
                            }
                        }
                    }
                    $additionalLists = $additionalListsData;
                }
            }
            if (empty($additionalLists)) {
                $this->_responseHelper->success(array('localLists'=> $localLists, 'list' => $lists));
            }
            $this->_responseHelper->success(array('localLists'=> $localLists, 'list' => $lists, 'additionalSelectionList' => $additionalLists, 'additionalLocalList' => $additionalLocalList));
        }
    }

    /**
     * Save crm ecommerce list config
     */
    public function saveCrmEcommerceAction()
    {
        if (Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_PLUGINS) && $this->_request->isPost()) {
            $secureToken = $this->_request->getParam(Tools_System_Tools::CSRF_SECURE_TOKEN, false);
            $tokenValid = Tools_System_Tools::validateToken($secureToken, self::APPS_SECURE_TOKEN);
            if (!$tokenValid) {
                $this->_responseHelper->fail('');
            }
            $data = $this->_request->getParams();
            $crmConfigMapper = Apps_Models_Mapper_AppsCrmMapper::getInstance();
            if (isset($data['lists']) && !empty($data['lists'])) {
                $appsCrmConfigModel = new Apps_Models_Models_AppsCrmModel();
                $appsCrmConfigModel->setDataType('ecommerce');
                $appsCrmConfigModel->setLists(implode(',', array_values($data['lists'])));
                $appsCrmConfigModel->setAdditionalList($data['additionalSelectValue']);
                $appsCrmConfigModel->setService($data['service']);
                $crmConfigMapper->save($appsCrmConfigModel);
            } elseif (isset($data['deleteList']) && isset($data['service']) && $data['deleteList'] == 1) {
                $crmConfigMapper->deleteServiceConfig($data['service'], 'ecommerce');
            } else {
                $this->_responseHelper->fail();
            }
            $this->_responseHelper->success('');
        }
    }

    public function saveSystemFormAction(){
        if(Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_PLUGINS) && $this->_request->isPost()) {
            $secureToken = $this->_request->getParam(Tools_System_Tools::CSRF_SECURE_TOKEN, false);
            $tokenValid = Tools_System_Tools::validateToken($secureToken, self::APPS_SECURE_TOKEN);
            if (!$tokenValid) {
                $this->_responseHelper->fail('');
            }
            $data = $this->_request->getParams();
            $appsSystemFormsMapper = Apps_Models_Mapper_AppsSystemFormMapper::getInstance();
            if(!empty($data['lists'])){
                $appsSystemFormsModel  = new Apps_Models_Models_AppsSystemFormModel();
                $appsSystemFormsModel->setForm($data['formName']);
                $appsSystemFormsModel->setLists(implode(',',array_values($data['lists'])));
                $appsSystemFormsModel->setService($data['service']);
                if (isset($data['additionalSelectionId'])) {
                    $appsSystemFormsModel->setAdditionalList($data['additionalSelectionId']);
                }
                $appsSystemFormsMapper->save($appsSystemFormsModel);
            }elseif(isset($data['deleteList']) && isset($data['service']) && $data['formName'] && $data['deleteList'] == 1 ){
                $appsSystemFormsMapper->deleteList($data['service'], $data['formName']);
            }else{
                $this->_responseHelper->fail();
            }
            $this->_responseHelper->success('');
        }
    }

    public function getEnabledServicesDashboardAction(){
        if(Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_PLUGINS)) {
            $data = $this->_request->getParams();
            $enabledServices = Apps_Models_Mapper_AppsSettingsMapper::getInstance()->selectConfigByStatusCategory('1', $data['serviceType']);
            if(!empty($enabledServices)){
                $this->_responseHelper->success(array('clients' => $data['customers'], 'enabledServices' => $enabledServices));
            }
            $this->_responseHelper->fail();
        }
    }

    public function getServiceAction(){
        if(Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_PLUGINS)) {
            $data = $this->_request->getParams();
            $lists = array();
            if(isset($data['serviceName'])){
                $services = Apps::apiCall('GET', 'apps', array($data['serviceName'].'List'));
                if(isset($services[$data['serviceName'].'List'])){
                    foreach($services[$data['serviceName'].'List'] as $list){
                        $lists[$list['id']] = $list['name'];
                    }
                    $this->_responseHelper->success(array('list' =>$lists));
                }
                $this->_responseHelper->fail();
            }
            $this->_responseHelper->fail();
        }
    }

    /**
     * get list of services for dashboard
     */
    public function getServiceDashboardAction()
    {
        if (Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_PLUGINS)) {
            $data = $this->_request->getParams();
            $serviceInfo = Apps_Models_Mapper_AppsSettingsMapper::getInstance()->getByServiceName($data['serviceName']);
            $serviceType = $serviceInfo[0]->getCategory();
            $lists = array();
            if (isset($data['serviceName'])) {
                if ($serviceType === self::SERVICE_TYPE_CRM) {
                    $services = Apps::apiCall('GET', 'apps', array($data['serviceName'] . 'ListDashboard'));
                }

                if (isset($services[$data['serviceName'] . 'ListDashboard'])) {
                    foreach ($services[$data['serviceName'] . 'ListDashboard'] as $list) {
                        $lists[$list['id']] = $list['name'];
                    }
                    $this->_responseHelper->success(array('list' => $lists));
                }
                $this->_responseHelper->fail();
            }
            $this->_responseHelper->fail();
        }
    }

    public function sendServicesDashboardAction(){
        if(Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_PLUGINS) && $this->_request->isPost()) {
            $serviceData = $this->_request->getParams();
            if(isset($serviceData['customers']) && isset($serviceData['service']) && isset($serviceData['lists'])){
                $serviceInfo = Apps_Models_Mapper_AppsSettingsMapper::getInstance()->getByServiceName($serviceData['service']);
                $serviceType = $serviceInfo[0]->getCategory();
                $data['services'] = array($serviceData['service'] => array('type' => $serviceType));
                $userDbtable = new Application_Model_DbTable_User();
                if (($serviceType === Apps::SERVICE_TYPE_EMAIL || $serviceType === Apps::SERVICE_TYPE_CRM) && $serviceData['service'] !== 'salesForce') {
                    $where = $userDbtable->getAdapter()->quoteInto('id IN (?)',
                        explode(',', $serviceData['customers']));
                    $select = $userDbtable->getAdapter()->select()->from('user',
                        array('email', 'full_name'))->where($where);
                    $users = $userDbtable->getAdapter()->fetchPairs($select);
                } elseif ($serviceType === Apps::SERVICE_TYPE_CRM && $serviceData['service'] === 'salesForce') {
                    $where = $userDbtable->getAdapter()->quoteInto('u.id IN (?)',
                        explode(',', $serviceData['customers']));
                    $where .= ' AND `scs`.`shipping_address_id` IS NOT NULL AND `scs`.`billing_address_id` IS NOT NULL';
                    $select = $userDbtable->getAdapter()->select()->from(array('u' => 'user'),
                        array('u.email', 'u.full_name', 'u.mobile_phone', 'scs.user_id'))
                        ->joinLeft(array('scs' => 'shopping_cart_session'), 'scs.user_id=u.id', array())
                        ->joinLeft(array('scas' => 'shopping_customer_address'), 'scas.id=scs.shipping_address_id',
                            array('shipping_first_name' => 'scas.firstname', 'shipping_address_city' => 'scas.city',
                            'shipping_address_zip' => 'scas.zip', 'shipping_address_country' => 'scas.country',
                                'shipping_address_street' => 'scas.address1', 'scs.id','scs.created_at'
                            ))
                        ->joinLeft(array('scab' => 'shopping_customer_address'), 'scab.id=scs.billing_address_id',
                            array('billing_first_name' => 'scab.firstname', 'billing_address_city' => 'scab.city',
                                'billing_address_zip' => 'scab.zip', 'billing_address_country' => 'scab.country',
                                'billing_address_street' => 'scab.address1')
                        )
                        ->where($where)
                        //->group(array('scs.user_id'))
                        ->order('scs.created_at DESC');
                    $selectFrom = $userDbtable->getAdapter()->select()->from(array('subresult' =>$select))->group('subresult.user_id');
                    $data['services'][$serviceData['service']]['fromDashboard'] = true;
                    $clientsExtended = $userDbtable->getAdapter()->fetchAssoc($selectFrom);
                    $selectFrom->assemble();
                    $where = $userDbtable->getAdapter()->quoteInto('id IN (?)',
                        explode(',', $serviceData['customers']));
                    $select = $userDbtable->getAdapter()->select()->from('user',
                        array('email', 'full_name', 'mobile_phone'))->where($where);
                    $users = $userDbtable->getAdapter()->fetchAssoc($select);
                    $data['services'][$serviceData['service']]['clientsExtended'] = $clientsExtended;
                }
                if(!empty($users)){
                    $data['services'][$serviceData['service']]['clients'] = $users;
                    $data['services'][$serviceData['service']]['lists'] = explode(',', $serviceData['lists']);
                    $response = Apps::apiCall('POST', 'apps', array($serviceData['service'].'Client'), $data);
                    if($response[$serviceData['service'].'Client']['done']){
                        $this->_responseHelper->success('');
                    }
                    $this->_responseHelper->fail($response[$serviceData['service'].'Client']['message']);

                }
            }
        }
    }

    /**
     * Call mojo.seosamba.com api
     *
     * @param string $methodType method type (GET, POST, PUT, DELETE)
     * @param string $methodName method name (apps)
     * @param array $command command list for mojo api
     * @param array $data data
     * @return mixed
     * @throws Exceptions_SeotoasterPluginException
     */
    public static function apiCall($methodType, $methodName, $command, $data = array()) {
        $websiteConfig = Zend_Controller_Action_HelperBroker::getStaticHelper('config')->getConfig();
        $websiteHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('website');
        $websiteUrl = $websiteHelper->getUrl();
        if(isset($websiteConfig['sambaToken']) && (isset($websiteConfig['websiteId'])) ) {
            $url = parse_url($websiteUrl);
            $data['websiteUrl'] = $url['host'];
            $data['websiteId'] = $websiteConfig['websiteId'];
            $data['sambaToken'] = $websiteConfig['sambaToken'];
            $data['command'] = $command;
            $seosambaRequest = Tools_Factory_PluginFactory::createPlugin('api',array(), array('websiteUrl' => $websiteUrl));
            return $seosambaRequest::request($methodType, $methodName, $data);
        }
    }

}
