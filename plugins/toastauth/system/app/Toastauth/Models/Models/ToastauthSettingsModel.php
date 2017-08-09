<?php


class Toastauth_Models_Models_ToastauthSettingsModel extends Application_Model_Models_Abstract
{

    protected $_name;
    protected $_settings;
    protected $_status;

    public function getName()
    {
        return $this->_name;
    }

    public function setName($name)
    {
        $this->_name = $name;

        return $this;
    }

    public function getSettings()
    {
        return $this->_settings;
    }

    public function setSettings($settings)
    {
        $this->_settings = $settings;

        return $this;
    }

    public function getStatus()
    {
        return $this->_status;
    }

    public function setStatus($status)
    {
        $this->_status = $status;

        return $this;
    }

}

