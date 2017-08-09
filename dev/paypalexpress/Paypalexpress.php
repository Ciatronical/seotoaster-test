<?php

class Paypalexpress extends Tools_Plugins_Abstract{

	 protected function _init() {
     
        $this->_view->setScriptPath(__DIR__ . '/system/views/');
    }

 protected function _makeOptionForm() {
        $this->_view->todoForm = new Todo_Forms_Todo();
        return $this->_view->render('form.todo.phtml');_
    }
    
  


}