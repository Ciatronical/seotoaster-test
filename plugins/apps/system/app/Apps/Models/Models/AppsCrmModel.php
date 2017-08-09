<?php


class Apps_Models_Models_AppsCrmModel extends Application_Model_Models_Abstract
{

    protected $_dataType = '';
    protected $_lists = '';
    protected $_service = '';
    protected $_additionalList = '';

    public function getDataType()
    {
        return $this->_dataType;
    }

    public function setDataType($dataType)
    {
        $this->_dataType = $dataType;

        return $this;
    }

    public function getLists()
    {
        return $this->_lists;
    }

    public function setLists($lists)
    {
        $this->_lists = $lists;

        return $this;
    }

    public function getService()
    {
        return $this->_service;
    }

    public function setService($service)
    {
        $this->_service = $service;

        return $this;
    }

    /**
     * @return string
     */
    public function getAdditionalList()
    {
        return $this->_additionalList;
    }

    /**
     * @param string $additionalList
     * @return string
     */
    public function setAdditionalList($additionalList)
    {
        $this->_additionalList = $additionalList;

        return $this;
    }

}

