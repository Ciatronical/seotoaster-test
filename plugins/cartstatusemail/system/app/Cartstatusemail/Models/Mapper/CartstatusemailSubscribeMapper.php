<?php


class Cartstatusemail_Models_Mapper_CartstatusemailSubscribeMapper extends Application_Model_Mappers_Abstract
{

    protected $_dbTable = 'Cartstatusemail_Models_Dbtables_CartstatusemailSubscribeDbtable';

    protected $_model = 'Cartstatusemail_Models_Models_CartstatusemailSubscribeModel';

    public function save($model)
    {
        if (!$model instanceof $this->_model) {
            throw new Exceptions_SeotoasterException('Given parameter should be ' . $this->_model . ' instance');
        }
        $data = array(
            'status' => $model->getStatus(),
            'user_id' => $model->getUserId(),
            'code' => $model->getCode()
        );

        $subscribeExists = $this->findByUserId($data['user_id']);
        if (empty($subscribeExists)) {
            return $this->getDbTable()->insert($data);

        }
        $where = $this->getDbTable()->getAdapter()->quoteInto("user_id = ?", $data['user_id']);

        return $this->getDbTable()->update($data, $where);

    }

    /**
     * Find by system user id
     *
     * @param int $userId system user id
     * @return mixed
     * @throws Exception
     */
    public function findByUserId($userId)
    {
        $where = $this->getDbTable()->getAdapter()->quoteInto('user_id = ?', $userId);
        $select = $this->getDbTable()->getAdapter()->select()->from('plugin_cartstatusemail_subscribe')
            ->where($where);

        return $this->getDbTable()->getAdapter()->fetchRow($select);
    }

    /**
     * Find subscribe info by code
     *
     * @param string $code
     * @return mixed
     * @throws Exception
     */
    public function findByCode($code)
    {
        $where = $this->getDbTable()->getAdapter()->quoteInto('pcs.code = ?', $code);
        $select = $this->getDbTable()->getAdapter()->select()->from(array('pcs' => 'plugin_cartstatusemail_subscribe'),
            array('pcs.user_id', 'u.email', 'pcs.status'))
            ->join(array('u' => 'user'), 'u.id=pcs.user_id', array())
            ->where($where);

        return $this->getDbTable()->getAdapter()->fetchRow($select);
    }

    /**
     * Find subscribe info by code and user system email
     *
     * @param string $code unique code
     * @param string $email system user email
     * @return mixed
     * @throws Exception
     */
    public function findByCodeEmail($code, $email)
    {
        $where = $this->getDbTable()->getAdapter()->quoteInto('pcs.code = ?', $code);
        $where .= ' AND ' . $this->getDbTable()->getAdapter()->quoteInto('u.email = ?', $email);
        $select = $this->getDbTable()->getAdapter()->select()->from(array('pcs' => 'plugin_cartstatusemail_subscribe'),
            array('pcs.user_id', 'u.email', 'pcs.status'))
            ->join(array('u' => 'user'), 'u.id=pcs.user_id', array())
            ->where($where);

        return $this->getDbTable()->getAdapter()->fetchRow($select);
    }

    /**
     * Change subscription status
     *
     * @param string $code unique code
     * @param string $status (subscribed, unsubscribed)
     * @return mixed
     * @throws Exception
     */
    public function updateStatus($code, $status = Cartstatusemail::SUBSCRIBED_STATUS_SUBSCRIBED)
    {
        $where = $this->getDbTable()->getAdapter()->quoteInto("code = ?", $code);

        return $this->getDbTable()->update(array('status' => $status), $where);
    }

    /**
     * Find subscription info by user email
     *
     * @param string $email user email
     * @return mixed
     * @throws Exception
     */
    public function findByEmail($email)
    {
        $where = $this->getDbTable()->getAdapter()->quoteInto('u.email = ?', $email);
        $select = $this->getDbTable()->getAdapter()->select()->from(array('pcs' => 'plugin_cartstatusemail_subscribe'),
            array('pcs.user_id', 'u.email', 'pcs.status', 'pcs.code'))
            ->join(array('u' => 'user'), 'u.id=pcs.user_id', array())
            ->where($where);

        return $this->getDbTable()->getAdapter()->fetchRow($select);
    }
}

