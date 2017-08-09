    <?php
/**
 * News
 *
 * @author: iamne <eugene@seotoaster.com> Seotoaster core team
 * Date: 7/26/12
 * Time: 12:30 PM
 */
class Widgets_News_News extends Widgets_Abstract {

    const ORDER_ASC       = 'asc';

    const ORDER_DESC      = 'desc';

    const USE_IMAGE       = 'img';

//    protected $_cacheable = false;

    /**
     * If true widget will also put a record to teh error log file
     *
     * @var bool
     */
    private $_debugMode         = false;

    /**
     * @var Simpleblog_Models_Mapper_PostMapper
     */
    protected $_mapper          = null;

    protected $_websiteHelper   = null;

    protected function _init() {
        $this->_view             = new Zend_View(array('scriptPath' => __DIR__ . '/views'));
        $this->_view->setHelperPath(APPLICATION_PATH . '/views/helpers/');
        $this->_view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');

        $this->_websiteHelper    = Zend_Controller_Action_HelperBroker::getStaticHelper('website');
        $this->_view->websiteUrl = $this->_websiteHelper->getUrl();
        $this->_mapper           = Newslog_Models_Mapper_NewsMapper::getInstance();
        $this->_debugMode        = Tools_System_Tools::debugMode();

        //check the first option if it's integer we assume the page id is passed
        if(isset($this->_options[0]) && intval($this->_options[0])) {
            $this->_toasterOptions['id'] = array_shift($this->_options);
            array_push($this->_cacheTags, 'pageid_'.$this->_toasterOptions['id']);
        }

        if (is_array($this->_options) && in_array('actions', $this->_options)) {
            $this->_cacheable = false;
        }
        // set default news list name
        $this->_toasterOptions['newsListName'] = 'newslist';

        //get parent magic space name
        $array_options = array('gplus', 'date', 'item', 'preview', 'tagcloud', 'event');
        $customOptionParams = array('nolinks', 'inline');
        if (sizeof($this->_options) >= 2 && !in_array($this->_options[0], $array_options) && !in_array($this->_options[1], $customOptionParams)){
           $this->_toasterOptions['newsListName'] = array_shift($this->_options);
        }
    }

    protected function _load() {
        //backward compatibility fix :( This will be removed in next versions of the plugin
        if(isset($this->_options[0]) && $this->_options[0] == 'list') {
            return $this->_renderNewsList();
        }

        if(empty($this->_options)) {
            throw new Exceptions_NewslogException('Not enough parameters passed!');
        }
        $option   = strtolower(array_shift($this->_options));
        $renderer = '_render' . ucfirst($option);
        if(method_exists($this, $renderer)) {
            return $this->$renderer();
        }
        return $this->_renderOption($option);
    }

    protected function _renderPreview()
    {
        $previewPath = 'system/images/noimage.png';
        $config      = Zend_Registry::get('misc');
        $width       = $config['pageTeaserCropSize'];
        $height      = $config['pageTeaserCropSize'];
        if (isset($this->_options[1])) {
            $cropParams = explode('x', $this->_options[1]);
            if (isset($cropParams[0], $cropParams[1])) {
                $width  = (int) $cropParams[0];
                $height = (int) $cropParams[1];
            }
        }
        $infoPreview = Tools_Page_Tools::getPreviewFilePath(
            $this->_toasterOptions['id'],
            (isset($this->_options[0]) && $this->_options[0] == 'crop') ? true : false,
            $width.'x'.$height
        );

        if (!empty($infoPreview['fullPath'])) {
            $previewPath = $infoPreview['path'];
        }
        // Cropped image
        elseif (isset($this->_options[0]) && $this->_options[0] == 'crop'
            && !empty($infoPreview['fileName'])
            && file_exists($infoPreview['sitePath'].$infoPreview['previewPath'].$infoPreview['fileName'])
        ) {
            $previewPath = $infoPreview['previewPath'].$infoPreview['fileName'];
            $cropStatus  = Tools_Image_Tools::resizeByParameters(
                $infoPreview['sitePath'].$infoPreview['previewPath'].$infoPreview['fileName'],
                $width,
                $height,
                true,
                $infoPreview['sitePath'].$infoPreview['previewCropPath'].$infoPreview['sizeSubfolder'],
                true
            );
            if ($cropStatus === true) {
                $previewPath = $infoPreview['previewCropPath'].$infoPreview['sizeSubfolder'].$infoPreview['fileName'];
            }
        }

        return $this->_websiteHelper->getUrl().$previewPath;
    }

    protected function _renderActions() {
        if(!Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_CONTENT)) {
            return '';
        }
        $this->_view->newsId = $this->_invokeNewsItem()->getId();
        return $this->_view->render('actions.news.phtml');
    }

    protected function _renderUrl() {
        $folder = Newslog_Models_Mapper_ConfigurationMapper::getInstance()->fetchConfigParam('folder');
        return $this->_websiteHelper->getUrl() . (($folder) ? $folder . '/' : '') . $this->_invokeNewsItem()->getMetaDataValue('url');
    }

    protected function _renderDate() {
        $format = array_shift($this->_options);
        if(!$format) {
            $format = 'M, j Y H:m';
        }
        $creatDate = $this->_invokeNewsItem()->getCreatedAt();
        $dateTrans = $this->_translateDate(date($format, strtotime($creatDate)));
        return $dateTrans;
    }

    private function _translateDate($strDate){
        $dates = explode(' ', ltrim($strDate));
        $dates[0] = $this->_translator->translate($dates[0]);
        $finDate = implode(' ',$dates);
        return $finDate ;
    }

    protected function _renderGplus() {
        $gplusProfile = Newslog_Tools_Misc::getGplusProfile($this->_invokeNewsItem());
        if($gplusProfile) {
            $title = (isset($this->_options[0])) ? $this->_options[0] : $gplusProfile['name'];
            return '<a class="newslog-gplus-profile" href="' . $gplusProfile['url'] . '?rel=author" target="_blank">' . $title . '</a>';
        }
        return '';
    }

    protected function _renderTags() {
        $tags = $this->_invokeNewsItem()->getTags();
        if(!is_array($tags)) {
            return '';
        }
        if(in_array('nolinks', $this->_options)){
            return implode(',', array_map(function($tag){ return $tag['name']; }, $tags));
        }
        $reqUriParsed     = parse_url(Zend_Controller_Front::getInstance()->getRequest()->getRequestUri());
        $websiteUrlParsed = parse_url($this->_websiteHelper->getUrl());
        $this->_settings           = Newslog_Models_Mapper_ConfigurationMapper::getInstance()->fetchConfigParams();
        $this->_view->tags         = $tags;

        $this->_view->listName    = $this->_toasterOptions['newsListName'];
        $this->_view->newsFolder  = trim($this->_settings['folder'], '/') . '/';
        $this->_view->tagsLength  = sizeof($tags);
        return $this->_view->render('tags.news.phtml');
    }

    /**
     * Common mehtod to get post's values
     *
     * @param string $option
     * @return string
     */
    private function _renderOption($option) {
        $getter = 'get' . ucfirst($option);

        try {
            $newsItem = $this->_invokeNewsItem();
        } catch (Exceptions_SeotoasterPluginException $spe) {
            if($this->_debugMode) {
                error_log($spe->getMessage());
            }
            return $spe->getMessage();
        }

        if(!method_exists($newsItem, $getter)) {
            return 'News widget error: wrong option passed.';
        }

        $data = $newsItem->$getter();

        if (array_search('inline', $this->_options) !== false) {
            return htmlentities($data, ENT_QUOTES, 'UTF-8');
        }
        return $data;
    }

    private function _invokeNewsItem() {
        if(!isset($this->_toasterOptions['id'])) {
            throw new Exceptions_SeotoasterPluginException('News widget error: Can not determine page id.');
        }
        $page = Application_Model_Mappers_PageMapper::getInstance()->find($this->_toasterOptions['id']);
        if(!$page instanceof Application_Model_Models_Page) {
            throw new Exceptions_SeotoasterPluginException('News widget error: News page cannot be found');
        }
        if(!$page->getExtraOption(Newslog::OPTION_PAGE_DEFAULT) && !$page->getNews()) {
            throw new Exceptions_SeotoasterPluginException('News widget error: Page passed to the widget is not a news page');
        }
        $newsItem = $this->_mapper->findByPageId($page->getId());
        if(!$newsItem instanceof Newslog_Models_Model_News) {
            throw new Exceptions_SeotoasterPluginException('News widget error: News item connot be found');
        }
        return $newsItem;
    }

        /**
         * Event widget
         * {$news:event:date[:date_format]}
         * {$news:event:location}
         * @return string
         * @throws Exceptions_SeotoasterPluginException
         */
        protected function _renderEvent()
        {
            if ($this->_invokeNewsItem()->getEvent() && !empty($this->_options[0])) {
                $options = filter_var_array($this->_options, FILTER_SANITIZE_STRING);
                if ($options[0] === 'date') {
                    $format = !empty($options[1]) ? $options[1] : 'M, j Y H:m';
                    $eventDate = $this->_invokeNewsItem()->getEventDate();
                    if ($eventDate != "0000-00-00 00:00:00") {
                        return $this->_translateDate(date($format, strtotime($eventDate)));
                    }
                } elseif ($options[0] === 'location') {
                    return $this->_invokeNewsItem()->getEventLocation();
                }
            }
            return '';
        }


    /*
     * ================ NEXT PART OF PLUGIN IS DEPRECATED AND WILL BE REMOVED IN NEXT RELEASES ==================
     * ========================================== YOU'VE BEEN WARNED ============================================
     */

    /**
     * For old newslist widget
     *
     * @deprecated
     * @return array
     */
    protected function _parseOtions() {
        $options = array(
            'order'    => self::ORDER_DESC,
            'limit'    => null,
            'tags'     => array(),
            'useImage' => false
        );
        if(is_array($this->_options)) {
            foreach($this->_options as $option) {
                switch($option) {
                    case self::USE_IMAGE:
                        $options['useImage'] = true;
                    break;
                    case self::ORDER_ASC:
                    case self::ORDER_DESC:
                        $options['order'] = $option;
                    break;
                    default:
                        if(is_string($option) && $this->_isNewsTag($option)) {
                            array_push($options['tags'], $option);
                        } else {
                            $options['limit']= intval($option);
                        }
                    break;
                }
            }
        }
        return $options;
    }

    /**
     * News list renderer
     *
     * @deprecated
     * @return mixed
     */
    protected function _renderNewsList() {
        $options             = $this->_parseOtions();
        $this->_view->folder = Newslog_Models_Mapper_ConfigurationMapper::getInstance()->fetchConfigParam('folder');
        $this->_view->img    = $options['useImage'];
        $this->_view->news   = array_map(function($newsItem){return $newsItem->toArray();}, Newslog_Models_Mapper_NewsMapper::getInstance()->fetchAll(null,
            array('created_at ' . strtoupper($options['order'])),
            $options['limit'],
            null,
            $options['tags']
        ));
        return $this->_view->render('list.news.phtml');
    }

    /**
     * @depracated
     * @param $tagName
     * @return bool
     */
    private function _isNewsTag($tagName) {
        $validator = new Zend_Validate_Db_RecordExists(array(
            'table' => 'plugin_newslog_tag',
            'field' => 'name'
        ));
        return $validator->isValid($tagName);
    }


    /**
     * Tag cloud render
     *
     * @return mixed list of all news tags
     */
        protected function _renderTagcloud()
        {
            $settings = Newslog_Models_Mapper_ConfigurationMapper::getInstance()->fetchConfigParams();
            $websiteUrl = $this->_websiteHelper->getUrl();
            $this->_view->tagsCloud = Newslog_Models_Mapper_TagMapper::getInstance()->findAllTags();
            $this->_view->baseFilterUrl = (!empty($this->_options)) ? $websiteUrl . trim(
                $settings['folder'],
                '/'
            ) . '/' . '?list=' . $this->_options[0] . '&amp;tags=' : $websiteUrl . trim(
                $settings['folder'],
                '/'
            ) . '/' . '?listall=true&amp;tags=';
            return $this->_view->render('tag.cloud.phtml');
        }
}
