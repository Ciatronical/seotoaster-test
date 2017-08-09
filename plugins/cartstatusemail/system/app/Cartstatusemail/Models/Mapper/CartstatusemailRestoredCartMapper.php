<?php


class Cartstatusemail_Models_Mapper_CartstatusemailRestoredCartMapper extends Application_Model_Mappers_Abstract
{

    protected $_dbTable = 'Cartstatusemail_Models_Dbtables_CartstatusemailRestoredCartDbtable';

    protected $_model = 'Cartstatusemail_Models_Models_CartstatusemailRestoredCartModel';

    public function save($model)
    {
        if (!$model instanceof $this->_model) {
            throw new Exceptions_SeotoasterException('Given parameter should be ' . $this->_model . ' instance');
        }
        $data = array(
            'cart_id' => $model->getCartId(),
            'restored_at' => $model->getRestoredAt(),
            'sent_at' => $model->getSentAt(),
            'code' => $model->getCode(),
            'user_id' => $model->getUserId(),
            'cart_status' => $model->getCartStatus()
        );

        $restoredCartExists = $this->findByCartId($data['cart_id']);
        if (empty($restoredCartExists)) {
            return $this->getDbTable()->insert($data);
        }
        $where = $this->getDbTable()->getAdapter()->quoteInto("cart_id = ?", $data['cart_id']);

        return $this->getDbTable()->update($data, $where);

    }

    /**
     * Find by system cart id (order id)
     *
     * @param int $cartId cart id (order id)
     * @return mixed
     * @throws Exception
     */
    public function findByCartId($cartId)
    {
        $where = $this->getDbTable()->getAdapter()->quoteInto('cart_id = ?', $cartId);

        return $this->fetchAll($where);
    }

    /**
     * Find cart restored info by code
     *
     * @param string $code unique key for restore cart content
     * @return mixed
     * @throws Exception
     */
    public function findByCode($code)
    {
        $where = $this->getDbTable()->getAdapter()->quoteInto('code = ?', $code);

        return $this->fetchAll($where);
    }


    /**
     * Find cart restored info by code
     *
     * @param string $code unique key for restore cart content
     * @param int $userId system user id
     * @return mixed
     * @throws Exception
     */
    public function findByCodeUserId($code, $userId)
    {
        $where = $this->getDbTable()->getAdapter()->quoteInto('code = ?', $code);
        $where .= ' AND ' .$this->getDbTable()->getAdapter()->quoteInto('user_id = ?', $userId);

        return $this->fetchAll($where);
    }

    /**
     * Find restored cart info using user id and cart id
     *
     * @param int $userId system user id
     * @param int $cartId cart id (order id)
     * @return array|null
     * @throws Exception
     */
    public function findByUserIdCartId($userId, $cartId)
    {
        $where = $this->getDbTable()->getAdapter()->quoteInto('user_id = ?', $userId);
        $where .= ' AND ' . $this->getDbTable()->getAdapter()->quoteInto('cart_id = ?', $cartId);

        return $this->fetchAll($where);
    }
}

