<?php


class Apps_Models_Mapper_AppsCrmMapper extends Application_Model_Mappers_Abstract
{

    protected $_dbTable = 'Apps_Models_Dbtables_AppsCrmDbtable';

    protected $_model = 'Apps_Models_Models_AppsCrmModel';

    public function save($listData)
    {
        if (!$listData instanceof $this->_model) {
            throw new Exceptions_SeotoasterException('Given parameter should be ' . $this->_model . ' instance');
        }
        $data = array(
            'dataType' => $listData->getDataType(),
            'lists' => $listData->getLists(),
            'service' => $listData->getService(),
            'additional_list' => $listData->getAdditionalList()
        );
        $dataType = $listData->getDataType();
        $serviceName = $listData->getService();
        $where = $this->getDbTable()->getAdapter()->quoteInto("dataType=?", $dataType);
        $where .= ' AND ' . $this->getDbTable()->getAdapter()->quoteInto("service=?", $serviceName);
        $existService = $this->getByDataTypeService($dataType, $serviceName);
        if (!empty($existService)) {
            return $this->getDbTable()->update($data, $where);
        }

        return $this->getDbTable()->insert($data);

    }

    /**
     * Get lists by dataType and Service name
     *
     * @param string $dataType
     * @param string $service
     * @return array|null
     * @throws Exception
     */
    public function getByDataTypeService($dataType, $service)
    {
        $where = $this->getDbTable()->getAdapter()->quoteInto("dataType=?", $dataType);
        $where .= ' AND ' . $this->getDbTable()->getAdapter()->quoteInto("service=?", $service);

        return $this->fetchAll($where);
    }

    /**
     * Delete by data type and service name
     *
     * @param string $service
     * @param string $dataType
     * @return mixed
     * @throws Exception
     */
    public function deleteServiceConfig($service, $dataType)
    {
        $where = $this->getDbTable()->getAdapter()->quoteInto("dataType=?", $dataType);
        $where .= ' AND ' . $this->getDbTable()->getAdapter()->quoteInto("service=?", $service);

        return $this->getDbTable()->delete($where);
    }

}

