<?php


class Toastauth_Models_Mapper_ToastauthSettingsMapper extends Application_Model_Mappers_Abstract
{

    protected $_dbTable = 'Toastauth_Models_Dbtables_ToastauthSettingDbtable';

    protected $_model = 'Toastauth_Models_Models_ToastauthSettingsModel';

    public function save($settings)
    {
        if (!$settings instanceof $this->_model) {
            throw new Exceptions_SeotoasterException('Given parameter should be ' . $this->_model . ' instance');
        }
        $data        = array(
            'name'     => $settings->getName(),
            'settings' => $settings->getSettings(),
            'status'   => $settings->getStatus()
        );
        $serviceName = $settings->getName();
        $where       = $this->getDbTable()->getAdapter()->quoteInto("name=?", $serviceName);
        $existForm   = $this->getProvider($serviceName);
        if (!empty($existForm)) {
            return $this->getDbTable()->update($data, $where);
        }

        return $this->getDbTable()->insert($data);

    }

    public function getProviders($enabled = false)
    {
        $query = $this->getDbTable()->getAdapter()->select()->from(
            'plugin_toastauth_settings',
            array('name', 'settings', 'status')
        );
        if ($enabled) {
            $query = $query->where('status=?', '1');
        }

        return $this->getDbTable()->getAdapter()->fetchAssoc($query);
    }

    public function getProvider($name, $enabled = false)
    {
        $query = $this->getDbTable()->getAdapter()->select()->from(
            'plugin_toastauth_settings',
            array('name', 'settings', 'status')
        )
                      ->where("name=?", $name);
        if ($enabled) {
            $query = $query->where('status=?', '1');
        }

        return $this->getDbTable()->getAdapter()->fetchRow($query);
    }

}

