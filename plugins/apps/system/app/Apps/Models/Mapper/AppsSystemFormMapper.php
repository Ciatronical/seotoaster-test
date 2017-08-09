<?php


class Apps_Models_Mapper_AppsSystemFormMapper extends Application_Model_Mappers_Abstract {

	protected $_dbTable = 'Apps_Models_Dbtables_AppsSystemFormDbtable';

	protected $_model   = 'Apps_Models_Models_AppsSystemFormModel';

	public function save($listData) {
		if(!$listData instanceof $this->_model) {
			throw new Exceptions_SeotoasterException('Given parameter should be ' . $this->_model . ' instance');
		}
		$data = array(
		    'form'    => $listData->getForm(),
            'lists'   => $listData->getLists(),
            'service' => $listData->getService(),
            'additional_list' => $listData->getAdditionalList()
        );
        $formName = $listData->getForm();
        $serviceName = $listData->getService();
        $where = $this->getDbTable()->getAdapter()->quoteInto("form=?", $formName);
        $where .= ' AND ' .$this->getDbTable()->getAdapter()->quoteInto("service=?", $serviceName);
        $existForm = $this->getByFormNameService($formName, $serviceName);
        if(!empty($existForm)){
            return $this->getDbTable()->update($data, $where);
        }
        return $this->getDbTable()->insert($data);
        
    }
    
    public function getByFormNameService($formName, $service) {
        $where = $this->getDbTable()->getAdapter()->quoteInto("form=?", $formName);
        $where .= ' AND ' .$this->getDbTable()->getAdapter()->quoteInto("service=?", $service);
        return $this->fetchAll($where);
	}

    public function deleteList($service, $formName){
        $where = $this->getDbTable()->getAdapter()->quoteInto("form=?", $formName);
        $where .= ' AND ' .$this->getDbTable()->getAdapter()->quoteInto("service=?", $service);
        return $this->getDbTable()->delete($where);
    }
    
}

