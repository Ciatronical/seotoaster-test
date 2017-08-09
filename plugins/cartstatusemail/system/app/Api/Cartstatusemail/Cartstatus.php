<?php
/**
 * Cart status config REST API controller
 *
 *
 */
class Api_Cartstatusemail_Cartstatus extends Api_Service_Abstract
{

    /**
     * @var array Access Control List
     */
    protected $_accessList = array(
        Tools_Security_Acl::ROLE_SUPERADMIN => array(
            'allow' => array('get', 'post', 'put', 'delete')
        ),
        Tools_Security_Acl::ROLE_ADMIN => array(
            'allow' => array('get', 'post', 'put', 'delete')
        ),
        Shopping::ROLE_SALESPERSON => array(
            'allow' => array('get', 'post', 'put', 'delete')
        )
    );

    /**
     * Get cart status email data
     *
     * Resourse:
     * : /api/cartstatusemail/cartstatus/id/:id
     *
     * HttpMethod:
     * : GET
     *
     * ## Parameters:
     * * id (type integer)
     * : id
     *
     * pairs (type sting)
     * : If given data will be returned as key-value array
     *
     * @return JSON List of groups
     */
    public function getAction()
    {
        $id = filter_var($this->_request->getParam('id'), FILTER_SANITIZE_NUMBER_INT);
        $cartStatusSettingsMapper = Cartstatusemail_Models_Mapper_CartstatusemailSettingsMapper::getInstance();
        $cartStatusDbtable = new Cartstatusemail_Models_Dbtables_CartstatusemailSettingsDbtable();
        if ($id) {
            $where = $cartStatusSettingsMapper->getDbTable()->getAdapter()->quoteInto('id=?', $id);
            $data = $cartStatusSettingsMapper->fetchAll($where);
            $allProductIdsData[0]['productsIds'] = $data[0]->getProductsIds();
        } else {
            $data = $cartStatusSettingsMapper->fetchAll();
            $select = $cartStatusDbtable->select()->from(
                'plugin_cartstatusemail_settings',
                array('productsIds' => new Zend_Db_Expr("GROUP_CONCAT(productsIds, ',', productsIds)"))
            );
            $allProductIdsData = $cartStatusSettingsMapper->getDbTable()->getAdapter()->fetchAll($select);
        }

        if (!empty($allProductIdsData)) {
            $productIdArray = explode(',', $allProductIdsData[0]['productsIds']);
            $productIdArray = array_unique($productIdArray);
            $select = $cartStatusDbtable->select()
                                        ->setIntegrityCheck(false)
                                        ->from(array('p' => 'page'), array('productId' => 'product.id', 'pageUrl' => 'p.url'))
                                        ->joinleft(array('product' => 'shopping_product'), 'product.page_id = p.id')
                                        ->where('product.id IN (?)', $productIdArray);
            $result = $cartStatusSettingsMapper->getDbTable()->getAdapter()->fetchAssoc($select);
        }

        foreach ($data as $key => $cartStatus) {
            $cartStatusData[$key]['cartStatus'] = $cartStatus->getCartStatus();
            $cartStatusData[$key]['periodHours'] = $cartStatus->getPeriodHours();
            $cartStatusData[$key]['productsIds'] = $cartStatus->getProductsIds();
            $cartStatusData[$key]['emailTemplate'] = $cartStatus->getEmailTemplate();
            $cartStatusData[$key]['emailFrom'] = $cartStatus->getEmailFrom();
            $cartStatusData[$key]['emailMessage'] = $cartStatus->getEmailMessage();
            $cartStatusData[$key]['productsRule'] = $cartStatus->getProductsRule();
            $cartStatusData[$key]['id'] = $cartStatus->getId();
            $cartStatusData[$key]['prodData'] = !empty($result) ? $result : '';

        }
        if (!empty($cartStatusData)) {
            return array_values($cartStatusData);
        }
        return array();

    }

    /**
     * New cart status email config settings
     *
     * Resourse:
     * : /api/cartstatusemail/cartstatus/
     *
     * HttpMethod:
     * : POST
     *
     * @return JSON New cart status email model
     */
    public function postAction()
    {
        $data = $this->getRequest()->getPost();
        $cache = Zend_Controller_Action_HelperBroker::getStaticHelper('Cache');
        $translator = Zend_Registry::get('Zend_Translate');

        if (!isset($data['periodDays']) && !isset($data['cartStatus'])) {
            $this->_error();
        }
        if ($data['cartStatus'] == '0') {
            $this->_error($translator->translate('Please choose cart status'));
        }

        if (!isset($data['productsIds'])) {
            $this->_error($translator->translate('Please add products'));
        }

        $data['periodHours'] = filter_var(trim($data['periodHours']), FILTER_SANITIZE_NUMBER_INT);

        if (empty($data['periodHours'])) {
            $this->_error($translator->translate('Period of hours Can\'t be empty'));
        }

        if ($data['productsRule'] != 'without') {
            if (empty($data['productsIds'])) {
                $this->_error($translator->translate('Please add products'));
            }
        } else {
            $data['productsIds'] = '';
        }

        $secureToken = $this->getRequest()->getParam(Tools_System_Tools::CSRF_SECURE_TOKEN, false);
        $tokenValid = Tools_System_Tools::validateToken($secureToken, Cartstatusemail::CARTSTATUS_SECURE_TOKEN);
        if (!$tokenValid) {
            $this->_error('invalid token');
        }

        $cartId = !empty($data['cartId']) ? $data['cartId'] : false;
        unset($data['secureToken'], $data['cartId']);
        $cartStatusSettingsMapper = Cartstatusemail_Models_Mapper_CartstatusemailSettingsMapper::getInstance();
        $checkExistingCartStatus = $cartStatusSettingsMapper->selectCartStatus(
            $data['cartStatus'],
            $data['periodHours'],
            $data['productsIds'],
            $data['productsRule']
        );

        $model = new Cartstatusemail_Models_Models_CartstatusemailSettingsModel();
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $model->{'set' . ucfirst($key)}($value);
            }
        }

       if ($cartId) {
           $cartStatusSettingsMapper->updateCartStatus($model, $cartId);
        } else {
           $cartStatusSettingsMapper->save($model);
       }
        $cache->clean('cartstatus_configdata', 'cart_status');
        return $model->toArray();
    }

    public function putAction()
    {

    }

    /**
     * Delete cart status email
     *
     * Resourse:
     * : /api/cartstatusemail/cartstatus/
     *
     * HttpMethod:
     * : DELETE
     *
     * ## Parameters:
     * id (type integer)
     * : cart id to delete
     *
     * @return JSON Result of removing cart status email
     */
    public function deleteAction()
    {
        $id = filter_var($this->_request->getParam('id'), FILTER_SANITIZE_NUMBER_INT);
        $cache = Zend_Controller_Action_HelperBroker::getStaticHelper('Cache');

        if (!$id) {
            $this->_error();
        }
        Cartstatusemail_Models_Mapper_CartstatusemailQueueMapper::getInstance()->deleteQueueByCartStatusId($id, array('0'));
        $cache->clean('cartstatus_configdata', 'cart_status');
        return Cartstatusemail_Models_Mapper_CartstatusemailSettingsMapper::getInstance()->deleteCartStatus($id);
    }

}
