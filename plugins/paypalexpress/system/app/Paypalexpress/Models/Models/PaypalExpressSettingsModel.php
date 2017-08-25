<?php
/**
 * Paypal configuration model
 *
 * Class Paypal_Models_Models_PaypalConfigModel
 */
class Paypalexpress_Models_Models_PaypalExpressSettingsModel extends Application_Model_Models_Abstract
{

    protected $_id = '';
    protected $_prodID = '';
    protected $_sandID = '';
    protected $_useSandbox = '';


    public function getId()
    {
        return $this->_id;
    }

    public function setId($id)
    {
        $this->_id = $id;
        return $this;
    }

   

    public function getProdID()
    {
        return $this->_prodID;
    }

    public function setProdID($prodID)
    {
        $this->_prodId = $prodID;
        return $this;
    }


  public function getSandID()
    {
        return $this->_sandID;
    }

    public function setSandID($sandID)
    {
        $this->_sandID = $sandID;
        return $this;
    }


    
    public function getUseSandbox()
    {
        return $this->_useSandbox;
    }

    public function setUseSandbox($useSandbox)
    {
        $this->_useSandbox = $useSandbox;
        return $this;
    }
    
    
    public function testModel() {
    	$test="Test";
    return $test;
    }

}
