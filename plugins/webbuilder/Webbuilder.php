<?php

class Webbuilder extends Tools_Plugins_Abstract {

    const VIEWS_POSTFIX   = '.webbuilder.phtml';

    /**
     * Webbuilder secure token
     */
    const WB_DIRECTUPLOAD_SECURE_TOKEN = 'WbDirectupload';

    const WB_FAREA_SECURE_TOKEN = 'WbFarea';

    const WB_GALLERY_SECURE_TOKEN = 'WbGallery';

    const WB_IMAGEONLY_SECURE_TOKEN = 'WbImageonly';

    const WB_TEXTONLY_SECURE_TOKEN = 'WbTextonly';

    const WB_VIDEOLINK_SECURE_TOKEN = 'WbVideolink';

    /**
     * Actions access list
     *
     * @var array
     */
    protected $_securedActions = array(
        Tools_Security_Acl::ROLE_USER => array('textonly', 'imageonly', 'featuredonly', 'galleryonly', 'directupload', 'videolink')
    );

    /**
     * Seotoaster config helper
     *
     * @var Helpers_Action_Config
     */
    protected $_configHelper = null;

    /**
     * Zend layout instance to render all the plugin's screens
     *
     * @var Zend_Layout
     */
    protected $_layout       = null;

    protected function _init() {
        // initialize layout
        $this->_layout = new Zend_Layout();
        $this->_layout->setLayoutPath(Zend_Layout::getMvcInstance()->getLayoutPath());

        // set proper view script pathes
        if(($scriptPaths = Zend_Layout::getMvcInstance()->getView()->getScriptPaths()) !== false) {
            $this->_view->setScriptPath($scriptPaths);
        }
        $this->_view->addScriptPath(__DIR__ . '/system/views/');

        // initialize helpers
        $this->_configHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('config');
    }

    /**
     * Text only edit screen
     *
     */
    public function textonlyAction() {
        $containerName = filter_var($this->_request->getParam('container'), FILTER_SANITIZE_STRING);
        $pageId        = filter_var($this->_request->getParam('pageId'), FILTER_SANITIZE_NUMBER_INT);
        $type          = ($pageId) ? Application_Model_Models_Container::TYPE_REGULARCONTENT : Application_Model_Models_Container::TYPE_STATICCONTENT;
        $container     = Application_Model_Mappers_ContainerMapper::getInstance()->findByName($containerName, $pageId, $type);

        // assign view variables
        $this->_view->content       = ($container instanceof Application_Model_Models_Container) ? $container->getContent() : '';
        $this->_view->pageId        = $pageId;
        $this->_view->containerName = $containerName;
        $this->_view->currentTheme  = $this->_configHelper->getConfig('currentTheme');

        // render
        $this->_show();
    }

    /**
     * Featured only screen
     */
    public function featuredonlyAction()
    {
        $containerName = filter_var($this->_request->getParam('container'), FILTER_SANITIZE_STRING);
        $pageId        = filter_var($this->_request->getParam('pageId'), FILTER_SANITIZE_NUMBER_INT);
        if ($pageId) {
            $type      = Application_Model_Models_Container::TYPE_REGULARCONTENT;
        }
        else {
            $type      = Application_Model_Models_Container::TYPE_STATICCONTENT;
        }
        $container     = Application_Model_Mappers_ContainerMapper::getInstance()->findByName(
            $containerName,
            $pageId,
            $type
        );
        $content       = '';
        if ($container instanceof Application_Model_Models_Container) {
            $content   = explode(':', $container->getContent());
        }
        $useImage      = false;
        $width         = '';
        $height        = '';

        // Image output options
        if (isset($content[3]) && ($content[3] == 'img' || $content[3] == 'imgc')) {
            $useImage = $content[3];
        }
        elseif (isset($content[3]) && strpos($content[3], 'imgc-') !== false) {
            preg_match('/^imgc-([0-9]+)x?([0-9]*)/i', $content[3], $cropParams);
            if (isset($cropParams[1], $cropParams[2])
                && is_numeric($cropParams[1])
                && $cropParams[2] == ''
            ) {
                $cropParams[2] = $cropParams[1];
            }
            $useImage = 'imgc';
            $width    = $cropParams[1];
            $height   = $cropParams[2];
        }

        // Assign view variables
        $this->_view->useImage      = $useImage;
        $this->_view->width         = $width;
        $this->_view->height        = $height;
        $this->_view->content       = $content;
        $this->_view->pageId        = $pageId;
        $this->_view->containerName = $containerName;
        $this->_view->areas         = Application_Model_Mappers_FeaturedareaMapper::getInstance()->fetchAll();

        // Render
        $this->_show();
    }

    /**
     * Gallery only screen
     */
    public function galleryonlyAction()
    {
        $containerName = filter_var($this->_request->getParam('containerName'), FILTER_SANITIZE_STRING);
        $pageId        = filter_var($this->_request->getParam('pageId'), FILTER_SANITIZE_NUMBER_INT);
        $container     = Application_Model_Mappers_ContainerMapper::getInstance()->findByName($containerName, $pageId);
        $width         = '';
        $height        = '';
        $content       = '';
        if ($container instanceof Application_Model_Models_Container) {
            $content   = explode(':', $container->getContent());
        }

        // Assign view variables
        if (is_array($content) && !empty($content)) {
            if (strpos($content[2], 'x') !== false) {
                list($width, $height) = explode('x', $content[2]);
            }

            $this->_view->galleryName = $content[0];
            $this->_view->thumbs      = $content[1];
            $this->_view->crop        = (bool) $content[2];
            $this->_view->caption     = $content[3];
            $this->_view->block        = !empty($content[4]) ? $content[4]: 0;
        }
        $this->_view->width           = $width;
        $this->_view->height          = $height;
        $this->_view->pageId          = $pageId;
        $this->_view->containerName   = $containerName;
        $this->_view->listofFolders   = Tools_Filesystem_Tools::scanDirectoryForDirs(
            $this->_websiteHelper->getPath().$this->_websiteHelper->getMedia()
        );

        // Render
        $this->_show();
    }

    public function imageonlyAction() {
        $containerName = filter_var($this->_request->getParam('containerName'), FILTER_SANITIZE_STRING);
        $pageId        = filter_var($this->_request->getParam('pid'), FILTER_SANITIZE_NUMBER_INT);
        $type          = ($pageId) ? Application_Model_Models_Container::TYPE_REGULARCONTENT : Application_Model_Models_Container::TYPE_STATICCONTENT;
        $container     = Application_Model_Mappers_ContainerMapper::getInstance()->findByName($containerName, $pageId, $type);
        $ioData        = array();

        if($container instanceof Application_Model_Models_Container) {
            $ioData = Zend_Json::decode($container->getContent());
        }

        $mediaFolders = Tools_Filesystem_Tools::scanDirectoryForDirs($this->_websiteHelper->getPath() . 'media/');
        if(!empty($mediaFolders)) {
            foreach($mediaFolders as $key => $mediaFolder) {
                $mediaSubFolder = $this->_websiteHelper->getPath() . 'media/' . $mediaFolder . '/small/';
                if(!is_dir($mediaSubFolder)) {
                    continue;
                }
                if((boolean)Tools_Filesystem_Tools::scanDirectory($mediaSubFolder)) {
                    continue;
                }
                unset($mediaFolders[$key]);
            }
        }
        asort($mediaFolders);

        if(is_array($ioData) && !empty($ioData)) {
            foreach($ioData as $key => $value) {
                $this->_view->$key = $value;
            }
        }

        $this->_view->folders       = $mediaFolders;
        $this->_view->description   = isset($ioData['description']) ? $ioData['description'] : '';
        $this->_view->pageId        = $pageId;
        $this->_view->containerName = $containerName;

        // render
        $this->_show();
    }

    /**
     * Videolink screen
     *
     */
    public function videolinkAction() {
        $containerName = filter_var($this->_request->getParam('container'), FILTER_SANITIZE_STRING);
        $pageId        = filter_var($this->_request->getParam('pageId'), FILTER_SANITIZE_NUMBER_INT);
        $type          = ($pageId) ? Application_Model_Models_Container::TYPE_REGULARCONTENT : Application_Model_Models_Container::TYPE_STATICCONTENT;
        $container     = Application_Model_Mappers_ContainerMapper::getInstance()->findByName($containerName, $pageId, $type);
        $ioData        = array();

        if($container instanceof Application_Model_Models_Container) {
            $ioData = Zend_Json::decode($container->getContent());
        }

        // assign view variables
        $this->_view->content       = ($container instanceof Application_Model_Models_Container) ? explode(':', $container->getContent()) : '';
        $this->_view->pageId        = $pageId;
        $this->_view->containerName = $containerName;
        $this->_view->width         = isset($ioData['width']) ? $ioData['width'] : Widgets_Videolink_Videolink::VIDEOLINK_DEFAULT_WIDTH;
        $this->_view->height        = isset($ioData['height']) ? $ioData['height'] : Widgets_Videolink_Videolink::VIDEOLINK_DEFAULT_HEIGHT;
        $this->_view->link          = isset($ioData['link']) ? $ioData['link'] :'';

        // render
        $this->_show();
    }



    public static function exportWebsiteData() {
        $media     = array();
        $dbAdapter = Zend_Registry::get('dbAdapter');
        $wbContent = $dbAdapter->fetchCol("SELECT `content` FROM `container` WHERE `name` LIKE 'wb_%';");
        $ioPattern = '~{"folder":"([\w\-]*)","image":"([\w\-]*\.jpg|png|jpeg|gif)".*}~';
        $goPattern = '~^([\w]*):[:\d]*~';
        $duPattern = '~(wb_[\w]*\.jpg|png|jpeg|gif)~';
        if(is_array($wbContent) && !empty($wbContent)) {
            foreach($wbContent as $key => $wbItem) {
                if(preg_match($ioPattern, $wbItem)) {
                    $media[] = preg_replace($ioPattern, 'media/$1/original/$2', $wbItem);
                }
                if(preg_match($goPattern, $wbItem)) {
                    $media = array_merge($media, glob(preg_replace($goPattern, 'media/$1/original/*', $wbItem)));
                }
                if(preg_match($duPattern, $wbItem)) {
                    $media = array_merge($media, glob(preg_replace($duPattern, 'media/*/original/$1', $wbItem)));
                }

            }
        }

        return array('media' => $media);
    }

    /**
     * Render a proper view script
     *
     * If $screenViewScript not passed, generates view script file name automatically using the action name and VIEWS_POSTFIX
     * @param string $screenViewScript
     */
    private function _show($screenViewScript = '') {
        if(!$screenViewScript) {
            $trace  = debug_backtrace(false);
            $screenViewScript = str_ireplace('Action', self::VIEWS_POSTFIX, $trace[1]['function']);
        }
        $this->_layout->content = $this->_view->render($screenViewScript);
        echo $this->_layout->render();
    }
}

