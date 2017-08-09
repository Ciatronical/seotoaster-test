<?php
/**
 * Seotoaster 2.0 newslog plugin.
 *
 * @todo Add more comments
 * @author Seotoaster core team <core.team@seotoaster.com>
 */

class Newslog extends Tools_Plugins_Abstract {

    const VIEWS_SCREENS_POSTFIX = '.newslog.phtml';

    const ROUTE_NAME            = 'newslog_route_default';

    const ROUTE_INDEX_NAME      = 'newslog_route_index';

    const NEWS_DEFAULT_LIMIT    = 10;

    const NEWS_PAGE_TYPE        = 3;

    /**
     * Seotoaster route, used to serve regular seotoaster pages
     */
    const ROUTE_DEFAULT_NAME    = 'default';

    const DEFAULT_INDEX         = 'd36637530217f0d6b0547a6e0e970d60';

    const OPTION_PAGE_INDEX     = 'option_newsindex';

    const OPTION_PAGE_DEFAULT   = 'option_newspage';

    const TEMPLATE_TYPE         = 'type_news';

    const DEFAULT_NEWS_FOLDER   = 'news/';

    /**
     * Help links
     */

    const SECTION_NEWS = 'news';

    const SECTION_ORGANIZE = 'organize';

    const SECTION_PREFERENCES = 'preferences';

    /**
     * secure token
     */
    const NEWS_PREFERENCES_SECURE_TOKEN = 'NewsPreferences';

    /**
     * Layout script to use to render news plugin screens
     *
     * @var null
     */
    private $_layout        = null;

    /**
     * Newslog preferences
     *
     * @var array
     */
    private $_settings = array();

    /**
     * Instance of the Application_Model_Models_Page with 'option_news_index' option
     *
     * @var Application_Model_Models_Page
     */
    private $_newsIndexPage = null;

    /**
     * Current page id
     *
     * @var integer
     */
    private $_currentPageId = 0;


    /**
     * Help links data
     *
     * @var array
     */
    private $_helperHashMap = array(
        self::SECTION_NEWS => 'blog-system-open-source.html',
        self::SECTION_ORGANIZE => 'blog-system-open-source.html',
        self::SECTION_PREFERENCES => 'blog-system-open-source.html'
    );

	/**
	 * List of action that should be allowed to specific roles
	 *
	 * By default all actions of your plugin are available to the guest user
	 * @var array
	 */
	protected $_securedActions = array(
		Tools_Security_Acl::ROLE_SUPERADMIN => array('news', 'preferences'),
        Tools_Security_Acl::ROLE_ADMIN      => array('news', 'preferences'),
        Tools_Security_Acl::ROLE_USER       => array('news')
	);

	protected function _init() {
        $this->_layout = new Zend_Layout();
        $this->_layout->setLayoutPath(Zend_Layout::getMvcInstance()->getLayoutPath());

        if(($scriptPaths = Zend_Layout::getMvcInstance()->getView()->getScriptPaths()) !== false) {
            $this->_view->setScriptPath($scriptPaths);
        }
		$this->_view->addScriptPath(__DIR__ . '/system/views/');

        $this->_settings           = Newslog_Models_Mapper_ConfigurationMapper::getInstance()->fetchConfigParams();
        $this->_settings['folder'] = !isset($this->_settings['folder']) ? self::DEFAULT_NEWS_FOLDER : (trim($this->_settings['folder'], '/') . '/');
	}

    public static function getNewsFolder() {
        return trim(Newslog_Models_Mapper_ConfigurationMapper::getInstance()->fetchConfigParam('folder'), '/') . '/';
    }

	/**
	 * Before controller hook. Solving the news specific routes
	 *
	 */
	public function beforeController() {
        $routeName = Zend_Controller_Front::getInstance()->getRouter()->getCurrentRouteName();
        $page      = Newslog_Tools_Misc::loadPageByUrl(filter_var($this->_request->getParam('page'), FILTER_SANITIZE_STRING));

        if(!$page instanceof Application_Model_Models_Page) {
            return false;
        }

        switch($routeName) {
            case self::ROUTE_INDEX_NAME:
                $this->_request->setParam('page', $page->getUrl());
            break;
            case self::ROUTE_NAME:
                if($page->getExtraOption(self::OPTION_PAGE_INDEX)) {
                    $this->_redirector->gotoUrl($this->_websiteUrl . $this->_settings['folder']);
                }
                $this->_currentPageId = $page->getId();
                $this->_appendToLayout();
            break;
            case self::ROUTE_DEFAULT_NAME:
                if($page->getExtraOption(self::OPTION_PAGE_INDEX)) {
                    $this->_redirector->gotoUrl($this->_websiteUrl . $this->_settings['folder']);
                }
                if($page->getExtraOption(self::OPTION_PAGE_DEFAULT) || $page->getNews()) {
                    $this->_redirector->gotoUrl($this->_websiteUrl . $this->_settings['folder'] . $page->getUrl());
                }
            break;
        }
        return true;
	}

    /**
     * Rewrite the 'canonical' link to include a news folder
     *
     */
    public function afterController() {
        $view         = Zend_Layout::getMvcInstance()->getView();
        $canonicalUrl = $view->canonicalUrl;
        $pageUrl      = $view->pageData['url'];

        // rewrite a canonical for a regular news page
        if($view->pageData['news'] == 1) {
            $canonicalUrl = str_replace($pageUrl, $this->_settings['folder'] . $pageUrl, $canonicalUrl);
        }
        // rewrite a canonical for news index page
        if(($this->_newsIndexPage instanceof Application_Model_Models_Page) && ($view->pageData['url'] == $this->_newsIndexPage->getUrl())) {
            $canonicalUrl = str_replace($pageUrl, $this->_settings['folder'], $canonicalUrl);
        }
        $view->canonicalUrl = $canonicalUrl;

        Zend_Layout::getMvcInstance()->setView($view);
    }

    /**
     * Before router hook
     *
     */
    public function beforeRouter() {
        // if news folder is not set then we don't need to do any routes
        if(!$this->_settings['folder']) {
            return;
        }

        $router = Zend_Controller_Front::getInstance()->getRouter();

        $requestUri = $this->_request->getRequestUri();
        if(!preg_match('~^(\/go|\/logout\/|\/sitemap(.*)\.xml|\/dashboard\/)$~', $requestUri)){
            $currentUrl =  $this->_request->getScheme() . '://' . $this->_request->getHttpHost() . '/' . strtr(trim(rawurldecode($requestUri),'/'), array('\'' => '', '"' => '', PHP_EOL => ''));
            $currentUrl = str_replace('\\', '', $currentUrl);
            $redirectMapper = Application_Model_Mappers_RedirectMapper::getInstance();
            $where = $redirectMapper->getDbTable()->getAdapter()->quoteInto("CONCAT(domain_from, from_url) = ?", $currentUrl);
            $redirectFromPage = $redirectMapper->getDbTable()->getAdapter()->fetchRow(
                $redirectMapper->getDbTable()->getAdapter()->select()->from('redirect', '*')->where($where)
            );
            if (empty($redirectFromPage)) {
                //add news page route
                $router->addRoute(
                    self::ROUTE_NAME,
                    new Zend_Controller_Router_Route($this->_settings['folder'] . ':page', array(
                        'controller' => 'index',
                        'action' => 'index',
                        'page' => self::DEFAULT_INDEX
                    ))
                );

                //add news index route
                $router->addRoute(self::ROUTE_INDEX_NAME,
                    new Zend_Controller_Router_Route($this->_settings['folder'], array(
                        'controller' => 'index',
                        'action'     => 'index',
                        'page'       => self::DEFAULT_INDEX
                    ))
                );
            }
        }
    }

    /**
     * Show add / edit news screen
     *
     */
    public function newsAction() {
        $this->_view->newPostForm = new Newslog_Forms_Post();
        $this->_show();
    }

    /**
     * Show newslog config screen
     *
     */
    public function preferencesAction() {
        $form        = new Newslog_Forms_Configuration();
        $authorsForm = new Newslog_Forms_Authors();

        if($this->_request->isPost()) {
            $tokenToValidate = $this->_request->getParam(Tools_System_Tools::CSRF_SECURE_TOKEN);
            $valid = Tools_System_Tools::validateToken($tokenToValidate, self::NEWS_PREFERENCES_SECURE_TOKEN);
            if (!$valid) {
                exit;
            }
            $option = $this->_request->getParam('opt');
            $form   = ($option == 'folder') ? $form : $authorsForm;

            if(!$form->isValid($this->_request->getParams())) {
                $this->_responseHelper->fail(join('<br />', $form->getMessages()));
            }

            $formData = $form->getValues();

            if($option == 'folder') {
                $this->_switchBlogOption($formData['folder']);

                // notify mojo about news folder update
                $configHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('config');
                $sambaToken   = $configHelper->getConfig('sambaToken');
                if($sambaToken) {
                    $data = array(
                        'sambaToken' => $sambaToken,
                        'folder'     => $formData['folder'],
                        'website'    => $this->_websiteUrl,
                        'hash'       => sha1($formData['folder'] . $this->_websiteUrl)
                    );
                    Api::request('put', 'news', $data);
                }
            }


            if(Newslog_Models_Mapper_ConfigurationMapper::getInstance()->save($formData)) {
                $this->_responseHelper->success($this->_translator->translate('Configuration updated'));
            }
            $this->_responseHelper->fail($this->_translator->translate('Cannot update configuration.'));
        }

        $this->_settings['folder'] = rtrim($this->_settings['folder'], '/');
        $form->populate($this->_settings);
        $authorsForm->populate($this->_settings);

        $this->_view->form        = $form;
        $this->_view->authorsForm = $authorsForm;

        $parsedUrl           = parse_url($this->_websiteUrl);
        $this->_view->domain = $parsedUrl['host'];

        $this->_show();
    }

    /**
     * Toggle news index option and news index page according to the newsfolder setting
     *
     * @param $newsFolder
     */
    private function _switchBlogOption($newsFolder) {
        $pageOptionMapper = Application_Model_Mappers_PageOptionMapper::getInstance();
        //load page news index option

        $pageOption = $pageOptionMapper->find(self::OPTION_PAGE_INDEX);
        //$pageOption->setActive((boolean)$newsFolder);
        $pageOptionMapper->save($pageOption);

        //news folder is empty we assume the whole site is a blog and disable new inewx page option
        if(!$newsFolder) {
            //find current news index page if exists
            if($this->_newsIndexPage instanceof Application_Model_Models_Page) {
                $this->_newsIndexPage->setExtraOptions(array(), true);
                Application_Model_Mappers_PageMapper::getInstance()->save($this->_newsIndexPage);
            }
        }
    }

    /**
     * Generates sitemapnews.xml content
     *
     * @return string
     */
    public static function getSitemapNews() {
        $view             = new Zend_View(array('scriptPath' => __DIR__ . '/system/views'));
        $view->news       = Newslog_Models_Mapper_NewsMapper::getInstance()->fetchAll();
        $view->folder     = Newslog_Models_Mapper_ConfigurationMapper::getInstance()->fetchConfigParam('folder') . '/';
        $view->language   = substr(Zend_Locale::getLocaleToTerritory(Zend_Controller_Action_HelperBroker::getStaticHelper('config')->getConfig('language')), 0, 2);
        $view->websiteUrl = Zend_Controller_Action_HelperBroker::getStaticHelper('website')->getUrl();
        return $view->render('sitemap.phtml');
    }

    /**
     * Toaster themes system export hook
     *
     * @todo implement
     * @return array
     */
    public static function exportWebsiteData() {
        $dbAdapter = Zend_Registry::get('dbAdapter');
        return array('pages' => $dbAdapter->fetchAll("SELECT `p`.* FROM `page` as `p` JOIN `plugin_newslog_news` ON `p`.`id` = `plugin_newslog_news`.`page_id`"));
    }

    /**
     * Render a proper view script
     *
     * If $screenViewScript not passed, generates view script file name automatically using the action name and VIEWS_SCREENS_POSTFIX
     * @param string $screenViewScript
     * @param boolean $useLayout
     */
    private function _show($screenViewScript = '', $useLayout = true) {
        $data = $this->_request->getParams();
        if(!$screenViewScript) {
            $trace  = debug_backtrace(false);
            $screenViewScript = str_ireplace('Action', self::VIEWS_SCREENS_POSTFIX, $trace[1]['function']);
        }
        if(!$useLayout) {
            echo $this->_view->render($screenViewScript);
            return;
        }
        if(isset($data['organize'])){
            $this->_view->organize = true;
        }
        $this->_view->helpSection = $data['run'];
        $this->_view->hashMap = $this->_helperHashMap;
        $this->_layout->content = $this->_view->render($screenViewScript);
        echo $this->_layout->render();
    }

    /**
     * Append some js and content to the layout
     *
     */
    private function _appendToLayout() {
        //Zend_Layout::getMvcInstance()->getView()->placeholder('logoSource')->set('http://backbonejs.org/docs/images/backbone.png');
        $newsPost = Newslog_Models_Mapper_NewsMapper::getInstance()->findByPageId($this->_currentPageId);
        if(!$newsPost) {
            return false;
        }
        $this->_view->newsPostId = $newsPost->getId();
        $this->_view->newsFolder = $this->_settings['folder'];
        $this->_view->isPR       = $newsPost->isPressRelease();

        $this->_injectContent($this->_view->render('inject.newslog.phtml'));
    }

    public static function getTabContent() {
        $translator = Zend_Registry::get('Zend_Translate');
        $view = new Zend_View(array(
            'scriptPath' => __DIR__ . '/system/views'
        ));
        $websiteHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('website');
        $configHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('config');
        $view->websiteUrl = $websiteHelper->getUrl();

        //getting news listing templates
        $view->newsTemplates = Application_Model_Mappers_TemplateMapper::getInstance()->findByType(Application_Model_Models_Template::TYPE_NEWS_LISTING);
        return array(
            'title'   => '<span id="news">' . $translator->translate('News') . '</span>',
            'content' => $view->render('uitab.phtml')
        );
    }
}
