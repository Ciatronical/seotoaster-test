<?php

class Widgets_Videolink_Videolink extends Widgets_WebbuilderWidget {

    const VIDEOLINK_DEFAULT_WIDTH     = 200;

    const VIDEOLINK_DEFAULT_HEIGHT    = 200;

    const VIDEOLINK_YOUTUBE_LINK      = 'youtube';

    const VIDEOLINK_GOOGLE_LINK       = 'google';

    const VIDEOLINK_DAILYMOTION_LINK  = 'dailymotion';

    const VIDEOLINK_VIMEO_LINK        = 'vimeo';

    const VIDEOLINK_MOTO_MOTO_LINK    = 'motomoto';

    const VIDEOLINK_RESOURCE          = 'Webbuilder-videolink';

    private $_youtubeApiKey;

   	protected function _load(){
        $acl = Zend_Registry::get('acl');
        if (!$acl->has(Widgets_Videolink_Videolink::VIDEOLINK_RESOURCE)) {
            $acl->addResource(Widgets_Videolink_Videolink::VIDEOLINK_RESOURCE);
        }

        $name      = Webbuilder_Tools_Misc::toHash($this->_options[0] . __CLASS__);
        $pageId    = (end($this->_options) == 'static') ? 0 : $this->_toasterOptions['id'];
        $type      = ($pageId) ? Application_Model_Models_Container::TYPE_REGULARCONTENT : Application_Model_Models_Container::TYPE_STATICCONTENT;
        $container = Application_Model_Mappers_ContainerMapper::getInstance()->findByName($name, $pageId, $type);

        $configHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('config');
        $this->_youtubeApiKey = $configHelper->getConfig('youtubeApiKey');

        if($container instanceof Application_Model_Models_Container) {
            $ioData = Zend_Json::decode($container->getContent());

            if(is_array($ioData) && !empty($ioData)) {
               foreach($ioData as $key => $value) {
                   $this->_view->$key = $value;
                }
            }
        }

        $urlVideo = isset($ioData['link']) ? $ioData['link'] :'';

        $existLinkUrl = false;
        if(preg_match("/vimeo/", $urlVideo)) {
            if(preg_match("/autoplay=/", $urlVideo)) {
                $autoplay = preg_replace("/(http:|https:|)\/\/(player\.vimeo\.com|vimeo\.com)(\/video\/|\/)/", '', $urlVideo);
                $this->_view->vimeoUrl = $autoplay;
            }
            if(preg_match("/t=/", $urlVideo)) {
                $time = preg_replace("/(http|https|):\/\/(player\.vimeo\.com|vimeo\.com)(\/video\/|\/)/", '', $urlVideo);
                $this->_view->vimeoUrl = $time;
            }
            if((!preg_match("/autoplay=/", $urlVideo)) && (!preg_match("/t=/", $urlVideo))) {
                preg_match("/[0-9]*$/", $urlVideo, $videoId);
                $this->_view->vimeoUrl = $videoId['0'];
            }
            $this->_view->sVideo = self::VIDEOLINK_VIMEO_LINK;
            $existLinkUrl = true;
        }

        if (preg_match("/youtu\.be/", $urlVideo) || preg_match("/youtube/", $urlVideo)) {
            $youtube = new Tools_Youtube(array('key' => $this->_youtubeApiKey));

            if (preg_match("/youtu\.be/", $urlVideo)) {
                $fullVideoId = preg_replace("/(http|https):\/\/(www\.|)youtu\.be\//", '', $urlVideo);
            }

            if (preg_match("/youtube/", $urlVideo)) {
                $fullVideoId = preg_replace("/(http|https):\/\/www\.youtube\.com\/watch\?v\=/", '', $urlVideo);
            }

            try {
                $videoId = Tools_Youtube::parseVideoFromUrl($urlVideo);
                $entry = $youtube->getVideoInfo($videoId);
                $result = $this->_getVideoMetaData($entry);
                $url = $videoId;
                $autoplay = '';

                if (preg_match("/t=/", $fullVideoId)) {
                    $startVideoMinutes = '0';
                    $startVideoSeconds = '0';
                    $startVideoTime = '0';
                    if (preg_match('~((?:t=\d).*(?:m|s))~', $fullVideoId, $matches)) {
                        $videoTime = str_replace('t=', '', $matches[0]);
                        if (preg_match("/m/", $videoTime)) {
                            $startVideoMinutes = preg_replace("/m[-_a-zA-Z0-9?]{0,100}/", '', $videoTime);
                        }
                        if (preg_match("/s/", $videoTime)) {
                            $startVideoSeconds = preg_replace("/(\dm|s)/", '', $videoTime);
                        }
                        if (isset($startVideoMinutes)) {
                            $startVideoMinutes = $startVideoMinutes * 60;
                        }
                        $startVideoTime = $startVideoMinutes + $startVideoSeconds;
                    }
                    $url = $url . '?start=' . $startVideoTime . $autoplay;
                }
                $keyTime = Tools_Youtube::parseUrlQuery($urlVideo);
                if (preg_match("/autoplay=/", $fullVideoId)) {
                    $autoplay = 'autoplay=1';
                    if (array_key_exists('t', $keyTime)) {
                        $url = $url . '&' . $autoplay;
                    } else {
                        $url = $url . '?' . $autoplay;
                    }
                }

                if (preg_match('/^https:\/\//', $urlVideo) && !preg_match('/^https:\/\//', $url)) {
                    $url = preg_replace('/^http/', 'https', $url);
                }
                $this->_view->url = $url;
                $this->_view->meta = $result;
                $this->_view->sVideo = self::VIDEOLINK_YOUTUBE_LINK;
                $existLinkUrl = true;
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }

        if(preg_match("/google/", $urlVideo)) {
            preg_match("/(#|\?)docid=[-0-9]*/", $urlVideo, $videoId);
            $videoId = substr($videoId['0'], 7);
            $this->_view->googleUrl = $videoId;
            $this->_view->sVideo = self::VIDEOLINK_GOOGLE_LINK;
            $existLinkUrl = true;
        }
        if(preg_match("/dailymotion/", $urlVideo)) {
            preg_match("/video\/[-a-zA-Z0-9]*/", $urlVideo, $videoId);
            $videoId = substr($videoId['0'], 6);
            $this->_view->dialyMotionUrl = $videoId;
            $this->_view->sVideo = self::VIDEOLINK_DAILYMOTION_LINK;
            $existLinkUrl = true;
        }

        if(preg_match("/motoetmotards/", $urlVideo)) {
            preg_match("/(-?\d+)/", $urlVideo, $videoId);
            $this->_view->motoAndMotoUrl = $videoId[0];
            $this->_view->sVideo = self::VIDEOLINK_MOTO_MOTO_LINK;
            $existLinkUrl = true;
        }

        $this->_view->wrongUrl      = $existLinkUrl;
        $this->_view->containerName = $name;
        $this->_view->inline        = (isset($this->_options[1]) && $this->_options[1] == 'inline') ? true : false;
        $this->_view->pageId        = (end($this->_options) == 'static') ? 0 : $this->_toasterOptions['id'];

        return $this->_view->render('videolink.phtml');
	}

    private function _getVideoMetaData($entry) {
        return array(
            'title' => isset($entry->snippet->title)?$entry->snippet->title:'',
            'description' => isset($entry->snippet->description)?$entry->snippet->description:'',
            'duration' => isset($entry->contentDetails->duration)?$entry->contentDetails->duration:'',
            'views' => isset($entry->statistics->viewCount)?$entry->statistics->viewCount:'0',
            'commentCount' => isset($entry->statistics->commentCount)?$entry->statistics->commentCount:'0'
        );
    }

    private function _getFlashUrl($entry) {
        foreach ($entry->mediaGroup->content as $content) {
            if ($content->type === 'application/x-shockwave-flash') {
                return $content->url;
            }
        }
        return null;
    }

    public static function getAllowedOptions() {
        $translator = Zend_Registry::get('Zend_Translate');
        return array(
            array(
                'group'  => $translator->translate('Plugins Shortcuts'),
                'alias'  => $translator->translate('Video link'),
                'option' => 'videolink:myname'
            )
        );
    }
}
