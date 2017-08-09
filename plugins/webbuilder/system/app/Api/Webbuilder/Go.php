<?php
/**
 * Webbuilder gallery only API
 *
 * @author Eugene I. Nezhuta <eugene@seotoaster.com>
 * User: Eugene I. Nezhuta <eugene@seotoaster.com>
 * Date: 4/23/13
 * Time: 12:34 PM
 */

class Api_Webbuilder_Go extends Api_Service_Abstract {

    protected $_accessList  = array(
        Tools_Security_Acl::ROLE_USER       => array('allow' => array('get', 'post', 'put', 'delete')),
        Tools_Security_Acl::ROLE_SUPERADMIN => array('allow' => array('get', 'post', 'put', 'delete')),
        Tools_Security_Acl::ROLE_ADMIN      => array('allow' => array('get', 'post', 'put', 'delete'))
    );

    public function postAction() {
        $name    = filter_var($this->_request->getParam('containerName'), FILTER_SANITIZE_STRING);
        $pageId  = filter_var($this->_request->getParam('pageId'), FILTER_SANITIZE_NUMBER_INT);
        $type    = ($pageId) ? Application_Model_Models_Container::TYPE_REGULARCONTENT : Application_Model_Models_Container::TYPE_STATICCONTENT;

        if($pageId == 0) {
            $pageId = null;
        }

        $tokenToValidate = $this->_request->getParam(Tools_System_Tools::CSRF_SECURE_TOKEN, false);
        $valid = Tools_System_Tools::validateToken($tokenToValidate, Webbuilder::WB_GALLERY_SECURE_TOKEN);
        if (!$valid) {
            exit;
        }

        // gallery widget specific fields
        $folder     = filter_var($this->_request->getParam('folder'), FILTER_SANITIZE_STRING);
        $thumbnails = filter_var($this->_request->getParam('thumbnails', Widgets_WebbuilderWidget::DEFAULT_THUMB_SIZE), FILTER_SANITIZE_NUMBER_INT);
        $width      = filter_var($this->_request->getParam('width'), FILTER_SANITIZE_NUMBER_INT);
        $height     = filter_var($this->_request->getParam('height'), FILTER_SANITIZE_NUMBER_INT);
        if ($width != '' && $height != '') {
            $crop   = $width.'x'.$height;
        }
        elseif ($width != '') {
            $crop   = $width.'x'.$width;
        }
        else {
            $crop   = filter_var($this->_request->getParam('crop', 0), FILTER_SANITIZE_NUMBER_INT);
        }
        $caption    = filter_var($this->_request->getParam('caption'), FILTER_SANITIZE_NUMBER_INT);
        //nolink
        $block = filter_var($this->_request->getParam('block', false), FILTER_SANITIZE_NUMBER_INT);

        $content    = $folder . ':' . $thumbnails . ':' . $crop . ':' . $caption . ':' . $block;

        // save container
        $mapper    = Application_Model_Mappers_ContainerMapper::getInstance();
        $container = $mapper->findByName($name, $pageId, $type);
        if(!$container instanceof Application_Model_Models_Container) {
            $container = new Application_Model_Models_Container();
            $container->setPageId($pageId)
                ->setContainerType($type)
                ->setName($name);
        }
        $container->setContent($content);

        try {
            return array('error' => false, 'responseText' => $mapper->save($container));
        } catch (Exception $e) {
            return $this->_error($e->getMessage());
        }
    }

    public function getAction() {}
    public function putAction() {}
    public function deleteAction() {
        parse_str($this->_request->getRawBody(), $data);
        $name   = filter_var($data['containerName'], FILTER_SANITIZE_STRING);
        $pageId = filter_var($data['pageId'], FILTER_SANITIZE_NUMBER_INT);
        $type    = ($pageId) ? Application_Model_Models_Container::TYPE_REGULARCONTENT : Application_Model_Models_Container::TYPE_STATICCONTENT;

        if($pageId == 0) {
            $pageId = null;
        }
        // save container
        $mapper    = Application_Model_Mappers_ContainerMapper::getInstance();
        $container = $mapper->findByName($name, $pageId, $type);
        if(!$container instanceof Application_Model_Models_Container) {
            return array('error' => false);
        }

        try {
            return array('error' => false, 'responseText' => $mapper->delete($container));
        } catch (Exception $e) {
            return $this->_error($e->getMessage());
        }
    }


}