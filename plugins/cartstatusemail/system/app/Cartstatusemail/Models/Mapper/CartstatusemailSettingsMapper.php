<?php


class Cartstatusemail_Models_Mapper_CartstatusemailSettingsMapper extends Application_Model_Mappers_Abstract {

    protected $_dbTable = 'Cartstatusemail_Models_Dbtables_CartstatusemailSettingsDbtable';

    protected $_model   = 'Cartstatusemail_Models_Models_CartstatusemailSettingsModel';

    public function save($settings) {
        if(!$settings instanceof $this->_model) {
            throw new Exceptions_SeotoasterException('Given parameter should be ' . $this->_model . ' instance');
        }
        $data = array(
            'cartStatus'    => $settings->getCartStatus(),
            'periodHours'   => $settings->getPeriodHours(),
            'productsIds'   => $settings->getProductsIds(),
            'emailTemplate' => $settings->getEmailTemplate(),
            'emailFrom'     => $settings->getEmailFrom(),
            'emailMessage'  => $settings->getEmailMessage(),
            'productsRule'  => $settings->getProductsRule()
         );

        return $this->getDbTable()->insert($data);

    }

    public function deleteCartStatus($id) {
        $where = $this->getDbTable()->getAdapter()->quoteInto("id=?", $id);
        return $this->getDbTable()->delete($where);
    }

    public function updateCartStatus($settings, $id) {
        $data = array(
            'cartStatus'    => $settings->getCartStatus(),
            'periodHours'   => $settings->getPeriodHours(),
            'productsIds'   => $settings->getProductsIds(),
            'emailTemplate' => $settings->getEmailTemplate(),
            'emailFrom'     => $settings->getEmailFrom(),
            'emailMessage'  => $settings->getEmailMessage(),
            'productsRule'  => $settings->getProductsRule()
        );
        $where = $this->getDbTable()->getAdapter()->quoteInto('id = ?', $id);
        return $this->getDbTable()->update($data, $where);
    }

    public function selectCartStatusById($id) {
        $where = $this->getDbTable()->getAdapter()->quoteInto("id=?", $id);
        return $this->fetchAll($where);
    }

    public function selectCartStatus($cartStatus, $periodHours, $productsIds, $productsRule) {
        $where =  $this->getDbTable()->getAdapter()->quoteInto('cartStatus=?', $cartStatus);
        $where .= ' AND '. $this->getDbTable()->getAdapter()->quoteInto('periodHours=?', $periodHours);
        $where .= ' AND '. $this->getDbTable()->getAdapter()->quoteInto('productsIds=?', $productsIds);
        $where .= ' AND '. $this->getDbTable()->getAdapter()->quoteInto('productsRule=?', $productsRule);
        return $this->fetchAll($where);
    }


}

