<?php

/**
 * Class Invoicetopdf_Models_Models_InvoicetopdfSettingModel
 */
class Invoicetopdf_Models_Models_InvoicetopdfSettingModel extends Application_Model_Models_Abstract
{

    protected $_name = '';
    protected $_value = '';

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->_value = $value;
        return $this;
    }

}

