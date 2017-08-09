<?php
/**
 * News rest service api
 *
 * @author: iamne <eugene@seotoaster.com> Seotoaster core team
 * Date: 7/3/12
 * Time: 5:34 PM
 */
class Api_Newslog_News extends Api_Service_Abstract {

    const NEWS_SECURE_TOKEN =  'News';

    protected $_cacheHelper;

    /**
     * @var Newslog_Models_Mapper_NewsMapper
     */
    protected $_newsMapper = null;

    protected $_accessList = array(
        Tools_Security_Acl::ROLE_GUEST => array('allow' => array('get')),
        Tools_Security_Acl::ROLE_ADMIN => array('allow' => array('get', 'post', 'put', 'delete')),
        Tools_Security_Acl::ROLE_SUPERADMIN => array('allow' => array('get', 'post', 'put', 'delete'))
    );

    public function init() {
        $this->_newsMapper = Newslog_Models_Mapper_NewsMapper::getInstance();
        $this->_cacheHelper = Zend_Controller_Action_HelperBroker::getExistingHelper('cache');
    }

    /**
     * Retreive news list or news item if id specified otherwise - 404 not found status returned
     *
     * For the news list additional parameters could be passed:
     * offset - list will start from this number
     * limit - number of the items in the list
     * order - how list will be ordered
     * tags - if this parameter is specified list will contain only news which contain those tags
     *
     * @return array
     */
    public function getAction() {
        $newsId = filter_var($this->_request->getParam('id'), FILTER_SANITIZE_NUMBER_INT);
        if($newsId) {
            $news = $this->_newsMapper->find($newsId);
            if($news instanceof Newslog_Models_Model_News) {
                $pageHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('page');
                $metaData        = $news->getMetaData(true);
                $metaData['url'] = $pageHelper->clean($metaData['url']);
                return $news->setMetaData($metaData)
                    ->toArray();
            }

            //news item can not be found
            $this->_error('404 News item can not be found!', self::REST_STATUS_NOT_FOUND);
        }

        //retrieve and validate additional parameters
        $offset = filter_var($this->_request->getParam('offset'), FILTER_SANITIZE_NUMBER_INT);
        $limit  = filter_var($this->_request->getParam('limit'), FILTER_SANITIZE_NUMBER_INT);
        $order  = filter_var($this->_request->getParam('order'), FILTER_SANITIZE_STRING);
        $search = filter_var($this->_request->getParam('search'), FILTER_SANITIZE_STRING);
        $tags   = (($tags = filter_var($this->_request->getParam('tags', false), FILTER_SANITIZE_STRING)) != '') ? explode(',', $tags) : array();
        $count = (bool)$this->_request->has('count');

        //fetch all news
        $news   = $this->_newsMapper->fetchAll(
            null,
            ($order)  ? array('created_at ' . strtoupper($order)) : array(),
            ($limit)  ? $limit : null,
            ($offset) ? $offset : null, $tags,
            ($search) ? $search : null,
            $count
        );

        $websiteHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('website');
        return ($count) ? $news : array_map(function($newsItem) use ($websiteHelper) {
            $newsItemMeta = $newsItem->getMetaData(true);

            if(!file_exists($websiteHelper->getPath() . $websiteHelper->getPreview() . $newsItemMeta['image'])) {
                $newsItemMeta['image'] =  null;
            }

            return $newsItem->setMetaData($newsItemMeta)
                ->toArray();
        }, $news);
    }

    /**
     * Add new news item to the database
     *
     * @return mixed
     */
    public function postAction() {
        $data = Zend_Json::decode($this->_request->getRawBody());
        $tokenToValidate = $data[Tools_System_Tools::CSRF_SECURE_TOKEN];
        $valid = Tools_System_Tools::validateToken($tokenToValidate, self::NEWS_SECURE_TOKEN);
        if (!$valid) {
            exit;
        }
        unset($data[Tools_System_Tools::CSRF_SECURE_TOKEN]);
        return $this->_saveNewsItem($data);
    }

    /**
     * Update an existing news item
     *
     * @return mixed
     */
    public function putAction() {
        return $this->_saveNewsItem(Zend_Json::decode($this->_request->getRawBody()));
    }

    /**
     * Delete news item by news id
     *
     * Register news garbage collector observer Newslog_Tools_Watchdog_News to clean related news page
     * @return mixed
     */
    public function deleteAction() {
        $id = filter_var($this->_request->getParam('id'), FILTER_SANITIZE_NUMBER_INT);
        if($id) {
            $newsItem = $this->_newsMapper->find($id);
            $newsItem->registerObserver(new Newslog_Tools_Watchdog_News(array(
                'action' => Tools_System_GarbageCollector::CLEAN_ONDELETE
            )));
            $deleteResult = $this->_newsMapper->delete($newsItem);
            $this->_cacheHelper->clean(false, false,'feeds_news');
            return $deleteResult;
        }
        $this->_error('Unknown news entry', self::REST_STATUS_NOT_FOUND);
    }

    /**
     * Create / Update news item
     *
     * Register news watchdog observer Newslog_Tools_Watchdog_News to create / update relevant news page
     * @param array $newsData
     * @return mixed
     */
    protected function _saveNewsItem(array $newsData) {
        $newsItem = new Newslog_Models_Model_News($newsData);
        if (!empty($newsData['createdAt'])) {
            $newsItem->setCreatedAt(date("Y-m-d", strtotime($newsData['createdAt'])).' '.date("H:i:s", strtotime('now')));
        }

        if (!empty($newsData['eventDate'])) {
            $newsItem->setEventDate(date("Y-m-d", strtotime($newsData['eventDate'])).' '.date("H:i:s", strtotime('now')));
        }
        $userId   = Zend_Controller_Action_HelperBroker::getStaticHelper('session')->getCurrentUser()->getId();


        $newsItem->setUserId($userId)
            ->registerObserver(new Newslog_Tools_Watchdog_News())
            ->registerObserver(new Newslog_Tools_Watchdog_Ping());

        try {
            $newsItem = $this->_newsMapper->save($newsItem);
            if(!$newsItem instanceof Newslog_Models_Model_News) {
                $this->_error('Server encountered an error during the last request');
            }
            $this->_cacheHelper->clean(false, false,'feeds_news');

        } catch (Exception $e) {
            if(Tools_System_Tools::debugMode()) {
                error_log($e->getMessage());
            }
            $this->_error($e->getMessage());
        }

        return $newsItem->toArray();
    }

}
