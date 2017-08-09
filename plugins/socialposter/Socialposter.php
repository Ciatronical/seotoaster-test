<?php
/**
 * Seotoaster 2.0 plugin bootstrap.
 *
 * @todo Add more comments
 * @author Seotoaster core team <core.team@seotoaster.com>
 */

class Socialposter extends Tools_Plugins_Abstract {

	/**
	 * List of action that should be allowed to specific roles
	 *
	 * By default all of actions of your plugin are available to the guest user
	 * @var array
	 */
	const SEOSAMBA_SOCIAL_URL = 'https://mojo.seosamba.com/social/';

    const NEWS_PLUGIN_NAME    = 'newslog';

    /**
     * Help links
     */
    const SECTION_SOCIALPOSTER = 'socialposter';

    const SECTION_PROFILE = 'profile';

    /**
     * Secure token
     */
    const SOCIALPOSTER_SECURE_TOKEN = 'SocialposterToken';

    /**
     * Help links
     * @var array
     */
    private $_helpHashMap  = array(
        self::SECTION_SOCIALPOSTER => 'social-media-marketing-tools.html',
        self::SECTION_PROFILE => 'social-media-marketing-tools.html'
    );

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
	private $_websiteConfig = '';
	
	protected function _init() {

        $this->_websiteConfig = Zend_Controller_Action_HelperBroker::getStaticHelper('config')->getConfig();

        $this->_layout = new Zend_Layout();
        $this->_layout->setLayoutPath(Zend_Layout::getMvcInstance()->getLayoutPath());

        if(($scriptPaths = Zend_Layout::getMvcInstance()->getView()->getScriptPaths()) !== false) {
            $this->_view->setScriptPath($scriptPaths);
        }
        $this->_view->addScriptPath(__DIR__ . '/system/views/');
	}
	
	public function postToNetworksAction(){
        if(Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_PLUGINS)) {

            // fetch prepopulate settings from the toaster config. By default it will prepopulate data. To turn it off put socialPosterPrepopulate settings to 0 in toaster config table
            $prepoulate = Zend_Controller_Action_HelperBroker::getStaticHelper('config')->getConfig('socialPosterPrepopulate');
            $prepoulate = ($prepoulate == '') ? 1 : intval($prepoulate);

            $pageData   = null;

            if (empty($this->_websiteConfig['websiteId'])) {
                $this->_view->registrationHeaderMessage = $this->_translator->translate('Socialposter warning');
            } else {
                if($prepoulate !== 0) {
                    //pre-populate page data to the social poster form
                    $page     = Application_Model_Mappers_PageMapper::getInstance()->findByUrl($this->_request->getParam('url'));
                    $pageData = ($page instanceof Application_Model_Models_Page) ? $page->toArray() : null;

                    if($pageData) {

                        // check if news plugin is installed and page is a news page
                        $plugins = Tools_Plugins_Tools::getEnabledPlugins(true);

                        if(in_array(self::NEWS_PLUGIN_NAME, $plugins) && ($page->getExtraOption(Newslog::OPTION_PAGE_DEFAULT) || $page->getNews())) {
                            $folder = Newslog_Models_Mapper_ConfigurationMapper::getInstance()->fetchConfigParam('folder');
                            if($folder) {
                                $pageData['url'] = $folder . '/' . $pageData['url'];
                            }
                        }
                    }
                }
                $websiteConfig = $this->_websiteConfig;
                $websiteHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('website');
                $websiteUrl = $websiteHelper->getUrl();
                if (isset($websiteConfig['sambaToken']) && (isset($websiteConfig['websiteId']))) {
                    $url = parse_url($websiteUrl);
                    $data['websiteUrl'] = $url['host'];
                    $data['websiteId'] = $websiteConfig['websiteId'];
                    $data['sambaToken'] = $websiteConfig['sambaToken'];
                    $data['command'] = 'getSocialNetworksStatus';
                    $this->_view->mojoWebsiteId = $data['websiteId'];
                    $this->_view->socialNetworkStatuses = Api::request('GET', 'getSocialNetworksStatus', $data);
                }
            }

            $this->_view->pageData = $pageData;
            $this->_view->websiteUrl = $this->_seotoasterData['websiteUrl'];
            $this->_view->helpSection = self::SECTION_SOCIALPOSTER;
            $this->_view->hashMap     = $this->_helpHashMap;
            $this->_layout->content = $this->_view->render('postToNetworks.phtml');
            echo $this->_layout->render();
        }
    }
	
	public function postMessageAction() {
		if(Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_PLUGINS)) {
            $tokenToValidate = $this->_request->getParam(Tools_System_Tools::CSRF_SECURE_TOKEN, false);
            $valid = Tools_System_Tools::validateToken($tokenToValidate, self::SOCIALPOSTER_SECURE_TOKEN);
            if (!$valid) {
                exit;
            }
            $data['socialPost'] = array(
                'post_link'        => $this->_requestedParams['post_link'],
                'post_description' => $this->_requestedParams['post_description'],
                'post_message'     => strip_tags($this->_requestedParams['post_message'])
            );
			$data['socialNetworks'] = $this->_requestedParams['networks'];
			$this->_processResponse($this->_apiCall('post', 'socialPostMessage', $data));
		}
	}
	
	private function _processResponse($response) {
		if($response != null) {
			if(isset($response['done'])) {
				if($response['done'] == true) {
					$this->_responseHelper->success($response['message']);
				}
				else {
					$this->_responseHelper->fail($response['message']);
				}
			}
			else {
				$this->_responseHelper->fail($response);
			}
		}
		else {
			$this->_responseHelper->fail('Unexpected error.');
		}
	}

	private function _apiCall($methodType, $methodName,$data = null) {
        if(isset($this->_websiteConfig['sambaToken']) && (isset($this->_websiteConfig['websiteId'])) ) {
			$url = parse_url($this->_websiteUrl);
			$data['websiteUrl'] = $url['host'];
			$data['websiteId'] = $this->_websiteConfig['websiteId'];
			$data['sambaToken'] = $this->_websiteConfig['sambaToken'];
			$seosambaRequest = Tools_Factory_PluginFactory::createPlugin('api',array(), array('websiteUrl' => $this->_websiteUrl));
			return $seosambaRequest::request($methodType, $methodName, $data);
		}
	}
	/**
	 * Main entry point
	 *
	 * @param array $requestedParams
	 * @return mixed $dispatcherResult
	 */
	/*public function run($requestedParams = array()) {
		$dispatcherResult = parent::run($requestedParams);
		return ($dispatcherResult) ? $dispatcherResult : '';
	}*/

	/**
	 * System hook to allow your plugin do some stuff before toaster controller starts
	 *
	 */
	/*public function beforeController() {

	}*/

	/**
	 * System hook to allow your plugin do some stuff after toaster controller finish its work
	 *
	 */
    public function registerSocialposterAction() {
        $this->_view->registrationHeaderMessage = $this->_translator->translate('Socialposter warning');
        $this->_view->helpSection = self::SECTION_PROFILE;
        $this->_view->hashMap     = $this->_helpHashMap;
        $this->_layout->content = $this->_view->render('postToNetworks.phtml');
        echo $this->_layout->render();
    }

	private function _setSocialposterConnectLink() {
		if(Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_CONTENT)) {
			$configHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('config');
			$config = $configHelper->getConfig();
			if( isset($config['websiteId']) && ($config['websiteId'] != '') ) {
                $this->_view->socialConnectLink = self::SEOSAMBA_SOCIAL_URL . '?w=' . $config['websiteId'];
			}
            else {
                $this->_view->dataUrl = $this->_websiteUrl . 'plugin/socialposter/run/registerSocialposter';
            }
            $this->_injectContent($this->_view->render('socialposterConnectLink.phtml'));
		}
    }
	
	public function afterController() {

        // inject some js into toaster layout
        if(Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_PLUGINS)) {
            Zend_Layout::getMvcInstance()->getView()->inlineScript()->prependFile(
                $this->_websiteUrl . 'plugins/socialposter/web/js/inject.min.js'
            );
        }

		echo $this->_setSocialposterConnectLink();
	}
}
