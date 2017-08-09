<?php

/**
 * Class Invoicetopdf_Models_Dbtables_InvoicetopdfSettingDbtable
 */
class Invoicetopdf_Models_Dbtables_InvoicetopdfSettingDbtable extends Zend_Db_Table_Abstract
{

    protected $_name = 'plugin_invoicetopdf_settings';

    /**
     * update config params
     *
     * @param Param name $name
     * @param Param value $value
     * @return bool
     */
    public function updateParam($name, $value)
    {
        if ($value === null) {
            return false;
        }
        $rowset = $this->find($name);
        $row = $rowset->current();
        if ($row === null) {
            $row = $this->createRow(
                array(
                    'name' => $name,
                    'value' => $value
                )
            );
        } else {
            $row->value = $value;
        }

        return $row->save();
    }

    /**
     * Return invoice to pdf config
     *
     * @return array
     */
    public function selectConfig()
    {
        return $this->getAdapter()->fetchPairs($this->select()->from($this->_name));
    }
}

