<?php
/**
 * Netcontent Mapper
 *
 */
class Netcontent_Models_Mapper_Netcontent extends Application_Model_Mappers_Abstract {

    const RESULT_UPDATED = 'updated';

	protected $_dbTable  = 'Netcontent_Models_DbTable_Netcontent';

	protected $_model    = 'Netcontent_Models_Model_Netcontent';

	public function save($widget) {
        $data = array(
            'widget_name' => $widget->getWidgetName(),
            'content'     => $widget->getContent(),
            'p2p'         => $widget->getP2p(),
            'modify_date' => $widget->getModifyDate(),
			'publish'	  => $widget->getPublish()
        );

		if(!$this->findByName($widget->getWidgetName(), $widget->getP2p())) {
			return $this->getDbTable()->insert($data);
		}
		if($widget->getUpdate() == true) {
			unset($data['widget_name']);
			$this->getDbTable()->update($data, array('widget_name = ?' => $widget->getWidgetName(), 'p2p = ?' => $widget->getP2p()));
			return self::RESULT_UPDATED;
		}
		return false;
	}

	public function findByName($widgetName, $p2p = null) {
        $where = $this->getDbTable()->getAdapter()->quoteInto('widget_name = ?', $widgetName);
		if($p2p != null) {$where .= $this->getDbTable()->getAdapter()->quoteInto(' AND p2p = ?', $p2p);}
        if(($widgetRow = $this->getDbTable()->fetchAll($where)->current()) === null) {
            return null;
        }
		return new $this->_model($widgetRow->toArray());
	}

	public function deleteWidget($widget, $p2p = null) {
        $widgetName = ($widget instanceof Netcontent_Models_Model_Netcontent) ? $widget->getWidgetName() : $widget;
		$where      = $this->getDbTable()->getAdapter()->quoteInto('widget_name = ?', $widgetName);
		if($p2p != null) {$where      .= $this->getDbTable()->getAdapter()->quoteInto(' AND p2p = ?', $p2p);}
		return $this->getDbTable()->delete($where);
	}

}

