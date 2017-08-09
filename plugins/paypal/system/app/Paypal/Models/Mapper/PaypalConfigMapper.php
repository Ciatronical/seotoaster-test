<?php

/**
 * Paypal configuration mapper
 *
 * Class Paypal_Models_Mapper_PaypalConfigMapper
 */
class Paypal_Models_Mapper_PaypalConfigMapper extends Application_Model_Mappers_Abstract
{

    /**
     * @var string
     */
    protected $_dbTable = 'Paypal_Models_Dbtables_PaypalConfigDbtable';

    /**
     * @var string
     */
    protected $_model = 'Paypal_Models_Models_PaypalConfigModel';

    /**
     * @param Paypal_Models_Models_PaypalConfigModel $config
     * @throws Exceptions_SeotoasterException
     */
    public function save($config)
    {
        if (!$config instanceof $this->_model) {
            throw new Exceptions_SeotoasterException('Given parameter should be ' . $this->_model . ' instance');
        }
        $data = array(

            'email' => $config->getEmail(),
            'apiSignature' => $config->getApiSignature(),
            'apiUser' => $config->getApiUser(),
            'apiPassword' => $config->getApiPassword(),
            'useSandbox' => $config->getUseSandbox()

        );
        $where = "id=1";
        $this->getDbTable()->update($data, $where);

    }

    /**
     * Get all config
     *
     * @return array|null
     */
    public function selectSettings()
    {
        return $this->fetchAll();
    }

}

