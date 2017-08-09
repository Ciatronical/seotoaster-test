<?php
/**
 * Webbuilder featured area only API
 *
 * @author Eugene I. Nezhuta <eugene@seotoaster.com>
 * User: Eugene I. Nezhuta <eugene@seotoaster.com>
 * Date: 4/23/13
 * Time: 12:34 PM
 */

class Api_Webbuilder_Fo extends Api_Service_Abstract {

    protected $_accessList  = array(
        Tools_Security_Acl::ROLE_USER       => array('allow' => array('get', 'post', 'put', 'delete')),
        Tools_Security_Acl::ROLE_SUPERADMIN => array('allow' => array('get', 'post', 'put', 'delete')),
        Tools_Security_Acl::ROLE_ADMIN      => array('allow' => array('get', 'post', 'put', 'delete'))
    );

    public function postAction() {
        $name   = filter_var($this->_request->getParam('containerName'), FILTER_SANITIZE_STRING);
        $pageId = filter_var($this->_request->getParam('pageId'), FILTER_SANITIZE_NUMBER_INT);
        $type   = ($pageId) ? Application_Model_Models_Container::TYPE_REGULARCONTENT : Application_Model_Models_Container::TYPE_STATICCONTENT;

        if($pageId == 0) {
            $pageId = null;
        }

        $tokenToValidate = $this->_request->getParam(Tools_System_Tools::CSRF_SECURE_TOKEN, false);
        $valid = Tools_System_Tools::validateToken($tokenToValidate, Webbuilder::WB_FAREA_SECURE_TOKEN);
        if (!$valid) {
            exit;
        }


        // featured area specific fields
        $areaName         = filter_var($this->_request->getParam('areaName'), FILTER_SANITIZE_STRING);
        $pagesToShow      = filter_var($this->_request->getParam('maxRes'), FILTER_SANITIZE_NUMBER_INT);
        $descriptionLimit = filter_var($this->_request->getParam('maxChar'), FILTER_SANITIZE_NUMBER_INT);
        $useImage         = filter_var($this->_request->getParam('useImg'), FILTER_SANITIZE_NUMBER_INT);
        $random           = filter_var($this->_request->getParam('rand'), FILTER_SANITIZE_NUMBER_INT);

        $content          = $areaName  . ':' . $pagesToShow. ':' . $descriptionLimit;
        if ($useImage) {
            $cropImage = (bool) $this->_request->getParam('cropImg');
            $width     = filter_var($this->_request->getParam('width'), FILTER_SANITIZE_NUMBER_INT);
            $height    = filter_var($this->_request->getParam('height'), FILTER_SANITIZE_NUMBER_INT);

            if ($cropImage && $width != '' && $height != '') {
                $content .= ':imgc-'.$width.'x'.$height;
            }
            elseif ($cropImage && $width != '') {
                $content .= ':imgc-'.$width.'x'.$width;
            }
            else {
                $content .= ':'.(($cropImage) ? 'imgc' : 'img');
            }
        } else {
            $content .= ':';
        }

        if($random) {
            $content .= ':' . $random;
        }

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
        $type   = ($pageId) ? Application_Model_Models_Container::TYPE_REGULARCONTENT : Application_Model_Models_Container::TYPE_STATICCONTENT;

        if($pageId == 0) {
            $pageId = null;
        }

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