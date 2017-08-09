<?php
/**
 * Created by PhpStorm.
 * User: vladymyr
 * Date: 19.10.15
 * Time: 17:03
 */
class Newslog_Tools_SearchIndex{
    private static $_instance = null;

    public static function getInstance() {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function addIndex($page, $newsItem) {
        if($page instanceof Application_Model_Models_Page){
            $tags = $newsItem->getTags();
            if (!empty($tags) && is_array($tags)){
                $tagsName = implode(
                    ', ',
                    array_map(
                        function ($tag) {
                            return $tag['name'];
                        },
                        $tags
                    )
                );
            } else {
                $tagsName = '';
            }
            Tools_Search_Tools::removeFromIndex($page->getId());
            $page->setMetaDescription(
                implode(
                    PHP_EOL,
                    array(
                        $newsItem->getContent(),
                        $tagsName
                    )
                )
            );
            Tools_Search_Tools::addPageToIndex($page);
        }
    }
}