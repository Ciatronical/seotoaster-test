<?php


class Pagerating_Mappers_ReviewMapper extends Application_Model_Mappers_Abstract {

	protected $_dbTable = 'Pagerating_DbTables_ReviewDbTable';

	protected $_model   = 'Pagerating_Models_Review';

	public function save($review) {
		if(!$review instanceof $this->_model) {
			throw new Exceptions_SeotoasterException('Given parameter should be ' . $this->_model . ' instance');
		}
		$data = $review->toArray();

		if ($review->getId()){
			return $this->getDbTable()->update($data, array('id = ?' => $review->getId()));
		} else {
			return $this->getDbTable()->insert($data);
		}
	}

	public function delete($model){
		if(!$model instanceof $this->_model) {
			throw new Exceptions_SeotoasterException('Given parameter should be ' . $this->_model . ' instance');
		}
		$where = $this->getDbTable()->getAdapter()->quoteInto('id = ?', $model->getId());

		return $this->getDbTable()->delete($where);
	}
}

