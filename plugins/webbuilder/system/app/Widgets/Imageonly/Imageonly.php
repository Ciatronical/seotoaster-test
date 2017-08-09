<?php

class Widgets_Imageonly_Imageonly extends Widgets_WebbuilderWidget
{

    const LINK_OPTION_URL = 'external';

    const LINK_OPTION_NOTHING = 'nothing';

    const LINK_OPTION_IMAGE = 'image';

    const LOCAL_PAGE_ID = 'localPageId';

    protected function _load()
    {
        $name = Webbuilder_Tools_Misc::toHash($this->_options[0] . __CLASS__);
        $pageId = (end($this->_options) == 'static') ? 0 : $this->_toasterOptions['id'];
        $type = ($pageId) ? Application_Model_Models_Container::TYPE_REGULARCONTENT : Application_Model_Models_Container::TYPE_STATICCONTENT;
        $container = Application_Model_Mappers_ContainerMapper::getInstance()->findByName($name, $pageId, $type);

        $config = Zend_Controller_Action_HelperBroker::getStaticHelper('config')->getConfig();

        if ($container instanceof Application_Model_Models_Container) {
            $ioData = Zend_Json::decode($container->getContent());
            $localLinkUrl = false;

            if (is_array($ioData) && !empty($ioData)) {
                foreach ($ioData as $key => $value) {
                    if ($key === self::LOCAL_PAGE_ID) {
                        $localPage = Application_Model_Mappers_PageMapper::getInstance()->find($value);
                        if ($localPage instanceof Application_Model_Models_Page) {
                            if(($ioData['externalUrl'] === $this->_websiteUrl.$localPage->getUrl()) && !(strpos($ioData['externalUrl'], '#'))){
                                $ioData['externalUrl'] = $this->_websiteHelper->getUrl() . $localPage->getUrl();
                            }
                            $localLinkUrl = true;
                        }
                    }
                    $this->_view->$key = $value;
                }
            }
            if ($localLinkUrl) {
                $this->_view->externalUrl = $ioData['externalUrl'];
            }
        }

        $width = !empty($this->_options[1]) ? filter_var($this->_options[1], FILTER_SANITIZE_STRING) : self::DEFAULT_THUMB_SIZE;
        if (!is_numeric($width)) {
            $imgType = 'img' . ucfirst($width);
            if (!empty($config[$imgType])) {
                $width = $config[$imgType];
            } elseif (!empty($ioData['folder']) && $imgType === 'imgOriginal') {
                $imgData = getimagesize(
                    $this->_websiteHelper->getPath() . 'media/' . $ioData['folder'] . '/original/' . $ioData['image']
                );
                $width = $imgData[0];
            } else {
                $width = self::DEFAULT_THUMB_SIZE;
            }
        }
        $this->_view->containerName = $name;
        $this->_view->width = $width;
        $this->_view->mediaSubFolder = Webbuilder_Tools_Filesystem::getMediaSubFolderByWidth($width);
        $this->_view->pageId = $pageId;

        return $this->_view->render('imageonly.phtml');
    }
}