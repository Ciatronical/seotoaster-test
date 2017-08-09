<?php


class Apps_Models_Models_AppsSystemFormModel extends Application_Model_Models_Abstract {

   protected $_form = '';
   protected $_lists = '';
   protected $_service = '';
   protected $_additionalList = '';

   public function getForm() {
       return $this->_form;
   }

   public function setForm($form) {
       $this->_form = $form;
       return $this;
   }

   public function getLists() {
       return $this->_lists;
   }

   public function setLists($lists) {
       $this->_lists = $lists;
       return $this;
   }

   public function getService() {
       return $this->_service;
   }

   public function setService($service) {
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

