<?php
/**
 * Webbuilder image only API
 *
 * @author Eugene I. Nezhuta <eugene@seotoaster.com>
 * User: Eugene I. Nezhuta <eugene@seotoaster.com>
 * Date: 4/29/13
 * Time: 5:04 PM
 */

class Api_Webbuilder_Io extends Api_Service_Abstract
{

    const DEFAULT_MEDIA_SUBFOLDER = 'small';

    private $_websiteHelper = null;

    /**
     * Container mapper
     *
     * @var Application_Model_Mappers_ContainerMapper
     */
    private $_mapper = null;

    protected $_accessList = array(
        Tools_Security_Acl::ROLE_USER => array('allow' => array('get', 'post', 'put', 'delete')),
        Tools_Security_Acl::ROLE_SUPERADMIN => array('allow' => array('get', 'post', 'put', 'delete')),
        Tools_Security_Acl::ROLE_ADMIN => array('allow' => array('get', 'post', 'put', 'delete'))
    );

    public function init()
    {
        $this->_websiteHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('website');
        $this->_mapper = Application_Model_Mappers_ContainerMapper::getInstance();
    }

    public function getAction()
    {
        $folder = filter_var($this->_request->getParam('folder'), FILTER_SANITIZE_STRING);
        if (!$folder) {
            $this->_error();
        }
        $folderPath = $this->_websiteHelper->getPath() . $this->_websiteHelper->getMedia(
        ) . $folder . DIRECTORY_SEPARATOR;
        if (!is_dir($folderPath)) {
            $this->_error();
        }
        $images = Tools_Filesystem_Tools::scanDirectory($folderPath . self::DEFAULT_MEDIA_SUBFOLDER);
        if (is_array($images) && !empty($images)) {
            $images = array_map(
                function ($image) {
                    return $image;
                },
                $images
            );
        }
        return $images;
    }

    public function postAction()
    {
        $ioData = array(
            'folder' => filter_var($this->_request->getParam('folder'), FILTER_SANITIZE_STRING),
            'image' => filter_var($this->_request->getParam('image'), FILTER_SANITIZE_STRING),
            'description' => filter_var($this->_request->getParam('description'), FILTER_SANITIZE_STRING),
            'linkedTo' => filter_var($this->_request->getParam('linkedto'), FILTER_SANITIZE_STRING),
        );

        $tokenToValidate = $this->_request->getParam(Tools_System_Tools::CSRF_SECURE_TOKEN, false);
        $valid = Tools_System_Tools::validateToken($tokenToValidate, Webbuilder::WB_IMAGEONLY_SECURE_TOKEN);
        if (!$valid) {
            exit;
        }

        $target = $this->_request->getParam('target');
        $ioData['target'] = $target === '_self' ? $target : '_blank';

        $externalUrl = $this->_request->getParam('externalUrl', '');

        if (!empty($externalUrl)) {
            if (preg_match('~^(http|https|ftp)\://~u', $externalUrl)) {
                $ioData['externalUrl'] = filter_var($externalUrl, FILTER_SANITIZE_STRING);
                $externalLinkInfo = parse_url($ioData['externalUrl']);
                $localWebsiteInfo = parse_url($this->_websiteHelper->getUrl());
                if ($externalLinkInfo['host'] === $localWebsiteInfo['host']) {
                    $localPage = Application_Model_Mappers_PageMapper::getInstance()->findByUrl(
                        rawurldecode(ltrim($externalLinkInfo['path'], '\/'))
                    );
                    if ($localPage instanceof Application_Model_Models_Page) {
                        $ioData['localPageId'] = $localPage->getId();
                    }
                }
            } elseif (preg_match('~^(#)~u', $externalUrl)) {
                $ioData['externalUrl'] = filter_var($externalUrl, FILTER_SANITIZE_STRING);
            }
            else {
                return $this->_error();
            }
        } else {
            $ioData['externalUrl'] = '';
        }


        $containerName = filter_var($this->_request->getParam('container'), FILTER_SANITIZE_STRING);
        $pageId = filter_var($this->_request->getParam('pid'), FILTER_SANITIZE_NUMBER_INT);
        $type = ($pageId) ? Application_Model_Models_Container::TYPE_REGULARCONTENT : Application_Model_Models_Container::TYPE_STATICCONTENT;

        if ($pageId == 0) {
            $pageId = null;
        }

        $container = $this->_mapper->findByName($containerName, $pageId, $type);
        if (!$container instanceof Application_Model_Models_Container) {
            $container = new Application_Model_Models_Container();
            $container->setName($containerName)
                ->setPageId($pageId)
                ->setContainerType($type);
        }
        $container->setContent(Zend_Json::encode($ioData));
        return $this->_mapper->save($container);
    }

    public function deleteAction()
    {
        $ioData = Zend_Json::decode($this->_request->getRawBody());
        $type = ($ioData['pid']) ? Application_Model_Models_Container::TYPE_REGULARCONTENT : Application_Model_Models_Container::TYPE_STATICCONTENT;
        if (empty($ioData) || !isset($ioData['container']) || !isset($ioData['pid'])) {
            $this->_error();
        }
        $container = $this->_mapper->findByName($ioData['container'], $ioData['pid'], $type);
        if (!$container instanceof Application_Model_Models_Container) {
            $this->_error();
        }
        $this->_mapper->delete($container);
    }


    public function putAction()
    {
    }

}