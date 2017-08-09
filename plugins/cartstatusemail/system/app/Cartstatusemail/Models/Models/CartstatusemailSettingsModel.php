<?php


class Cartstatusemail_Models_Models_CartstatusemailSettingsModel extends Application_Model_Models_Abstract {

    protected $_cartStatus = '';
    protected $_periodHours = '';
    protected $_productsIds = '';
    protected $_emailTemplate = '';
    protected $_emailFrom = '';
    protected $_emailMessage = '';
    protected $_productsRule = '';

    public function getCartStatus() {
        return $this->_cartStatus;
    }
    public function setCartStatus($cartStatus) {
        $this->_cartStatus = $cartStatus;
    }

    public function getPeriodHours() {
        return $this->_periodHours;
    }
    public function setPeriodHours($periodHours) {
        $this->_periodHours = $periodHours;
    }

    public function getProductsIds() {
        return $this->_productsIds;
    }
    public function setProductsIds($productsIds) {
        $this->_productsIds = $productsIds;
    }

    public function getEmailTemplate() {
        return $this->_emailTemplate;
    }
    public function setEmailTemplate($emailTemplate) {
        $this->_emailTemplate = $emailTemplate;
    }

    public function getEmailFrom() {
        return $this->_emailFrom;
    }
    public function setEmailFrom($emailFrom) {
        $this->_emailFrom = $emailFrom;
    }

    public function getEmailMessage() {
        return $this->_emailMessage;
    }
    public function setEmailMessage($emailMessage) {
        $this->_emailMessage = $emailMessage;
    }

    public function getProductsRule() {
        return $this->_productsRule;
    }
    public function setProductsRule($productsRule) {
        $this->_productsRule = $productsRule;
    }

}

