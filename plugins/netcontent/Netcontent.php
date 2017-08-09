<?php
/**
 * Seotoaster 2.0 plugin bootstrap.
 *
 * @author Seotoaster core team <core.team@seotoaster.com>
 */

class Netcontent extends Tools_Plugins_Abstract {

	const CLIENT_WIDGET = 1;

	const AGENCY_WIDGET = 0;

    protected $_configHelper = null;

    protected $_seoWidgets = array('seoHead','seoTop','seoBottom');

	/**
	 * List of action that should be allowed to specific roles
	 *
	 * By default all of actions of your plugin are available to the guest user
	 * @var array
	 */
	protected $_securedActions = array(
		Tools_Security_Acl::ROLE_SUPERADMIN => array(
            'secured'
        )
	);

	/**
	 * Init method.
	 *
	 * Use this method to init your plugin's data and variables
	 * Use this method to init specific helpers, view, etc...
	 */
	protected function _init() {
		$this->_view->setScriptPath(__DIR__ . '/system/views/');
        $this->_configHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('config');
	}

	/**
	 * Main entry point
	 *
	 * @param array $requestedParams
	 * @return mixed $dispatcherResult
	 */
	public function run($requestedParams = array()) {
		parent::run($requestedParams);
		return isset($this->_options[0]) ? $this->_getWidgetContent() : '';
	}


    public static function getTabContent() {
        $translator = Zend_Controller_Action_HelperBroker::getStaticHelper('language');
        $websiteUrl = Zend_Controller_Action_HelperBroker::getStaticHelper('website')->getUrl();
        return array(
            'title'   => '<span id="netcontent">' . 'Netcontent' . '</span>',
            'content' => '<div id="list-of-widgets"></div><script src="'. $websiteUrl . 'plugins/' . strtolower(__CLASS__) .'/web/js/netcontentlist.min.js"></script>'
        );
    }

    public static function getEditorTop() {
        $view             = new Zend_View(array('scriptPath' => __DIR__ . '/system/views/'));
        $view->isP2p      = true;
        $view->websiteUrl = Zend_Controller_Action_HelperBroker::getStaticHelper('website')->getUrl();
        $view->setHelperPath(APPLICATION_PATH . '/views/helpers/');
        $view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
        return array(
            'title' => "p2p-check",
            'code'  => $view->render('widget.netcontent.phtml')
        );
    }

    public static function getEditorLink() {
        $translator = Zend_Controller_Action_HelperBroker::getStaticHelper('language');
        return array(
            'link'   => '<a class="netcontent-list-link" href="javascript:;">' . $translator->translate('NetContent') . '</a>',
            'script' => 'plugins/' . strtolower(__CLASS__) .'/web/js/netcontentlist.min.js'
        );
    }

    public function isP2pAction() {
        if(Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_CONTENT)) {
            $this->_responseHelper->success(array(
                'exist' => (boolean)Netcontent_Models_Mapper_Netcontent::getInstance()->findByName($this->_generateWidgetName(), self::CLIENT_WIDGET)
            ));
        }
        $this->_responseHelper->fail($this->_translator->translate('ADMIN access needed'));
        return false;
    }

    public function widgetlistAction() {
        if(Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_CONTENT)) {
            if($this->_configHelper->getConfig('sambaToken') == '') {
                $this->_responseHelper->fail(array(
                    'message'     => $this->_translator->translate('You should be connected to SEO Samba!'),
                    'notConected' => true
                ));
                return false;
            }
            $netcontentDbTable = new Netcontent_Models_DbTable_Netcontent();
            $where = $netcontentDbTable->getAdapter()->quoteInto('widget_name NOT IN (?)', $this->_seoWidgets);
            $where .= $netcontentDbTable->getAdapter()->quoteInto(' AND publish = ?', true);
            $widgetList = Netcontent_Models_Mapper_Netcontent::getInstance()->fetchAll($where, array('ASC' => 'widget_name'));
            if(empty($widgetList)) {
                $this->_responseHelper->fail($this->_translator->translate('You dont have any widgets'));
                return false;
            }
            $widgetList = array_map(function($widget) {
                $widget = $widget->toArray();
                $widget['content'] = preg_replace_callback('/<[\s]*script\b[^>]*>(.*?)<[\s]*\/[\s]*script[\s]*>/is',
                    create_function('$matches', 'return "<code style=\"display:block;font-size: 10px; color: #aaa; clear: both;\">" . htmlentities($matches[0]) . "</code>";'),
                    htmlspecialchars_decode($widget['content']) );
                return $widget;
            }, $widgetList);
            $this->_responseHelper->success($widgetList);
            return false;
        }
        $this->_responseHelper->fail($this->_translator->translate('ADMIN access needed'));
        return false;
    }

    public function syncNetContentAction() {
        if(Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_CONTENT)) {
            $widgets     = Netcontent_Models_Mapper_Netcontent::getInstance()->fetchAll();
            $toasterWidgets = array(
                'toasterWidgets' => ($widgets) ? array_map(function($widget) {return $widget->getWidgetName();}, $widgets) : array(),
            );
            $syncWidgets = $this->_apiCall(Zend_Form::METHOD_PUT, 'netContent', $toasterWidgets);
            if( isset($syncWidgets['done']) && ($syncWidgets['done'] === false) ) {
                $this->_responseHelper->fail(false);
                return false;
            }
            if(!empty($syncWidgets)) {
                foreach ($syncWidgets as $syncWidget) {
                    if(is_array($widgets)) {
                        if(in_array($syncWidget['widgetName'], $toasterWidgets['toasterWidgets'])) {
                            $qqq = array_search($syncWidget['widgetName'], $toasterWidgets['toasterWidgets']);
                            unset($toasterWidgets['toasterWidgets'][array_search($syncWidget['widgetName'], $toasterWidgets['toasterWidgets'])]);
                        }
                    }
                    Netcontent_Models_Mapper_Netcontent::getInstance()->save(new Netcontent_Models_Model_Netcontent($syncWidget));
                }
            }
            if(is_array($toasterWidgets['toasterWidgets']) && !empty($toasterWidgets['toasterWidgets'])) {
                foreach ($toasterWidgets['toasterWidgets'] as $widget) {
                    Netcontent_Models_Mapper_Netcontent::getInstance()->deleteWidget($widget);
                    //@todo move to observer
                }
            }
            echo json_encode(true);
        }
        $this->_responseHelper->fail($this->_translator->translate('ADMIN access needed'));
        return false;
    }

    public function saveNetContentAction() {
        if(Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_CONTENT)) {
            $widgetName  = $this->_generateWidgetName();
            $pageUrl = ( $this->_requestedParams['pageUrl'] == '' ) ? 'index.html' : $this->_requestedParams['pageUrl'];
            $currentPage =  Application_Model_Mappers_PageMapper::getInstance()->findByUrl($pageUrl);
            $parseContent = new Tools_Content_Parser($this->_requestedParams['widgetContent'], $currentPage->toArray(), array());
            $netcontent = preg_replace(array('/<a[^>]+(href=[^>]*javascript:;[^>]*|class=[^>]*tpopup[^>]*)[^>]*(href=[^>]*javascript:;[^>]*|class=[^>]*tpopup[^>]*)>(.*?)<\/a>/is','/<img[^>]*class=[^>]*spin[^>]*>/is'), '', $parseContent->parseSimple() );
            $syncWidgets = $this->_apiCall(Zend_Form::METHOD_POST, 'netContent', array(
                'widgetName'    => $widgetName,
                'widgetContent' => $netcontent//$this->_requestedParams['widgetContent'],
            ));
            if($syncWidgets === false) {
                $this->_responseHelper->fail($this->_translator->translate('You should be connected to SEO Samba!'));
                return false;
            }

            $widget = new Netcontent_Models_Model_Netcontent();
            $widgetExists = Netcontent_Models_Mapper_Netcontent::getInstance()->findByName($widgetName, self::CLIENT_WIDGET);
            $widget->setWidgetName($widgetName)
                ->setContent(htmlspecialchars($this->_requestedParams['widgetContent']))
                ->setP2p(true)
                ->setModifyDate(date(DATE_ATOM))
                ->setUpdate((boolean)$widgetExists)
                ->setPublish($widgetExists == null) ? true : $widgetExists->getPublished();

            $result = Netcontent_Models_Mapper_Netcontent::getInstance()->save($widget);

            if(!$result) {
                $this->_responseHelper->fail($this->_translator->translate('Widget with this name already exists.'));
                return false;
            }
            elseif($result == Netcontent_Models_Mapper_Netcontent::RESULT_UPDATED) {
                $this->_responseHelper->success($this->_translator->translate('Widget updated'));
                return true;
            }
            $this->_responseHelper->success($this->_translator->translate('Widget added'));
            return true;
        }
        $this->_responseHelper->fail($this->_translator->translate('ADMIN access needed'));
        return false;
    }

    public function deleteNetContentAction() {
        if(Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_CONTENT)) {
            $widgetName  = $this->_generateWidgetName();
            $mapper      = Netcontent_Models_Mapper_Netcontent::getInstance();

            //delete remote
            $syncWidgets = $this->_apiCall(Zend_Form::METHOD_DELETE, 'netContent', array(
                'widgetName' => $widgetName
            ));

            $result = $mapper->deleteWidget($mapper->findByName($widgetName, self::CLIENT_WIDGET), self::CLIENT_WIDGET);

            //@todo move to observer
            $this->_removeWidgetOccurence($widgetName);

            if($result) {
                $this->_responseHelper->success($this->_translator->translate('Widget deleted.'));
                return true;
            }
            $this->_responseHelper->fail($this->_translator->translate('Error occured.'));
        }
        $this->_responseHelper->fail($this->_translator->translate('ADMIN access needed'));
        return false;
    }

    public function sambaNetContentWidget($data) {
        $widget = new Netcontent_Models_Model_Netcontent($data);
        $widget->setWidgetName($data['widgetName'])
            ->setContent(htmlspecialchars($data['widgetContent']))
            ->setP2p($data['widgetP2p'])
            ->setModifyDate($data['modifyDate'])
            ->setUpdate($data['widgetUpdate'])
			->setPublish($data['widgetPublish']);
        $result =Netcontent_Models_Mapper_Netcontent::getInstance()->save($widget);

        $parsedUrl  = parse_url($this->_websiteUrl);
        $dashPos    = strpos($data['widgetName'], '-');
        if($parsedUrl['host'] == substr($data['widgetName'], 0, $dashPos)) {
            $sContainer = Application_Model_Mappers_ContainerMapper::getInstance()->findByName(substr($data['widgetName'], $dashPos+1), 0, Application_Model_Models_Container::TYPE_STATICCONTENT);
			$sContainer->registerObserver(new Tools_Content_GarbageCollector(array(
				'action' => Tools_System_GarbageCollector::CLEAN_ONUPDATE
			)));
            $sContainer->setContent($data['widgetContent']);
            Application_Model_Mappers_ContainerMapper::getInstance()->save($sContainer);
			$sContainer->notifyObservers();
        }
		if( in_array($data['widgetName'], $this->_seoWidgets)) {
			$this->_putSeoWidget($data['widgetName'], $widget);
		}

        if(!$result) {
            $this->_responseHelper->fail($this->_translator->translate('Widget with this name already exists.'));
            return false;
        }
        elseif($result == Netcontent_Models_Mapper_Netcontent::RESULT_UPDATED) {
            $this->_responseHelper->success($this->_translator->translate('Widget updated'));
            return true;
        }
        $this->_responseHelper->success($this->_translator->translate('Widget added'));
    }

    public function deleteSambaNetContentWidget($data) {
        $result = Netcontent_Models_Mapper_Netcontent::getInstance()->deleteWidget($data['widgetName'], $data['p2p']);
        $this->_removeWidgetOccurence($data['widgetName']);
        if($result) {
            $this->_responseHelper->success($this->_translator->translate('Widget deleted.'));
            return true;
        }
        $this->_responseHelper->fail($this->_translator->translate('An error occured!'));
    }

    /**
     * Modified version of the Plugin's Garbage collector method
     *
     * Removes all widget occurences from the content and templates
     * @param Netcontent_Models_Model_Netcontent | string $widget Net content model instance or name
     */
    private function _removeWidgetOccurence($widget) {
        if(!$widget instanceof Netcontent_Models_Model_Netcontent) {
			$widgetName = $widget;
            $widget = Netcontent_Models_Mapper_Netcontent::getInstance()->findByName($widgetName);
			if($widget == null) {
				$widget = new Netcontent_Models_Model_Netcontent(array('widgetName' => $widgetName));
			}
        }
        $pattern = '~{\$plugin:' . strtolower(__CLASS__) . ':'.$widget->getWidgetName().'}~usU';
        //removing plugin occurences from content
        $containerMapper = Application_Model_Mappers_ContainerMapper::getInstance();
        $containers      = $containerMapper->fetchAll();
        if(!empty ($containers)) {
            array_walk($containers, function($container, $key, $data) {
                $container->setContent(preg_replace($data['pattern'], '', $container->getContent()));
                $data['mapper']->save($container);
            }, array('pattern' => $pattern, 'mapper' => $containerMapper));
        }
        unset($containers);
        //removing plugin occurences from the templates
        $templateMapper = Application_Model_Mappers_TemplateMapper::getInstance();
        $templates      = $templateMapper->fetchAll();
        if(!empty ($templates)) {
            array_walk($templates, function($template, $key, $data) {
                $template->setContent(preg_replace($data['pattern'], '', $template->getContent()));
                $data['mapper']->save($template);
            }, array('pattern' => $pattern, 'mapper' => $templateMapper));
        }
        unset($templates);
    }

    /**
     * Tries to make call to the toaster api
     *
     * @param $method Request method. Can be POST, PUT, DELETE, GET
     * @param $apiMethod Method that should be called
     * @param $data Array of data to pass to the api method
     * @return mixed
     */
    private function _apiCall($method, $apiMethod, $data, $localCallParams = array()) {
        if(!($sambaToken = $this->_configHelper->getConfig('sambaToken'))) {
            return array('done' => false);
        }
        try {
            $api        = Tools_Factory_PluginFactory::createPlugin('api', array(), array(
                'websiteUrl' => $this->_websiteUrl
            ));
        } catch (Exceptions_SeotoasterPluginException $spe) {
            return array('done' => false);
        }
        if(empty($localCallParams)) {
            return $api::request($method, $apiMethod, array_merge($data, array(
                'toasterUrl' => $this->_websiteUrl,
                'sambaToken' => $sambaToken
            )));
        }
        else {
            $api->$apiMethod($localCallParams['type'], $localCallParams['data']);
        }
    }

    private function _getWidgetContent() {
		$p2p = isset($this->_options[1]) ? true : false;
        $widget = Netcontent_Models_Mapper_Netcontent::getInstance()->findByName($this->_options[0], $p2p);
        return ($widget === null) ? '' : htmlspecialchars_decode($widget->getContent());
    }

    private function _generateWidgetName() {
        $parsedWebsiteUrl = parse_url($this->_websiteUrl);
        return $parsedWebsiteUrl['host'] . '-' . $this->_requestedParams['widgetName'];
    }

	private function _putSeoWidget($widgetName, $widget) {
		$type = null;
		switch ($widgetName) {
			case 'seoHead':
				$type = 'head';
			break;
			case 'seoTop':
				$type = 'top';
			break;
			case 'seoBottom':
				$type = 'bottom';
			break;
		}
		$this->_apiCall(null, 'putSeoData', null, array('type' => $type, 'data' => htmlspecialchars_decode($widget->getContent())));
	}
}