<?php
/**
 * Newslist magic space. Allows to output news list with custom layout
 *
 * Optionally magicspace can receive options, they are:
 * 1. 'asc'/'desc' - list order direction
 * 2. N - here N is an integer value which tell the magic space how many news items you want to see in results
 * You can pass option in random order
 */
class MagicSpaces_Newslist_Newslist extends Tools_MagicSpaces_Abstract {

    /**
     * Widget name that will be used in the replacement
     *
     */
    const NEWS_WIDGET_NAME    = 'news';

    /**
     * News list order direction. Descending by default.
     *
     * @var string
     */
    protected $_orderDirection = 'DESC';

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

    protected $_listName      = 'noname';

    protected $_listAll      =  true;

    /**
     * Enable pagination
     */
    protected $_pagination    = null;

    /**
     * Main magic space entry point
     *
     * 1. Get the html version of the current page's template
     * 2. Modify news widget by adding the appropriate page id to each of its occurrences and the parse it
     * 3.
     * 4. Output the news list and ..... wait for it ...... profit!
     *
     * @return null|string
     */
    protected function _run() {
        $this->_parseParams();

        $tmpContent     = $this->_content;
        $this->_content = $this->_getCurrentTemplateContent();
        $spaceContent   = $this->_parse();
        $this->_content = $tmpContent;
        $content        = '';
        $translator     = Zend_Registry::get('Zend_Translate');

        if(!$spaceContent) {
            $spaceContent = $this->_parse();
        }

        $request = Zend_Controller_Front::getInstance()->getRequest();
        if(($request->has('list') && ($this->_listName == $request->getParam('list', 'noname'))) || ($request->has('listall') && ($request->getParam('listall') == $this->_listAll))) {
            $this->_tags = $request->getParam('tags', array());
            if(!is_array($this->_tags)) {
                $this->_tags = explode(',', $this->_tags);
            }
        }

        if($this->_limit != null && $this->_pagination) {
            $news = Zend_Paginator::factory(Newslog_Models_Mapper_NewsMapper::getInstance()->fetchAll('published = 1', array('created_at ' . $this->_orderDirection), null, 0, $this->_tags));
            $news->setDefaultItemCountPerPage($this->_limit);

            if($this->_listName == $request->getParam('listname') || $request->getParam('listall') == $this->_listAll) {
                $news->setCurrentPageNumber(filter_var($request->getParam('pnum', 1), FILTER_SANITIZE_NUMBER_INT));
            }

            $view  = new Zend_View(array('scriptPath' => __DIR__ . '/views/'));
            $pager = $view->paginationControl($news, 'Sliding', 'pagination.phtml', array(
                'listName' => $this->_listName,
                'tags'     => urlencode(is_array($this->_tags) ? implode(',', $this->_tags) : $this->_tags)
            ));
        } else {
            $news = Newslog_Models_Mapper_NewsMapper::getInstance()->fetchAll('published = 1', array('created_at ' . $this->_orderDirection), $this->_limit, 0, $this->_tags);
        }

        if(empty($news)) {
            return $translator->translate('You don\'t have news yet');
        }

        $this->_spaceContent = $spaceContent;
        foreach($news as $newsItem) {
            $content .= preg_replace('~{\$' . self::NEWS_WIDGET_NAME . ':(.+)}~uU', '{$' . self::NEWS_WIDGET_NAME . ':' . $newsItem->getPageId() . ':' . $this->_listName . ':$1}', $this->_spaceContent);
        }

        $parser          = new Tools_Content_Parser($content, array());
        $newsListContent = $parser->parseSimple();
        return ($this->_pagination && isset($view)) ? ($newsListContent . $pager) : $newsListContent;
    }

    /**
     * Parse magic space parameters $_params and init appropriate properties
     *
     */
    private function _parseParams() {
        if(!is_array($this->_params)) {
            return false;
        }
        foreach($this->_params as $key => $param) {
            if(!intval($param)) {
                $param = strtolower($param);
                if($param == 'asc' || $param == 'desc') {
                    $this->_orderDirection = $param;
                    continue;
                }
                if($param === 'pag') {
                    $this->_pagination = $param;
                    continue;
                }
                if($param == 'tags') {
                    $this->_tags = isset($this->_params[$key+1]) ? explode(',', $this->_params[$key+1]) : array();
                    continue;
                }
                if(in_array($param, $this->_tags)) {
                    continue;
                }
                $this->_listName = $param;
                continue;
            }
            $this->_limit = intval($param);
        }
    }

    /**
     * Get the html (not parsed) version of the current template
     *
     * @return bool|string
     */
    private function _getCurrentTemplateContent() {
//        $page    = Application_Model_Mappers_PageMapper::getInstance()->find($this->_toasterData['id']);
        $tempate = Application_Model_Mappers_TemplateMapper::getInstance()->find($this->_toasterData['templateId']);
        if(!$tempate instanceof Application_Model_Models_Template) {
            return false;
        }
        return $tempate->getContent();
    }
}
