<?php


class Apps_Models_Mapper_AppsSettingsMapper extends Application_Model_Mappers_Abstract {

	protected $_dbTable = 'Apps_Models_Dbtables_AppsSettingDbtable';

	protected $_model   = 'Apps_Models_Models_AppsSettingsModel';

    public function save($settings) {
        if(!$settings instanceof $this->_model) {
            throw new Exceptions_SeotoasterException('Given parameter should be ' . $this->_model . ' instance');
        }
        $data = array(
            'service_name'  => $settings->getServiceName(),
            'status'        => $settings->getStatus(),
            'category'      => $settings->getCategory()
        );
        $serviceName = $settings->getServiceName();
        $where = $this->getDbTable()->getAdapter()->quoteInto("service_name=?", $serviceName);
        $existForm = $this->getByServiceName($serviceName);
        if(!empty($existForm)){
            return $this->getDbTable()->update($data, $where);
        }
        return $this->getDbTable()->insert($data);

    }

    public function getByServiceName($serviceName) {
        $where = $this->getDbTable()->getAdapter()->quoteInto("service_name=?", $serviceName);
        return $this->fetchAll($where);
    }

    public function selectConfig() {
        return $this->getDbTable()->getAdapter()->fetchPairs($this->getDbTable()->select()->from('plugin_apps_settings'));
    }

    public function selectConfigByStatusCategory($status, $categoryType){
        $where = $this->getDbTable()->getAdapter()->quoteInto("status=?", $status);
        $where .= ' AND ' .$this->getDbTable()->getAdapter()->quoteInto("category=?", $categoryType);
        return $this->getDbTable()->getAdapter()->fetchPairs($this->getDbTable()->select()->from('plugin_apps_settings')->where($where));
    }

}

