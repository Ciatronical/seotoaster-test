<?php
/**
 * Netcontent Db table class
 *
 */
class Netcontent_Models_DbTable_Netcontent extends Zend_Db_Table_Abstract {

	protected $_name = 'plugin_netcontent_widget';

	protected $_primary = array('widget_name', 'p2p');
   }

