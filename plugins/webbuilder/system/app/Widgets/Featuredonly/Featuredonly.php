<?php

class Widgets_Featuredonly_Featuredonly extends Widgets_WebbuilderWidget {

   	const FA_WIDGET_TEMPLATE           = '{$featured:area:%s}';

    const FA_DEFAULT_MAX_PAGES_COUNT   = 5;

    const FA_DEFAULT_DESCRIPTION_LIMIT = 250;

    protected function _load(){
        // get container by its name
        $name      = Webbuilder_Tools_Misc::toHash($this->_options[0] . __CLASS__);
        $pageId    = (end($this->_options) == 'static') ? 0 : $this->_toasterOptions['id'];
        $type      = ($pageId) ? Application_Model_Models_Container::TYPE_REGULARCONTENT : Application_Model_Models_Container::TYPE_STATICCONTENT;
        $container = Application_Model_Mappers_ContainerMapper::getInstance()->findByName($name, $pageId, $type);

        // assign view variables
        $this->_view->container = $name;
        $this->_view->pageId    = $pageId;
        $class = current(preg_grep('/class=*/', $this->_options));
        $content = '';
        if ($container instanceof Application_Model_Models_Container) {
            $content = $container->getContent();
            if ($class) {
                $featuredAreaOptions = explode(':', $content);
                if (count($featuredAreaOptions) > 4) {
                    array_splice($featuredAreaOptions, 4, null, $class);
                    $content = implode(':', $featuredAreaOptions);
                } else {
                    $content .= ':' . $class;
                }
            }
            $content = sprintf(self::FA_WIDGET_TEMPLATE, $content);
        }
        $this->_view->content = $content;
        return $this->_view->render('featuredonly.phtml');
	}

		
}
