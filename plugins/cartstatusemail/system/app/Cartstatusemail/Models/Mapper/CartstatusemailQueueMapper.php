<?php


class Cartstatusemail_Models_Mapper_CartstatusemailQueueMapper extends Application_Model_Mappers_Abstract {

    protected $_dbTable = 'Cartstatusemail_Models_Dbtables_CartstatusemailQueueDbtable';

    protected $_model   = 'Cartstatusemail_Models_Models_CartstatusemailQueueModel';

    public function save($queue) {
        if(!$queue instanceof $this->_model) {
            throw new Exceptions_SeotoasterException('Given parameter should be ' . $this->_model . ' instance');
        }
        $data = array(
            'cartStatusId'  => $queue->getCartStatusId(),
            'status'        => $queue->getStatus(),
            'cartId'        => $queue->getCartId(),
            'userEmail'     => $queue->getUserEmail(),
            'userFullName'  => $queue->getUserFullName(),
            'cartStatus'    => $queue->getCartStatus(),
            'emailTemplate' => $queue->getEmailTemplate(),
            'emailFrom'     => $queue->getEmailFrom(),
            'emailMessage'  => $queue->getEmailMessage(),
            'sentAt'        => $queue->getSentAt()
        );

        return $this->getDbTable()->insert($data);

    }

    public function deleteQueueByCartStatusId($cartStatusId, array $withStatus = array())
    {
        $where = $this->getDbTable()->getAdapter()->quoteInto("cartStatusId=?", $cartStatusId);
        if (!empty($withStatus)) {
            $where .= ' AND '. $this->getDbTable()->getAdapter()->quoteInto("status IN (?)", $withStatus);
        }

        return $this->getDbTable()->delete($where);
    }

    public function selectFromQueue($cartStatusId, $cartId, $userEmail){
        $where = $this->getDbTable()->getAdapter()->quoteInto("cartStatusId=?", $cartStatusId);
        $where .= ' AND '. $this->getDbTable()->getAdapter()->quoteInto("cartId=?", $cartId);
        $where .= ' AND '. $this->getDbTable()->getAdapter()->quoteInto("userEmail=?", $userEmail);
        return $this->fetchAll($where);
    }

    public function getAllQueue(){
        $where = $this->getDbTable()->getAdapter()->quoteInto("status=?", '0');
        $select = $this->getDbTable()->getAdapter()->select()->from('plugin_cartstatusemail_queue')->where($where)->limit(3, 0);
        $resultSet =  $this->getDbTable()->getAdapter()->fetchAll($select);
        $entries = array();
        foreach ($resultSet as $row) {
            $entries[] = new $this->_model($row);
        }
        return $entries;
    }

    /**
     * Update queue status
     *
     * @param int $queueId queue id
     * @param string $sentStatus (0,1,2)
     * @param bool $setSentDate assign date
     * @throws Exception
     */
    public function updateQueueStatus($queueId, $sentStatus, $setSentDate = false)
    {
        $where = $this->getDbTable()->getAdapter()->quoteInto("id=?", $queueId);
        if ($setSentDate) {
            $data = array('status' => $sentStatus, 'sentAt' => date(Tools_System_Tools::DATE_MYSQL, strtotime('now')));
        } else {
            $data = array('status' => $sentStatus);
        }

        $this->getDbTable()->update($data, $where);
    }

    public function cleanQueueByCartIdStatus($cartId, $cartStatus){
        $cacheHelper = Zend_Controller_Action_HelperBroker::getExistingHelper('cache');
        $where = $this->getDbTable()->getAdapter()->quoteInto("cartId=?", $cartId);
        $cartInQueueExist = $this->fetchAll($where);
        if(!empty($cartInQueueExist)){
            if($cartInQueueExist[0]->getCartStatus() != $cartStatus){
                $this->getDbTable()->delete($where);
                $cacheHelper->clean('cartstatus_configdata', 'cart_status');
            }
        }
    }

    /**
     * Update queue status by email
     *
     * @param string $email user email
     * @param string $status queue status (0, 1, 2)
     */
    public function updateQueueStatusByEmail($email, $status = Cartstatusemail::QUEUE_STATUS_NOT_SENT)
    {
        $where = $this->getDbTable()->getAdapter()->quoteInto("userEmail = ?", $email);
        return $this->getDbTable()->update(array('status' => $status), $where);
    }

}

