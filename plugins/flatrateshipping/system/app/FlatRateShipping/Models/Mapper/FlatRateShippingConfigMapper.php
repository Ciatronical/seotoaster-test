<?php

/**
 * Class FlatRateShipping_Models_Mapper_FlatRateShippingConfigMapper
 */
class FlatRateShipping_Models_Mapper_FlatRateShippingConfigMapper extends Application_Model_Mappers_Abstract
{

    protected $_dbTable = 'FlatRateShipping_Models_DbTable_FlatRateShippingConfigDbtable';

    protected $_model = 'FlatRateShipping_Models_Model_FlatRateShippingConfigModel';

    /**
     * Save config params
     *
     * @param FlatRateShipping_Models_Model_FlatRateShippingConfigModel $config
     * @return mixed
     * @throws Exceptions_SeotoasterException
     */
    public function save($config)
    {
        if (!$config instanceof $this->_model) {
            throw new Exceptions_SeotoasterException('Given parameter should be ' . $this->_model . ' instance');
        }
        $data = array(
            'id' => $config->getId(),
            'amount_type_limit' => $config->getAmountTypeLimit(),
            'amount_limit' => $config->getAmountLimit()
        );
        $id = $config->getId();
        $zones = $config->getZones();
        $this->_saveZones($id, $zones);
        $where = $this->getDbTable()->getAdapter()->quoteInto("id=?", $id);
        $existRow = $this->getById($id);
        if (!empty($existRow)) {
            return $this->getDbTable()->update($data, $where);
        }
        return $this->getDbTable()->insert($data);

    }

    /**
     * Get config by id
     *
     * @param int $id
     * @return array|null
     */
    public function getById($id)
    {
        $where = $this->getDbTable()->getAdapter()->quoteInto("id=?", $id);
        return $this->fetchAll($where);
    }

    /**
     * Get all config
     *
     * @return mixed
     */
    public function getConfig()
    {
        $select = $this->getDbTable()->getAdapter()->select()
            ->from(array('plugin_flatrateshipping_config', array('id', 'amount_type_limit', 'amount_limit')));
        return $this->getDbTable()->getAdapter()->fetchAssoc($select);
    }

    /**
     * Get all zones
     *
     * @return mixed
     */
    public function getZones()
    {
        $select = $this->getDbTable()->getAdapter()->select()
            ->from(
                'plugin_flatrateshipping_zones_config',
                array(
                    'conf_key' => new Zend_Db_Expr("CONCAT(config_id, '_', config_zone_id)"),
                    'flatrate_zone_id',
                    'amount_zone',
                    'config_zone_id',
                    'config_id'
                )
            );
        return $this->getDbTable()->getAdapter()->fetchAssoc($select);
    }

    /**
     * Process zones
     *
     * @param int $configId
     * @param array $zones
     */
    private function _saveZones($configId, $zones)
    {
        $where = $this->getDbTable()->getAdapter()->quoteInto("config_id=?", $configId);
        $this->getDbTable()->getAdapter()->delete('plugin_flatrateshipping_zones_config', $where);
        foreach ($zones as $confZoneId => $value) {
            if (is_array($value)) {
                $data = array(
                    'config_id' => $configId,
                    'flatrate_zone_id' => $value['zoneId'],
                    'amount_zone' => $value['zoneAmount'],
                    'config_zone_id' => $confZoneId
                );
                $this->getDbTable()->getAdapter()->insert('plugin_flatrateshipping_zones_config', $data);
            }
        }
    }

    /**
     * Get by zoneId
     *
     * @param int $zoneId
     * @return mixed
     */
    public function getByZoneId($zoneId)
    {
        $where = $this->getDbTable()->getAdapter()->quoteInto("pfzc.flatrate_zone_id=?", $zoneId);
        $where .= ' AND ' . $this->getDbTable()->getAdapter()->quoteInto("pfc.amount_limit>?", 0);
        $select = $this->getDbTable()->getAdapter()->select()
            ->from(
                array(
                    'pfzc' => 'plugin_flatrateshipping_zones_config',
                    array('config_id', 'flatrate_zone_id', 'amount_zone')
                )
            )
            ->join(
                array('pfc' => 'plugin_flatrateshipping_config'),
                'pfc.id=pfzc.config_id',
                array('amount_type_limit', 'amount_limit')
            )
            ->order('pfc.amount_limit ASC')
            ->where($where);
        return $this->getDbTable()->getAdapter()->fetchAll($select);
    }

    /**
     * Delete config by configId
     *
     * @param int $configId
     */
    public function deleteConfigRow($configId)
    {
        $where = $this->getDbTable()->getAdapter()->quoteInto("config_id=?", $configId);
        $this->getDbTable()->getAdapter()->delete('plugin_flatrateshipping_zones_config', $where);
        $where = $this->getDbTable()->getAdapter()->quoteInto("id=?", $configId);
        $this->getDbTable()->getAdapter()->delete('plugin_flatrateshipping_config', $where);
    }

    /**
     * Get shipping methods by delivery type
     *
     * @param string $deliveryType
     * @return mixed
     */
    public function getPredefinedShippingMethods($deliveryType)
    {
        if ($deliveryType == Flatrateshipping::SHIPPING_TYPE_NATIONAL) {
            $where = $this->getDbTable()->getAdapter()->quoteInto(
                "flatrate_zone_id=?",
                Flatrateshipping::SHIPPING_TYPE_NATIONAL
            );
            $where .= ' OR ' . $this->getDbTable()->getAdapter()->quoteInto(
                "flatrate_zone_id=?",
                Flatrateshipping::SHIPPING_TYPE_ALL
            );
        } else {
            $where = $this->getDbTable()->getAdapter()->quoteInto(
                "flatrate_zone_id=?",
                Flatrateshipping::SHIPPING_TYPE_INTERNATIONAL
            );
            $where .= ' OR ' . $this->getDbTable()->getAdapter()->quoteInto(
                "flatrate_zone_id=?",
                Flatrateshipping::SHIPPING_TYPE_ALL
            );
        }
        $select = $this->getDbTable()->getAdapter()->select()
            ->from(
                array(
                    'pfzc' => 'plugin_flatrateshipping_zones_config',
                    array('config_id', 'flatrate_zone_id', 'amount_zone')
                )
            )
            ->join(
                array('pfc' => 'plugin_flatrateshipping_config'),
                'pfc.id=pfzc.config_id',
                array('amount_type_limit', 'amount_limit')
            )
            ->order('pfc.amount_limit ASC')
            ->where($where);
        return $this->getDbTable()->getAdapter()->fetchAll($select);
    }

    /**
     * Get existing zone ids
     *
     * @return mixed
     * @throws Exception
     */
    public function getZoneIds()
    {
        $select = $this->getDbTable()->getAdapter()->select()
            ->from(array(
                'plugin_flatrateshipping_zones_config'
            ),
                array('flatrate_zone_id', 'flatrate_zone_id')
            )->group('flatrate_zone_id');

        return $this->getDbTable()->getAdapter()->fetchAssoc($select);
    }
}

