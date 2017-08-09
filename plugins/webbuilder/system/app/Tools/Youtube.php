<?php

class Tools_Youtube
{
    /**
     * pass in by constructor
     *
     * @var
     */
    protected $_youtubeKey;

    /**
     * @var
     */
    protected $_referer;

    /**
     *Returns a list of videos that match the API request parameters.
     */
    const VIDEOS = 'https://www.googleapis.com/youtube/v3/videos';

    /**
     * Returns a collection of search results that match the query
     * parameters specified in the API request.
     * By default, a search result set identifies matching video, channel,
     * and playlist resources, but you can also configure queries to only
     * retrieve a specific type of resource.
     */
    const SEARCH = 'https://www.googleapis.com/youtube/v3/search';

    /**
     *    Returns a collection of zero or more channel resources that match the request criteria.
     */
    const CHANNELS = 'https://www.googleapis.com/youtube/v3/channels';

    /**
     *Returns a collection of playlists that match the API request parameters.
     * For example, you can retrieve all playlists that the authenticated user owns,
     * or you can retrieve one or more playlists by their unique IDs.
     */
    const PLAYLISTS = 'https://www.googleapis.com/youtube/v3/playlists';

    /**
     *Returns a collection of playlist items that match the API request parameters.
     * You can retrieve all of the playlist items in a specified playlist or
     * retrieve one or more playlist items by their unique IDs.
     */
    const PLAYLIST_ITEMS = 'https://www.googleapis.com/youtube/v3/playlistItems';

    /**
     *Returns a list of channel activity events that match the request criteria.
     * For example, you can retrieve events associated with a particular channel,
     * events associated with the user's subscriptions and Google+ friends,
     * or the YouTube home page feed, which is customized for each user.
     */
    const ACTIVITIES = 'https://www.googleapis.com/youtube/v3/activities';

    /**
     * @var array
     */
    protected $_apiUrls = array(
        'videos.list' => self::VIDEOS,
        'search.list' => self::SEARCH,
        'channels.list' => self::CHANNELS,
        'playlists.list' => self::PLAYLISTS,
        'playlistItems.list' => self::PLAYLIST_ITEMS,
        'activities' => self::ACTIVITIES,
    );

    /**
     * @var array
     */
    protected $_pageInfo = array();


    /**
     * Parse the input url and return just the path part
     *
     * @param $url
     * @return mixed
     */
    public static function parseUrlPath($url)
    {
        $array = parse_url($url);

        return $array['path'];
    }

    /**
     * Return an  array of queries => values  form given URL
     *
     * @param $url
     * @return array
     */
    public static function parseUrlQuery($url)
    {
        $array = parse_url($url);
        $params = array();
        if (!isset($array['query'])) {
            return $params;
        }
        $query = $array['query'];

        $queryParts = explode('&', $query);

        foreach ($queryParts as $param) {
            $item = explode('=', $param);
            $params[$item[0]] = empty($item[1]) ? '' : $item[1];
        }

        return $params;
    }

    /**
     * @param array $params
     */
    public function __construct($params = array())
    {
        if (!is_array($params)) {
            throw new InvalidArgumentException('The configuration options must be an array.');
        }

        if (!array_key_exists('key', $params)) {
            throw new InvalidArgumentException('Google API key is required, please visit http://code.google.com/apis/console');
        }
        $this->_youtubeKey = $params['key'];

        if (array_key_exists('referer', $params)) {
            $this->_referer = $params['referer'];
        }
    }

    /**
     * @param $vId
     * @return bool
     * @throws Exception
     */
    public function getVideoInfo($vId)
    {
        $apiUrl = $this->getApi('videos.list');
        $params = array(
            'id' => $vId,
            'key' => $this->_youtubeKey,
            'part' => 'id, snippet, contentDetails, player, statistics, status'
        );

        $apiData = $this->apiGet($apiUrl, $params);

        return $this->decodeSingle($apiData);
    }

    /**
     * @param $vIds
     * @return bool
     * @throws Exception
     */
    public function getVideosInfo($vIds)
    {
        $ids = is_array($vIds) ? implode(',', $vIds) : $vIds;
        $apiUrl = $this->getApi('videos.list');
        $params = array(
            'id' => $ids,
            'part' => 'id, snippet, contentDetails, player, statistics, status'
        );

        $apiData = $this->apiGet($apiUrl, $params);

        return $this->decodeList($apiData);
    }

    /**
     * Search playlists, channels and videos
     * @param $q
     * @param int $maxResults
     * @return array|bool
     */
    public function search($q, $maxResults = 10)
    {
        $params = array(
            'q' => $q,
            'part' => 'id, snippet',
            'maxResults' => $maxResults
        );

        return $this->searchAdvanced($params);
    }

    /**
     *  Search only Videos, Return an array of PHP objects
     *
     * @param $q
     * @param int $maxResults
     * @param null $order
     * @return array|bool
     */
    public function searchVideos($q, $maxResults = 10, $order = null)
    {
        $params = array(
            'q' => $q,
            'type' => 'video',
            'part' => 'id, snippet',
            'maxResults' => $maxResults
        );
        if (!empty($order)) {
            $params['order'] = $order;
        }

        return $this->searchAdvanced($params);
    }

    /**
     * Search only Videos in a given channel
     *
     * @param $q
     * @param $channelId
     * @param int $maxResults
     * @param null $order
     * @return array|bool
     */
    public function searchChannelVideos($q, $channelId, $maxResults = 10, $order = null)
    {
        $params = array(
            'q' => $q,
            'type' => 'video',
            'channelId' => $channelId,
            'part' => 'id, snippet',
            'maxResults' => $maxResults
        );
        if (!empty($order)) {
            $params['order'] = $order;
        }

        return $this->searchAdvanced($params);
    }

    /**
     * @param $params
     * @param bool $pageInfo
     * @return array|bool
     * @throws Exception
     */
    public function searchAdvanced($params, $pageInfo = false)
    {
        $apiUrl = $this->getApi('search.list');

        if (empty($params) || !isset($params['q'])) {
            throw new InvalidArgumentException('at least the Search query must be supplied');
        }

        $apiData = $this->apiGet($apiUrl, $params);
        if ($pageInfo) {
            return array(
                'results' => $this->decodeList($apiData),
                'info' => $this->_pageInfo
            );
        } else {
            return $this->decodeList($apiData);
        }
    }

    /**
     * Make Initial Call. With second argument to reveal page info such as page tokens
     * We can create an array to store page tokens so we can go back and forth
     *
     * @param $params
     * @param null $token
     * @return array|bool
     */
    public function paginateResults($params, $token = null)
    {
        if (!is_null($token)) {
            $params['pageToken'] = $token;
        }
        if (!empty($params)) {
            return $this->searchAdvanced($params, true);
        }
    }

    /**
     *
     * Search channel by Name, Return a std PHP object
     *
     * @param $username
     * @param array $optionalParams
     * @return bool
     * @throws Exception
     */
    public function getChannelByName($username, $optionalParams = array())
    {
        $apiUrl = $this->getApi('channels.list');
        $params = array(
            'forUsername' => $username,
            'part' => 'id,snippet,contentDetails,statistics,invideoPromotion'
        );
        if (!empty($optionalParams)) {
            $params = array_merge($params, $optionalParams);
        }
        $apiData = $this->apiGet($apiUrl, $params);

        return $this->decodeSingle($apiData);
    }

    /**
     * Search channel by Id , Return a std PHP object
     *
     * @param $id
     * @param array $optionalParams
     * @return bool
     * @throws Exception
     */
    public function getChannelById($id, $optionalParams = array())
    {
        $apiUrl = $this->getApi('channels.list');
        $params = array(
            'id' => $id,
            'part' => 'id,snippet,contentDetails,statistics,invideoPromotion'
        );
        if (!empty($optionalParams)) {
            $params = array_merge($params, $optionalParams);
        }
        $apiData = $this->apiGet($apiUrl, $params);

        return $this->decodeSingle($apiData);
    }

    /**
     * Return playlist by given channel ID , Return a std PHP object
     *
     * @param $channelId
     * @param array $optionalParams
     * @return bool
     * @throws Exception
     */
    public function getPlaylistsByChannelId($channelId, $optionalParams = array())
    {
        $apiUrl = $this->getApi('playlists.list');
        $params = array(
            'channelId' => $channelId,
            'part' => 'id, snippet, status'
        );
        if (!empty($optionalParams)) {
            $params = array_merge($params, $optionalParams);
        }
        $apiData = $this->apiGet($apiUrl, $params);

        return $this->decodeList($apiData);
    }

    /**
     * Return playlist by given playlist ID , Return a std PHP object
     *
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function getPlaylistById($id)
    {
        $apiUrl = $this->getApi('playlists.list');
        $params = array(
            'id' => $id,
            'part' => 'id, snippet, status'
        );
        $apiData = $this->apiGet($apiUrl, $params);

        return $this->decodeSingle($apiData);
    }

    /**
     * @param $playlistId
     * @param int $maxResults
     * @return bool
     * @throws Exception
     */
    public function getPlaylistItemsByPlaylistId($playlistId, $maxResults = 50)
    {
        $apiUrl = $this->getApi('playlistItems.list');
        $params = array(
            'playlistId' => $playlistId,
            'part' => 'id, snippet, contentDetails, status',
            'maxResults' => $maxResults
        );
        $apiData = $this->apiGet($apiUrl, $params);

        return $this->decodeList($apiData);
    }

    /**
     * @param $channelId
     * @return bool
     * @throws Exception
     */
    public function getActivitiesByChannelId($channelId)
    {
        if (empty($channelId)) {
            throw new InvalidArgumentException('ChannelId must be supplied');
        }
        $apiUrl = $this->getApi('activities');
        $params = array(
            'channelId' => $channelId,
            'part' => 'id, snippet, contentDetails'
        );
        $apiData = $this->apiGet($apiUrl, $params);

        return $this->decodeList($apiData);
    }

    /**
     * Return a valid video Id from given URL
     *
     * @param $youtubeUrl
     * @return string
     * @throws Exception
     */
    public static function parseVideoFromUrl($youtubeUrl)
    {
        if (strpos($youtubeUrl, 'youtube.com') !== false) {
            $params = self::parseUrlQuery($youtubeUrl);

            return $params['v'];
        } else {
            if (strpos($youtubeUrl, 'youtu.be') !== false) {
                $path = self::parseUrlPath($youtubeUrl);
                $vid = substr($path, 1);

                return $vid;
            } else {
                throw new Exception('The supplied URL does not look like a Youtube URL');
            }
        }
    }

    /**
     * Return a valid channel Id from given URL
     *
     * @param $youtubeUrl
     * @return bool
     * @throws Exception
     */
    public function getChannelFromURL($youtubeUrl)
    {
        if (strpos($youtubeUrl, 'youtube.com') === false) {
            throw new Exception('The supplied URL does not look like a Youtube URL');
        }

        $path = self::parseUrlPath($youtubeUrl);
        if (strpos($path, '/channel') === 0) {
            $segments = explode('/', $path);
            $channelId = $segments[count($segments) - 1];
            $channel = $this->getChannelById($channelId);
        } else {
            if (strpos($path, '/user') === 0) {
                $segments = explode('/', $path);
                $username = $segments[count($segments) - 1];
                $channel = $this->getChannelByName($username);
            } else {
                throw new Exception('The supplied URL does not look like a Youtube Channel URL');
            }
        }

        return $channel;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getApi($name)
    {
        return $this->_apiUrls[$name];
    }

    /**
     * Decode the response from youtube, extract the single resource object
     *
     * @param $apiData
     * @return bool
     * @throws Exception
     */
    public function decodeSingle($apiData)
    {
        $resObj = json_decode($apiData);
        if (isset($resObj->error)) {
            $msg = "Error " . $resObj->error->code . " " . $resObj->error->message;
            if (isset($resObj->error->errors[0])) {
                $msg .= " : " . $resObj->error->errors[0]->reason;
            }
            throw new Exception($msg, $resObj->error->code);
        } else {
            $itemsArray = $resObj->items;
            if (!is_array($itemsArray) || count($itemsArray) == 0) {
                return false;
            } else {
                return $itemsArray[0];
            }
        }
    }

    /**
     * Decode the response from youtube, extract the list of resource objects
     *
     * @param $apiData
     * @return bool
     * @throws Exception
     */
    public function decodeList($apiData)
    {
        $resObj = json_decode($apiData);
        if (isset($resObj->error)) {
            $msg = "Error " . $resObj->error->code . " " . $resObj->error->message;
            if (isset($resObj->error->errors[0])) {
                $msg .= " : " . $resObj->error->errors[0]->reason;
            }
            throw new Exception($msg, $resObj->error->code);
        } else {
            $this->_pageInfo = array(
                'resultsPerPage' => $resObj->pageInfo->resultsPerPage,
                'totalResults' => $resObj->pageInfo->totalResults,
                'kind' => $resObj->kind,
                'etag' => $resObj->etag,
                'prevPageToken' => null,
                'nextPageToken' => null
            );
            if (isset($resObj->prevPageToken)) {
                $this->_pageInfo['prevPageToken'] = $resObj->prevPageToken;
            }
            if (isset($resObj->nextPageToken)) {
                $this->_pageInfo['nextPageToken'] = $resObj->nextPageToken;
            }

            $itemsArray = $resObj->items;
            if (!is_array($itemsArray) || count($itemsArray) == 0) {
                return false;
            } else {
                return $itemsArray;
            }
        }
    }

    /**
     * @param $url
     * @param $params
     * @return mixed
     * @throws Exception
     */
    public function apiGet($url, $params)
    {
        //set the youtube key
        $params['key'] = $this->_youtubeKey;

        //boilerplates for CURL
        $tuCurl = curl_init();
        curl_setopt($tuCurl, CURLOPT_URL, $url . (strpos($url, '?') === false ? '?' : '') . http_build_query($params));
        if (strpos($url, 'https') === false) {
            curl_setopt($tuCurl, CURLOPT_PORT, 80);
        } else {
            curl_setopt($tuCurl, CURLOPT_PORT, 443);
        }
        if ($this->_referer !== null) {
            curl_setopt($tuCurl, CURLOPT_REFERER, $this->_referer);
        }
        curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($tuCurl, CURLOPT_SSL_VERIFYPEER, false);
        $tuData = curl_exec($tuCurl);
        if (curl_errno($tuCurl)) {
            throw new Exception('Curl Error : ' . curl_error($tuCurl));
        }

        return $tuData;
    }


}
