<?php

/**
 * Class FlatRateShipping_Models_Model_FlatRateShippingConfigModel
 */
class FlatRateShipping_Models_Model_FlatRateShippingConfigModel extends Application_Model_Models_Abstract
{

    protected $_id;
    protected $_amountTypeLimit = '';
    protected $_amountLimit = '';
    protected $_zones = array();

    /**
     * @return null
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->_id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getAmountTypeLimit()
    {
        return $this->_amountTypeLimit;
    }

    /**
     * @param $amountTypeLimit
     * @return $this
     */
    public function setAmountTypeLimit($amountTypeLimit)
    {
        $this->_amountTypeLimit = $amountTypeLimit;
        return $this;
    }

    /**
     * @return string
     */
    public function getAmountLimit()
    {
        return $this->_amountLimit;
    }

    /**
     * @param $amountLimit
     * @return $this
     */
    public function setAmountLimit($amountLimit)
    {
        $this->_amountLimit = $amountLimit;
        return $this;
    }

    /**
     * @return array
     */
    public function getZones()
    {
        return $this->_zones;
    }

    /**
     * @param $zones
     * @return $this
     */
    public function setZones($zones)
    {
        $this->_zones = $zones;
        return $this;
    }

}

