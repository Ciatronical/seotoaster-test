<?php
/**
 *
 */
class Newslog_Tools_Feed {

    const TYPE_ALL      = 'all';

    const TYPE_FULL     = 'full';

    /**
     * @var Newslog_Tools_Feed
     */
    private static $_instance = null;

    private function __construct() {}

    private function __clone() {}

    public static function getInstance() {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }



    public function generate($type = self::ALL) {
        if ($type === self::TYPE_ALL || $type === self::TYPE_FULL) {
            $news = Newslog_Models_Mapper_NewsMapper::getInstance()->fetchAll(null,
                array('created_at DESC'));
        } else {
            $news = Newslog_Models_Mapper_NewsMapper::getInstance()->fetchAll(null,
                array('created_at DESC'), null, null, array($type));
        }

        if (empty($news)) {
            Tools_System_Tools::debugMode() || error_log('Cant generate xml feed. No news found.');
            return false;
        }

        $feedXml = $this->_generate($news, $type);
        return !empty($feedXml) ? $feedXml : false ;
    }

    private function _generate($news, $feedName) {

        $websiteHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('website');
        $miscConfig    = Zend_Registry::get('misc');
        $view          = new Zend_View(array(
            'scriptPath' => $websiteHelper->getPath() . $miscConfig['pluginsPath'] . 'newslog/system/views/'
        ));

        $view->news          = $news;
        $view->feedType      = $feedName;
        $view->websiteUrl    = $websiteHelper->getUrl();
        $view->websitePath   = $websiteHelper->getPath();
        $view->newsConfig    = Newslog_Models_Mapper_ConfigurationMapper::getInstance()->fetchConfigParams();
        $view->websiteConfig = Application_Model_Mappers_ConfigMapper::getInstance()->getConfig();

        try {
            $cacheHelper = Zend_Controller_Action_HelperBroker::getExistingHelper('cache');
            if (null === ($sitemapContent = $cacheHelper->load($feedName, Helpers_Action_Cache::PREFIX_FEEDS))) {
                try {
                    $sitemapContent = $view->render('feed.phtml');
                } catch (Zend_View_Exception $zve) {
                    return $this->forward('index', 'index', null, array('page' => 'feeds' . $feedName . '.xml'));
                }
                $cacheHelper->save($feedName, $sitemapContent, Helpers_Action_Cache::PREFIX_FEEDS . 'news', array('feeds_news'), Helpers_Action_Cache::CACHE_WEEK);
            }
            return $sitemapContent;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

}
