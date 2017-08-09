<?php

class Webbuilder_Tools_Filesystem
{
    const IMG_SMALL = 'small';
    const IMG_MEDIUM = 'medium';
    const IMG_LARGE = 'large';
    const IMG_ORIGINAL = 'original';

    public static function getMediaSubFolderByWidth($width)
    {
        $config = Zend_Controller_Action_HelperBroker::getStaticHelper('config')->getConfig();

        if ($width <= $config['imgSmall']) {
            return self::IMG_SMALL;
        }
        if ($width <= $config['imgMedium']) {
            return self::IMG_MEDIUM;
        }
        if ($width <= $config['imgLarge']) {
            return self::IMG_LARGE;
        }

        return self::IMG_ORIGINAL;
    }

}