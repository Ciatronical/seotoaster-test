<?php
/**
 * Webbuilder direct upload API
 *
 * @author Eugene I. Nezhuta <eugene@seotoaster.com>
 * User: Eugene I. Nezhuta <eugene@seotoaster.com>
 * Date: 4/19/13
 * Time: 4:53 PM
 */

class Api_Webbuilder_Du extends Api_Service_Abstract {

    private $_websiteHelper = null;

    private $_debugMode    = false;

    protected $_accessList  = array(
        Tools_Security_Acl::ROLE_USER       => array('allow' => array('get', 'post', 'put', 'delete')),
        Tools_Security_Acl::ROLE_SUPERADMIN => array('allow' => array('get', 'post', 'put', 'delete')),
        Tools_Security_Acl::ROLE_ADMIN      => array('allow' => array('get', 'post', 'put', 'delete'))
    );

    public function init() {
        $this->_websiteHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('website');
        $this->_debugMode     = Tools_System_Tools::debugMode();
    }

    /**
     * Upload an image
     *
     */
    public function postAction()
    {
        $imageData           = $this->_request->getParams();
        $imageName           = $imageData['imageName'];
        $pathToDirectory     = $this->_websiteHelper->getPath() . 'media' . DIRECTORY_SEPARATOR . $imageData['folderName'] . DIRECTORY_SEPARATOR;
        $thumbnailsDirectory = $pathToDirectory .'thumbnails'. DIRECTORY_SEPARATOR;
        $cropDirectory       = $pathToDirectory .'crop'. DIRECTORY_SEPARATOR;

        $pageId  = filter_var($imageData['pageId'], FILTER_SANITIZE_NUMBER_INT);
        $type    = ($pageId) ? Application_Model_Models_Container::TYPE_REGULARCONTENT : Application_Model_Models_Container::TYPE_STATICCONTENT;

        $tokenToValidate = $this->_request->getParam(Tools_System_Tools::CSRF_SECURE_TOKEN, false);
        $valid = Tools_System_Tools::validateToken($tokenToValidate, Webbuilder::WB_DIRECTUPLOAD_SECURE_TOKEN);
        if (!$valid) {
            exit;
        }

        if($pageId == 0) {
            $pageId = null;
        }

        if (!is_dir($thumbnailsDirectory)) {
            Tools_Filesystem_Tools::mkdir($thumbnailsDirectory);
        }

        $useCrop   = true;
        $thumbSize = Widgets_Directupload_Directupload::DEFAULT_THUMB_SIZE;

        // Delete old img
        $imgTypes  = array('jpg' => 'jpg', 'png' => 'png', 'jpeg' => 'jpeg', 'gif' => 'gif');
        $imgType   = explode('.', $imageData['imageName']);
        $imgType   = end($imgType);
        if (!in_array($imgType, $imgTypes)) {
            return array('error' => 1, 'message' => "Wrong file type");
        }
        unset($imgTypes[$imgType]);
        $oldPreviews = Tools_Filesystem_Tools::findFilesByExtension(
            $pathToDirectory.Tools_Image_Tools::FOLDER_ORIGINAL.DIRECTORY_SEPARATOR,
            implode('|', $imgTypes),
            true,
            true,
            true
        );
        if (isset($oldPreviews[$imageData['containerName']])) {
            Tools_Filesystem_Tools::deleteFile($oldPreviews[$imageData['containerName']]);
        }


        Tools_Image_Tools::resizeByParameters(
            $pathToDirectory.Tools_Image_Tools::FOLDER_ORIGINAL.DIRECTORY_SEPARATOR.$imageName,
            $thumbSize,
            'auto',
            !($useCrop),
            $thumbnailsDirectory,
            $useCrop
        );

        if (!is_dir($cropDirectory)) {
            Tools_Filesystem_Tools::mkdir($cropDirectory);
        }

        Tools_Image_Tools::resizeByParameters(
            $pathToDirectory.Tools_Image_Tools::FOLDER_ORIGINAL.DIRECTORY_SEPARATOR.$imageName,
            $thumbSize,
            'auto',
            !($useCrop),
            $cropDirectory,
            $useCrop
        );

        if (!empty($imageData['cropParams']) && isset($imageData['cropParams']['subfolder'])) {
            // Create a folder crop-size subfolder
            if (!is_dir($cropDirectory.$imageData['cropParams']['subfolder'].DIRECTORY_SEPARATOR)) {
                Tools_Filesystem_Tools::mkDir($cropDirectory.$imageData['cropParams']['subfolder'].DIRECTORY_SEPARATOR);
            }

            // Create image by size
            if (isset($imageData['cropParams']['width'], $imageData['cropParams']['height'])
                && !file_exists($cropDirectory.$imageData['cropParams']['subfolder'].DIRECTORY_SEPARATOR.$imageName)
            ) {
                Tools_Image_Tools::resizeByParameters(
                    $pathToDirectory.Tools_Image_Tools::FOLDER_ORIGINAL.DIRECTORY_SEPARATOR.$imageName,
                    $imageData['cropParams']['width'],
                    $imageData['cropParams']['height'],
                    true,
                    $cropDirectory.$imageData['cropParams']['subfolder'].DIRECTORY_SEPARATOR,
                    true
                );
            }
        }

        $mapper    = Application_Model_Mappers_ContainerMapper::getInstance();
        $container = $mapper->findByName($imageData['containerName'], $pageId, $type);
        if(!$container instanceof Application_Model_Models_Container) {
            $container = new Application_Model_Models_Container();
            $container->setPageId($pageId)
                ->setContainerType($type)
                ->setName($imageData['containerName']);
        }
        $container->setContent($imageName);
        $mapper->save($container);
    }

    /**
     * Removing image
     *
     */
    public function deleteAction() {
        $data       = Zend_Json::decode($this->_request->getRawBody());
        $folderPath	= realpath($this->_websiteHelper->getPath() . $this->_websiteHelper->getMedia() . $data['folderName']);

        if(!isset($data['imageName']) || !isset($data['folderName'])) {
            $this->_error('Folder name or image name not specified');
        }

        // get an image name from the image path and image info
        $data['imageName'] = basename($data['imageName']);
        $info              = pathinfo($data['imageName']);

        try {
            // removing image from filesystem
            if(($result = Tools_Image_Tools::removeImageFromFilesystem($data['imageName'], $data['folderName'])) !== true) {
                $this->_error($result);
            }

            $pageId = filter_var($this->_request->getParam('pageId'), FILTER_SANITIZE_NUMBER_INT);
            $type   = ($pageId) ? Application_Model_Models_Container::TYPE_REGULARCONTENT : Application_Model_Models_Container::TYPE_STATICCONTENT;

            if($pageId == 0) {
                $pageId = null;
            }

            // removing the container
            $mapper    = Application_Model_Mappers_ContainerMapper::getInstance();
            $container = $mapper->findByName($info['filename'], $pageId, $type);
            if($container instanceof Application_Model_Models_Container) {
                $mapper->delete($container);
            }


            //cleaning up the file system if needed
            $folderContent = Tools_Filesystem_Tools::scanDirectory($folderPath, false, true);
            if(empty($folderContent)) {
                try {
                    Tools_Filesystem_Tools::deleteDir($folderPath);
                } catch (Exception $e) {
                    $this->_debugMode && error_log($e->getMessage());
                    $this->_error($e->getMessage());
                }
            }
        } catch (Exceptions_SeotoasterException $e) {
            error_log($e->getMessage() . PHP_EOL . $e->getTraceAsString());
            $this->_error($e->getMessage());
        }
    }

    public function getAction() {}
    public function putAction() {}
}
