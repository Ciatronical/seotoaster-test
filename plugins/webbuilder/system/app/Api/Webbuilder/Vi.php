<?php
/**
 * Webbuilder videolink API
 *
 */

class Api_Webbuilder_Vi extends Api_Service_Abstract {

    protected $_accessList  = array(
        Tools_Security_Acl::ROLE_USER       => array('allow' => array('get', 'post', 'put', 'delete')),
        Tools_Security_Acl::ROLE_SUPERADMIN => array('allow' => array('get', 'post', 'put', 'delete')),
        Tools_Security_Acl::ROLE_ADMIN      => array('allow' => array('get', 'post', 'put', 'delete'))
    );

    public function init() {
        $this->_websiteHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('website');
        $this->_mapper        = Application_Model_Mappers_ContainerMapper::getInstance();
    }

    public function postAction() {
        $viData = array(
            'link'   => filter_var($this->_request->getParam('link'), FILTER_SANITIZE_STRING),
            'width'  => filter_var($this->_request->getParam('width'), FILTER_SANITIZE_STRING),
            'height' => filter_var($this->_request->getParam('height'), FILTER_SANITIZE_STRING)
        );

        $tokenToValidate = $this->_request->getParam(Tools_System_Tools::CSRF_SECURE_TOKEN, false);
        $valid = Tools_System_Tools::validateToken($tokenToValidate, Webbuilder::WB_VIDEOLINK_SECURE_TOKEN);
        if (!$valid) {
            exit;
        }

        $containerName = filter_var($this->_request->getParam('container'), FILTER_SANITIZE_STRING);
        $pageId        = filter_var($this->_request->getParam('pageId'), FILTER_SANITIZE_NUMBER_INT);
        $type          = ($pageId) ? Application_Model_Models_Container::TYPE_REGULARCONTENT : Application_Model_Models_Container::TYPE_STATICCONTENT;
        $container      = $this->_mapper->findByName($containerName, $pageId, $type);

        if($pageId == 0) {
            $pageId = null;
        }

        if(!$container instanceof Application_Model_Models_Container) {
            $container = new Application_Model_Models_Container();
            $container->setName($containerName)
                ->setPageId($pageId)
                ->setContainerType(($pageId) ? Application_Model_Models_Container::TYPE_REGULARCONTENT : Application_Model_Models_Container::TYPE_STATICCONTENT);
        }
        $container->setContent(Zend_Json::encode($viData));
        try {
            return array('error' => false, 'responseText' =>  $this->_mapper->save($container));
        } catch (Exception $e) {
            return $this->_error($e->getMessage());
        }
    }

    public function getAction() {}
    public function putAction() {}
    public function deleteAction() {}


}