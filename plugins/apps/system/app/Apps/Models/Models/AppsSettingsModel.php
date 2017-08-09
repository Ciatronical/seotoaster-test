<?php


class Apps_Models_Models_AppsSettingsModel extends Application_Model_Models_Abstract {

   protected $_serviceName = '';
   protected $_status = 0;
   protected $_category = '';
      
   public function getServiceName() {
		return $this->_serviceName;
   }
   public function setServiceName($serviceName) {
		$this->_serviceName = $serviceName;
		return $this;
   }
   
   public function getStatus() {
		return $this->_status;
   }
   public function setStatus($status) {
		$this->_status = $status;
		return $this;
   }

   public function getCategory() {
       return $this->_category;
   }

   public function setCategory($category) {
       $this->_category = $category;
       return $this;
   }

}

