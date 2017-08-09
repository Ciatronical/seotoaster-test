<?php

class Widgets_Galleryonly_Galleryonly extends Widgets_WebbuilderWidget {

    const GALLERY_WIDGET_TEMPLATE = '{$gal:%s}';

   	protected function _load(){

        // get container by its name
        $name      = Webbuilder_Tools_Misc::toHash($this->_options[0] . __CLASS__);
        $pageId    = (end($this->_options) == 'static') ? 0 : $this->_toasterOptions['id'];
        $type      = ($pageId) ? Application_Model_Models_Container::TYPE_REGULARCONTENT : Application_Model_Models_Container::TYPE_STATICCONTENT;
        $container = Application_Model_Mappers_ContainerMapper::getInstance()->findByName($name, $pageId, $type);

        // assign view variables
        $this->_view->container = $name;
        $this->_view->pageId    = $pageId;
        $this->_view->content   = ($container instanceof Application_Model_Models_Container) ? sprintf(self::GALLERY_WIDGET_TEMPLATE, $container->getContent()) : '';

        return $this->_view->render('galleryonly.phtml');
    }
}
