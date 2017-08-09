<?php
/**
 * News list widget {$newslist:template_name[:list_name[:per_page[:asc|desc[:pgr[:tags:tag1,tag2, ..., tagN]]]]}
 *
 * template_name -required parameter, represents news list template that will be loaded for rendering
 * list_name - optional, unique news list identifier
 * per_page - optional parameter, how many news items should be displayed in list
 * asc|desc - order direction. DESC used by default
 * pgr - use pager or not
 * tags:tag1,tag2,...,tagN - List of tags to filter a news list
 *
 * User: Eugene I. Nezhuta <eugene@seosamba.com>
 * Date: 10/25/13
 * Time: 5:43 PM
 */

class Widgets_Newslist_Newslist extends Widgets_Abstract {

    /**
     * News widget name in the system
     *
     */
    const NEWS_WIDGET_NAME  = 'news';

    /**
     * List order direction (LOD) ascendant
     *
     */
    const LOD_ASC           = 'asc';

    /**
     * List order direction (LOD) descendant
     *
     */
    const LOD_DESC          = 'desc';

    /**
     * List option tags
     *
     */
    const OPT_TAGS          = 'tags';

    /**
     * List options pager
     *
     */
    const OPT_PAGER         = 'pgr';

    /**
     * Default news list name
     *
     */
    const DEFAULT_LIST_NAME = 'newslist';

    /**
     * News list order direction. Descending by default.
     *
     * @var string
     */
    protected $_orderDirection = self::LOD_DESC;

    /**
     * How many news items should be in news list. Leave null to get everything.
     *
     * @var mixed integer|null
     */
    protected $_limit         = null;

    /**
     * Display news that have specific tags
     *
     * @var array
     */
    protected $_tags          = array();

    /**
     * News list name. Used for the filtering by tags and pagination
     *
     * @var string
     */
    protected $_listName      = self::DEFAULT_LIST_NAME;

    /**
     * News filter flag. Used for the filtering by tags and pagination in all lists
     *
     * @var string
     */
    protected $_listAll      = true;

    /**
     * Use pagination or not in news list
     *
     * @var bool
     */
    protected $_pagination    = false;

    /**
     * Do not cache news list widget by default
     *
     * @var bool
     */
    protected $_cacheable       = false;

    /**
     * If true widget will also put a record to the error log file
     *
     * @var bool
     */
    private $_debugMode         = false;

    /**
     * News mapper
     *
     * @var Newslog_Models_Mapper_NewsMapper
     */
    protected $_mapper          = null;

    /**
     * Website helper with a lot of useful data
     *
     * @var null
     */
    protected $_websiteHelper   = null;

    protected function _init() {
        $this->_view             = new Zend_View(array('scriptPath' => __DIR__ . '/views'));
        $this->_view->setHelperPath(APPLICATION_PATH . '/views/helpers/');
        $this->_view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');

        $this->_websiteHelper    = Zend_Controller_Action_HelperBroker::getStaticHelper('website');
        $this->_view->websiteUrl = $this->_websiteHelper->getUrl();
        $this->_mapper           = Newslog_Models_Mapper_NewsMapper::getInstance();
        $this->_debugMode        = Tools_System_Tools::debugMode();
    }

    protected function _load() {
        // loading news list template
        $templateName = array_shift($this->_options);
        $this->_config = Zend_Controller_Action_HelperBroker::getStaticHelper('config');

        // if developerMode = 1, parsing template directly from files
        if ((bool) $this->_config->getConfig('enableDeveloperMode')) {
            $websitePath  = $this->_toasterOptions['websitePath'];
            $themePath    = $this->_toasterOptions['themePath'];
            $currentTheme = $this->_toasterOptions['currentTheme'];
            $templatePath = $websitePath.$themePath.$currentTheme.DIRECTORY_SEPARATOR.$templateName.'.html';
            if (file_exists($templatePath)) {
                $templateContent = Tools_Filesystem_Tools::getFile($templatePath);
            } else {
                $templateContent = '<span style="color: red;">News list template "<em>' . $templateName . '</em>" not found</span>';
            }
        }else{
            $template = Application_Model_Mappers_TemplateMapper::getInstance()->find($templateName);
            if($template === null) {
                $templateContent = '<span style="color: red;">News list template "<em>' . $templateName . '</em>" not found</span>';
            }else {
                $templateContent = $template->getContent();
            }
        }
        // parse options passed to the widget and init proper vars
        $this->_parseOptions();

        // Check the request for pagination or tags filtering
        $request = Zend_Controller_Front::getInstance()->getRequest();
        if(($request->has('list') && ($this->_listName == $request->getParam('list', self::DEFAULT_LIST_NAME))) || ($request->has('listall') && ($this->_listAll == $request->getParam('listall')))) {
            $this->_tags = $request->getParam('tags', array());
            if(!is_array($this->_tags)) {
                $this->_tags = explode(',', $this->_tags);
            }
        }

        // loading news
        if($this->_pagination) {
            // @todo Optimize pagination to work with db provider to query only needed portion of news items
            $news = Zend_Paginator::factory($this->_mapper->fetchAll('published = 1', array('created_at ' . $this->_orderDirection), null, 0, $this->_tags));
            $news->setDefaultItemCountPerPage($this->_limit);
            $news->setCurrentPageNumber(1);
            if($this->_listName == $request->getParam('list', self::DEFAULT_LIST_NAME) || ($this->_listAll == $request->getParam('listall'))) {
                $news->setCurrentPageNumber(filter_var($request->getParam('pg', 1), FILTER_SANITIZE_NUMBER_INT));
            }

            //initialize view to render pagination controls
            $view  = new Zend_View(array('scriptPath' => __DIR__ . '/views/'));
            $pager = $view->paginationControl($news, 'Sliding', 'pagination.phtml', array(
                'listName' => $this->_listName,
                'tags'     => urlencode(is_array($this->_tags) ? implode(',', $this->_tags) : $this->_tags)
            ));
        } else {
            $news = $this->_mapper->fetchAll('published = 1', array('created_at ' . $this->_orderDirection), $this->_limit, 0, $this->_tags);
        }

        if (empty($news)) {
            if (Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_PLUGINS)) {
                return $this->_translator->translate('You don\'t have news yet');
            } else {
                return '';
            }
        }

        // Calculation template content once and init prepared list content
        $listContent     = '';

        // prepare correct news list content
        foreach($news as $item) {
            $listContent .= preg_replace('~{\$' . self::NEWS_WIDGET_NAME . ':(.+)}~uU', '{$' . self::NEWS_WIDGET_NAME . ':' . $item->getPageId() . ':$1}', $templateContent);
        }

        // parse news list content via parser (native widget will be used)
        $parser      = new Tools_Content_Parser($listContent, array());
        $listContent = $parser->parseSimple();
        return ($this->_pagination && isset($view)) ? ($listContent . $pager) : $listContent;
    }

    private function _parseOptions() {
        if(is_array($this->_options) && !empty($this->_options)) {
            foreach($this->_options as $key => $option) {
                // if option is an integer value we assume 'limit' (per_page) passed
                $limit = intval($option);
                if($limit) {
                    $this->_limit = $limit;
                    continue;
                }

                // if options is one of the order direction values we assume new order direction passed
                if($option == self::LOD_ASC || $option == self::LOD_DESC) {
                    $this->_orderDirection = $option;
                    continue;
                }
                // if option is 'tags' check if tags are passed.
                if($option == self::OPT_TAGS) {
                    $tagsSpot    = $key + 1;
                    $this->_tags = isset($this->_options[$tagsSpot]) ? explode(',', $this->_options[$tagsSpot]) : array();
                    unset($this->_options[$tagsSpot]);
                    continue;
                }
                // if option is pgr we assume that pagination is needed
                if($option == self::OPT_PAGER) {
                    $this->_pagination = true;
                    $this->_limit      = $this->_limit ? $this->_limit : 16;
                    continue;
                }
                // if option is list tag, skip it
                //if(in_array($option, $this->_tags)) {continue;}
                // if option didn't go anywhere assume this is a list name
                $this->_listName = $option;
                continue;
            }
        }
    }
}