<?php
class Widgets_Directupload_Directupload extends Widgets_WebbuilderWidget
{
    const DIRECTUPLOAD_RESOURCE          = 'Webbuilder-directupload';

    const DIRECTUPLOAD_PLACEHOLDER_IMAGE = 'plugins/webbuilder/web/images/placeholder.png';

   	protected function _load()
    {
        $acl = Zend_Registry::get('acl');
        if (!$acl->has(Widgets_Directupload_Directupload::DIRECTUPLOAD_RESOURCE)) {
            $acl->addResource(Widgets_Directupload_Directupload::DIRECTUPLOAD_RESOURCE);
        }

        // required parameters
        if(!isset($this->_options[0]) || !isset($this->_options[1]) || !isset($this->_options[2])) {
            $this->_error('Not enough parameters. See readme.txt for usage');
        }

        $folder        = $this->_filter(filter_var($this->_options[0], FILTER_SANITIZE_STRING), '-');
        $imageName     = $this->_filter(filter_var($this->_options[1], FILTER_SANITIZE_STRING));
        $containerName = Webbuilder_Tools_Misc::toHash($imageName . __CLASS__);
        $pageId        = (end($this->_options) == 'static') ? 0 : $this->_toasterOptions['id'];
        $type          = ($pageId) ? Application_Model_Models_Container::TYPE_REGULARCONTENT : Application_Model_Models_Container::TYPE_STATICCONTENT;

        if(end($this->_options) != 'static') {
            $containerName .= '_' . $this->_toasterOptions['id'];
        }

        $placeholder = array_search('placeholder', $this->_options);
        if($placeholder){
            unset($this->_options[$placeholder]);
        }

        $cropParams         = $this->_getCropParams();
        $subfolderInfo      = $this->_getSubfolderInfo($folder);
        $mediaSubfolder     = $subfolderInfo['subfolder'];
        $mediaSubfolderPath = realpath($subfolderInfo['path']);
        $imageExists        = false;

        if ($mediaSubfolderPath) {
            $container = Application_Model_Mappers_ContainerMapper::getInstance()->findByName(
                $containerName,
                $pageId,
                $type
            );

            $urlPach = $this->_websiteUrl.'media'.'/'.$folder.'/'.$mediaSubfolder.'/';
            if ($mediaSubfolder == Tools_Image_Tools::FOLDER_CROP && !empty($cropParams)) {
                $mediaSubfolderPath .= DIRECTORY_SEPARATOR.$cropParams['subfolder'].DIRECTORY_SEPARATOR;
                $urlPach            .= $cropParams['subfolder'].'/';
            }

            // Update image for new size
            if (!empty($cropParams) && !is_dir($mediaSubfolderPath)) {
                Tools_Filesystem_Tools::mkdir($mediaSubfolderPath);

                $originalFolder = $this->_websiteHelper->getPath().'media'.DIRECTORY_SEPARATOR.$folder
                    .DIRECTORY_SEPARATOR.Tools_Image_Tools::FOLDER_ORIGINAL.DIRECTORY_SEPARATOR;

                $previews = Tools_Filesystem_Tools::findFilesByExtension(
                    $originalFolder,
                    'jpg|png|jpeg|gif',
                    true,
                    true,
                    true
                );

                foreach ($previews as $pathFile) {
                    if (!is_readable($mediaSubfolderPath.basename($pathFile))) {
                        Tools_Image_Tools::resizeByParameters(
                            $pathFile,
                            $cropParams['width'],
                            $cropParams['height'],
                            true,
                            $mediaSubfolderPath,
                            true
                        );
                    }
                }
            }
            else {
                $previews = Tools_Filesystem_Tools::findFilesByExtension(
                    $mediaSubfolderPath,
                    'jpg|png|jpeg|gif',
                    true,
                    true,
                    true
                );
            }

            if (!empty($previews) && ($container instanceof Application_Model_Models_Container)) {
                $imageExists = true;
                if (array_key_exists($containerName, $previews)) {
                    $previewImage = $urlPach.$container->getContent();
                }
            }
        }

        //check placeholder image
        $placeholderImagePath = realpath($this->_websiteHelper->getPath() . 'plugins' . DIRECTORY_SEPARATOR . 'webbuilder'. DIRECTORY_SEPARATOR
                                    . 'web' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'placeholder.png');
        $placeholderImage = false;
        if(file_exists($placeholderImagePath)){
            $placeholderImage = $this->_websiteUrl . self::DIRECTUPLOAD_PLACEHOLDER_IMAGE;
        }

        // assign view variables
        $this->_view->pageId         = $pageId;
        $this->_view->galleryRel     = isset($this->_options[3]) ? $this->_filter($this->_options[3]) : '';
        $this->_view->isIE           = (strpos($_SERVER['HTTP_USER_AGENT'], '(compatible; MSIE ') !== false);
        $this->_view->width          = filter_var($this->_options[2], FILTER_SANITIZE_NUMBER_INT);
        $this->_view->container      = $containerName;
        $this->_view->imageName      = $imageName;
        $this->_view->folder         = $folder;
        $this->_view->mediaSubfolder = $mediaSubfolder;
        $this->_view->cropParams     = $cropParams;
        $this->_view->image          = isset($previewImage) ? $previewImage : $this->_websiteUrl . 'plugins/webbuilder/web/images/directupload.png';
        $this->_view->imageExists    = (isset($imageExists) && $imageExists);
        $this->_view->placeholderImage = $placeholderImage;
        $this->_view->placeholder    = $placeholder;

        if (isset($previewImage) && !empty($cropParams)) {
            $this->_view->originalImage = str_replace(
                $mediaSubfolder.DIRECTORY_SEPARATOR.$cropParams['subfolder'],
                Tools_Image_Tools::FOLDER_ORIGINAL,
                $previewImage
            );
        }
        elseif (isset($previewImage)) {
            $this->_view->originalImage = str_replace(
                $mediaSubfolder,
                Tools_Image_Tools::FOLDER_ORIGINAL,
                $previewImage
            );
        }
        if(in_array("nolink",$this->_options)) {
            $this->_view->nolink = 'nolink';
        }

        return $this->_view->render('directupload.phtml');
	}

    /**
     * Filter the given value using the [^\w]+|[\s\-]+~ui pattern and replace all not valid chars with the $replacement
     *
     * @param string $value
     * @param string $replacement
     * @return string
     */
    private function _filter($value, $replacement = '')
    {
        $filter = new Zend_Filter_PregReplace();
        $value  = $filter->setMatchPattern('~[^\w]+|[\s\-]+~ui')
            ->setReplacement($replacement)
            ->filter(trim($value));
        return $value;
    }

    /**
     * Get the proper media sub-folder from option or based on the image width
     *
     * @param string $folder
     * @return array
     */
    private function _getSubfolderInfo($folder)
    {
        if (isset($this->_options[4])) {
            if (strpos($this->_options[4], Tools_Image_Tools::FOLDER_CROP.'-') !== false) {
                $name = Tools_Image_Tools::FOLDER_CROP;
            }
            if (in_array($this->_options[4], Tools_Image_Tools::$imgResizedFolders)) {
                $name = $this->_options[4];
            }
            if ($this->_options[4] == Tools_Image_Tools::FOLDER_ORIGINAL) {
                $name = Tools_Image_Tools::FOLDER_ORIGINAL;
            }
        }
        else {
            $name = Webbuilder_Tools_Filesystem::getMediaSubFolderByWidth(
                filter_var($this->_options[2], FILTER_SANITIZE_NUMBER_INT)
            );
        }

        $path = $this->_websiteHelper->getPath().'media'.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.$name
            .DIRECTORY_SEPARATOR;
        if (!is_dir($path)) {
            Tools_Filesystem_Tools::mkdir($path);
        }

        return array(
            'subfolder' => $name,
            'folder'    => $folder,
            'path'      => $path
        );
    }

    /**
     * Returns the crop params
     *
     * @return array
     */
    private function _getCropParams()
    {
        $params = array();
        if (isset($this->_options[4]) && strpos($this->_options[4], Tools_Image_Tools::FOLDER_CROP.'-') !== false) {
            preg_match('/^'.Tools_Image_Tools::FOLDER_CROP.'-([0-9]+)x?([0-9]*)/i', $this->_options[4], $cropParams);
            if (isset($cropParams[1], $cropParams[2]) && is_numeric($cropParams[1]) && empty($cropParams[2])) {
                $cropParams[2] = $cropParams[1];
            }
            unset($cropParams[0]);
            $params['width']     = $cropParams[1];
            $params['height']    = $cropParams[2];
            $params['subfolder'] = implode('-', $cropParams);
        }

        return $params;
    }

    public static function getAllowedOptions() {
        $translator = Zend_Registry::get('Zend_Translate');
        return array(
            array(
                'group'  => $translator->translate('Plugins Shortcuts'),
                'alias'  => $translator->translate('Direct upload'),
                'option' => 'directupload:FOLDER_NAME:IMAGE_NAME:WIDTH:[GALLERY_TAG]:[IMG_TYPE][:nolink]]'
            )
        );
    }
}
