<?php
/**
 * Directuploadgal magic space. Allows to wrap content in div with specific class
 *
 * Optionally magicspace can receive options, they are:
 * 1. class for div wrapper - optional
 */
class MagicSpaces_Directuploadgal_Directuploadgal extends Tools_MagicSpaces_Abstract
{

    protected function _run()
    {
        $galClass = 'img_gallery';
        if (isset($this->_params[0])) {
            $galClass = $this->_params[0];
        }
        $this->_spaceContent = '<div class="' . $galClass . '">' . $this->_spaceContent . '</div>';
        return $this->_spaceContent;

    }

}
