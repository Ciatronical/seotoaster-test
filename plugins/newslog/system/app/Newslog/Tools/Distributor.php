<?php
/**
 * Distributor.php
 *
 * @author Eugene I. Nezhuta <theneiam@gmail.com>
 * Date: 11/1/12
 * Time: 11:04 AM
 */
class Newslog_Tools_Distributor {

    /**
     * Action that will tell distributor to start receiving news
     *
     */
    const ACTION_RECEIVE = 'receive';

    /**
     * Action that will tell distributor to remove news
     *
     */
    const ACTION_REMOVE  = 'remove';

    /**
     * Instance of the distributor
     *
     * @var Newslog_Tools_Distributor
     */
    private static $_instance = null;

    /**
     * News mapper instance
     *
     * @var Newslog_Models_Mapper_NewsMapper
     */
    private $_newsMapper      = null;

    protected $_cacheHelper;

    private function __construct() {}

    private function __clone() {}

    public static function getInstance() {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function init() {
        $this->_init();
        return $this;
    }

    private function _init() {
        $this->_newsMapper = Newslog_Models_Mapper_NewsMapper::getInstance();
        $this->_cacheHelper = Zend_Controller_Action_HelperBroker::getExistingHelper('cache');
    }

    public function receive($news) {
        $this->_checkNewsFormat($news);
        $this->_init();
        $template = $news['template'];
        unset($news['template']);
        if(Application_Model_Mappers_TemplateMapper::getInstance()->find($template) !== null) {
            array_walk($news,function (&$newsData) use ($template) {
               if (isset($newsData['metaData'])){
                   $metaData = json_decode($newsData['metaData'], true);
                   $metaData['template'] = $template;
                   $newsData['metaData'] = json_encode($metaData);
               }
            });
        }
        foreach($news as $key => $newsItemData) {
            $newsItem = $this->_newsMapper->findByExternalId($newsItemData['id']);
            if($newsItem === null) {
                $newsItem = new Newslog_Models_Model_News();
                $newsItem->setExternalId($newsItemData['id']);
            }

            unset($newsItemData['id']);
            $newsItem->setOptions($newsItemData)
                ->setTags($this->_processTags($newsItemData['tags']))
                ->setType(Newslog_Models_Model_News::TYPE_EXTERNAL)
                ->registerObserver(new Newslog_Tools_Watchdog_News());

            if($newsItem->getId()) {
                $metaData = $newsItem->getMetaData(true);
                $metaData['oldUrl'] = $newsItem->getPage()->getUrl();
                $newsItem->setMetaData($metaData);
            }

            if(isset($newsItemData['preview'])) {
               $newsItem->setMetaData($this->_processTeaserImage($newsItemData['preview'], $newsItem->getMetaData(true)));
            }

            // by default all distributed news will be authored by superaadmin
            $user = Application_Model_Mappers_UserMapper::getInstance()->findByRole(Tools_Security_Acl::ROLE_SUPERADMIN);
            if($user instanceof Application_Model_Models_User) {
                $newsItem->setUserId($user->getId());
            }

            $newsItem->setPublished(true);
            $newsItem = $this->_newsMapper->save($newsItem);
            if(!$newsItem instanceof Newslog_Models_Model_News) {
                throw new Exceptions_NewslogException('Server encountered an error during saving the news.', 500);
            }
            $news[$key] = $newsItem;
        }
        $this->_cacheHelper->clean(false, false,'feeds_news');

        return true;
    }

    public function remove($news) {
        $this->_checkNewsFormat($news);
        $this->_init();
        $mapper = Newslog_Models_Mapper_NewsMapper::getInstance();

        array_walk($news, function($newsItemData) use($mapper) {
                $newsItem = $mapper->findByExternalId($newsItemData['id']);
                $newsItem->registerObserver(new Newslog_Tools_Watchdog_News(array(
                        'action' => Tools_System_GarbageCollector::CLEAN_ONDELETE
                    )));
                $mapper->delete($newsItem);
        });

        $this->_cacheHelper->clean(false, false,'feeds_news');

        return true;
    }

    private function _checkNewsFormat($news) {
        if(!is_array($news)) {
            throw new Exceptions_NewslogException('Cant not receive news. Not valid format.', 500);
        }
        if(empty($news)) {
            throw new Exceptions_NewslogException('Cant not receive news. Empty array received.', 500);
        }
    }

    private function _processTeaserImage($teaserImageData, $newsItemMetaData) {
        $websiteHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('website');
        $pageHelper    = Zend_Controller_Action_HelperBroker::getStaticHelper('page');
        $image         = base64_decode($teaserImageData['image']);
        $imageName     = $pageHelper->clean($pageHelper->filterUrl($newsItemMetaData['url'])) . '.' . $teaserImageData['extension'];
        try {
            Tools_Filesystem_Tools::saveFile($websiteHelper->getPath() . $websiteHelper->getPreview() . $imageName, $image);
            $newsItemMetaData['image'] = $websiteHelper->getUrl() . $websiteHelper->getPreview() . $imageName;
        } catch (Exceptions_SeotoasterException $se) {
            error_log($se->getMessage() . "\n" . $se->getTraceAsString());
        }
        return $newsItemMetaData;
    }

    private function _processTags($tags) {
        if(!is_array($tags)) {
            return array();
        }
        $tagsMapper = Newslog_Models_Mapper_TagMapper::getInstance();
        return array_map(function($tag) use ($tagsMapper) {
            if(!($existingTag = $tagsMapper->findByName($tag['name']))) {
                unset($tag['id']);
                $tag = $tagsMapper->save(new Newslog_Models_Model_Tag($tag));
                return array('id' => $tag->getId(), 'name' => $tag->getName());
            }
            return array('id' => $existingTag->getId(), 'name' => $existingTag->getName());
        }, $tags);
    }
}
