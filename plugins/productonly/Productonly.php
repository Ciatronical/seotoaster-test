<?php
class Productonly extends Tools_Plugins_Abstract {

    const PREFIX     = 'productonly_';

    /**
     * secure token
     */
    const PRODUCTONLY_SECURE_TOKEN = 'ProductonlyToken';

    private $_sortBy = array(
        'name'  => 'Name',
        'price' => 'Price',
        'brand' => 'Brand',
        'date'  => 'Date'
    );
    
	public function  __construct($options, $seotoasterData) {
		parent::__construct($options, $seotoasterData);

        $this->_layout = new Zend_Layout();
        $this->_layout->setLayoutPath(Zend_Layout::getMvcInstance()->getLayoutPath());

        if(($scriptPaths = Zend_Layout::getMvcInstance()->getView()->getScriptPaths()) !== false) {
            $this->_view->setScriptPath($scriptPaths);
        }
        $this->_view->addScriptPath(__DIR__ . '/views/');
    }

	public function run($requestedParams = array()) {
        $this->_requestedParams = $requestedParams;
		parent::run($requestedParams);

        return $this->_renderProductonly();
    }

    protected function _renderProductonly() {
        $this->_view->AdminLogged = Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_CONTENT);
        if (isset($this->_options[0])) {
            $this->_view->pageUrl       = $this->_seotoasterData['websiteUrl'].$this->_seotoasterData['url'];
            $this->_view->pageId        = $this->_seotoasterData['id'];
            $this->_view->containerName = self::PREFIX.$this->_options[0];

            if (isset($this->_options[1]) && $this->_options[1]=='static') {
                $containerType = Application_Model_Models_Container::TYPE_STATICCONTENT;
                $pageId = null;
            }
            else {
                $containerType = Application_Model_Models_Container::TYPE_REGULARCONTENT;
                $pageId = $this->_seotoasterData['id'];
            }
            $this->_view->containerType = $containerType;

            $productsList = Application_Model_Mappers_ContainerMapper::getInstance()->findByName(
                self::PREFIX.$this->_options[0],
                $pageId,
                $containerType
            );
            $this->_view->productsList = (!empty($productsList)) ? $productsList->getContent() : '';

            return $this->_view->render('productPrepare.phtml');
        }
    }
    
    public function editProductListAction() {
       if (Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_CONTENT)) {
           $containerName = $this->_request->getParam('containerName');
           $containerType = $this->_request->getParam('containerType');
           $pageId        = ($containerType == Application_Model_Models_Container::TYPE_STATICCONTENT) ? null : $this->_request->getParam('pageId');
           $content       = Application_Model_Mappers_ContainerMapper::getInstance()->findByName($containerName, $pageId, $containerType);

           $this->_view->content          = (!empty($content)) ? $content->getContent() : '';
           $this->_view->choiceProducts   = Tools_Plugins_Tools::getPluginTabContent();
           $this->_view->websiteUrl       = $this->_seotoasterData['websiteUrl'];
           $this->_view->containerName    = $containerName;
           $this->_view->containerType    = $containerType;
           $this->_view->pageId           = $pageId;
           $this->_view->sortBy           = $this->_sortBy;
           $this->_view->productTemplates = Application_Model_Mappers_TemplateMapper::getInstance()->findByType(Application_Model_Models_Template::TYPE_LISTING);

           $this->_layout->content = $this->_view->render('editProduct.phtml');
           echo $this->_layout->render();
       }
    }

    public function saveContentAction() {
        if (!Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_CONTENT)) { //ADMINPANEL
            return $this->_responseHelper->success($this->_translator->translate('Your session has expired. Please log in again.'));
        }

        $secureToken = $this->_request->getParam(Tools_System_Tools::CSRF_SECURE_TOKEN, false);
        $tokenValid = Tools_System_Tools::validateToken($secureToken, self::PRODUCTONLY_SECURE_TOKEN);
        if (!$tokenValid) {
            $this->_responseHelper->fail('');
        }

        $data         = $this->_request->getParams();
        $pageId       = ($data['containerType'] == Application_Model_Models_Container::TYPE_STATICCONTENT) ? null : $data['pageId'];
        $mapper       = Application_Model_Mappers_ContainerMapper::getInstance();
        $productsList = $mapper->findByName($data['containerName'], $pageId, $data['containerType']);
        $container    = new Application_Model_Models_Container();

        if (!empty($productsList)) {
            $container->setId($productsList->getId());
        }
        $container->setName($data['containerName'])
            ->setContainerType($data['containerType'])
            ->setPageId($pageId)
            ->setContent($data['content']);

        $mapper->save($container);
        return $this->_responseHelper->success($this->_translator->translate('Save'));
    }
}
