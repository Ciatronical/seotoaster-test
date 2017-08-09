<?php

class Newslog_Tools_Misc {

    public static function getGplusProfile($newsItem = null) {
        if($newsItem) {
            $user             = Application_Model_Mappers_UserMapper::getInstance()->find($newsItem->getUserId());
            if($user instanceof Application_Model_Models_User) {
                $userGplusProfile = $user->getGplusProfile();
            }
        }

        if(isset($userGplusProfile) && $userGplusProfile) {
            return array(
                'name' => 'Goolge+',
                'url'  => $userGplusProfile
            );
        } else {
            $newsConfig = Newslog_Models_Mapper_ConfigurationMapper::getInstance()->fetchConfigParams();
            if(isset($newsConfig['gplusProfile']) && $newsConfig['gplusProfile']) {
                return array(
                    'name' => 'Goolge+',
                    'url'  => $newsConfig['gplusProfile']
                );
            }
        }
        return null;
    }

    public static function loadPageByUrl($pageUrl = Helpers_Action_Website::DEFAULT_PAGE) {
        $page = null;
        if(Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_CACHE_PAGE)) {
            $page = Zend_Controller_Action_HelperBroker::getStaticHelper('cache')->load(md5($pageUrl), 'pagedata_');
        }
        if(!$page instanceof Application_Model_Models_Page) {
            if($pageUrl == Newslog::DEFAULT_INDEX) {
                $page = Application_Model_Mappers_PageMapper::getInstance()->fetchByOption(Newslog::OPTION_PAGE_INDEX, true);
            } else {
                $page = Application_Model_Mappers_PageMapper::getInstance()->findByUrl($pageUrl);
            }
        }
        return $page;
    }

    public static function error($message, $log = true, $exceptionClass = 'Exceptions_NewslogException') {
        $log || error_log($message);
        throw new $exceptionClass($message);
    }
}