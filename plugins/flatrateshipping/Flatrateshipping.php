<?php
/**
 * Flat Rate shipping calculator
 *
 * @author Seotoaster core team <pavel.k@seosamba.com>
 */

class Flatrateshipping extends Tools_Shipping_Plugin
{

    /**
     * Default amount zones on configuration screen
     */
    const QUANTITY_ZONES_ON_SCREEN = 6;

    /**
     * Up to this amount (weight)
     */
    const AMOUNT_TYPE_UP_TO = 'up to';

    /**
     * Over this amount (weight)
     */
    const AMOUNT_TYPE_OVER = 'over';

    /**
     * Eachover amount (weight)
     */
    const AMOUNT_TYPE_EACH_OVER = 'eachover';

    /**
     * National shipping
     */
    const SHIPPING_TYPE_NATIONAL = 'national';

    /**
     * International shipping
     */
    const SHIPPING_TYPE_INTERNATIONAL = 'international';

    /**
     * national plus international shipping
     */
    const SHIPPING_TYPE_ALL = 'all';

    /**
     * Alias for shipping config screen
     */
    const PLUGIN_ALIAS = 'Fee by order\'s weight or amount';

    /**
     * secure token
     */
    const FLATRATE_SECURE_TOKEN = 'FlatrateshippingToken';

    /**
     * Default config row
     *
     * @var array
     */
    protected $_defaultFlatshippingRow = array(
        '1' => array(
            'id' => 1,
            'amount_type_limit' => Flatrateshipping::AMOUNT_TYPE_UP_TO,
            'amount_limit' => 0
        )
    );


    /**
     * List of action that should be allowed to specific roles
     *
     * By default all of actions of your plugin are available to the guest user
     * @var array
     */
    protected $_securedActions = array(
        Tools_Security_Acl::ROLE_SUPERADMIN => array(
            'config'
        )
    );

    /**
     * Init method.
     *
     * Use this method to init your plugin's data and variables
     * Use this method to init specific helpers, view, etc...
     */
    protected function _init()
    {
        parent::_init();
        $this->_view->setScriptPath(__DIR__ . '/system/views/');
    }

    /**
     * Secured action.
     *
     * Will be available to the superadmin only
     */
    public function configAction()
    {
        if (Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_PLUGINS)) {
            $form = new FlatRateShipping_Forms_Config();
            if ($this->_request->isPost()) {
                $form = Tools_System_Tools::addTokenValidatorZendForm($form, self::FLATRATE_SECURE_TOKEN);
                if ($form->isValid($this->_request->getParams())) {
                    $dataFlatConfig = $this->_request->getParams();
                    $config = array(
                        'name' => strtolower(__CLASS__),
                        'config' => array('title' => $dataFlatConfig['title'], 'units' => $dataFlatConfig['units'])
                    );
                    $dataFlatConfig = $this->_request->getParams();
                    if (isset($dataFlatConfig['configData']) && !empty($dataFlatConfig['configData'])) {
                        $flatRateShippingConfigMapper = FlatRateShipping_Models_Mapper_FlatRateShippingConfigMapper::getInstance(
                        );
                        $flatRateShippingConfigModel = new FlatRateShipping_Models_Model_FlatRateShippingConfigModel();
                        foreach ($dataFlatConfig['configData'] as $conf) {
                            if ($conf['amountLimit'] == 0) {
                                $flatRateShippingConfigMapper->deleteConfigRow($conf['configRowId']);
                            } else {
                                $flatRateShippingConfigModel->setId($conf['configRowId']);
                                $flatRateShippingConfigModel->setAmountLimit($conf['amountLimit']);
                                $flatRateShippingConfigModel->setAmountTypeLimit($conf['amountType']);
                                $flatRateShippingConfigModel->setZones($conf['zoneWithAmount']);
                                $flatRateShippingConfigMapper->save($flatRateShippingConfigModel);
                            }
                        }
                    }
                    if (Models_Mapper_ShippingConfigMapper::getInstance()->save($config)) {
                        $this->_jsonHelper->direct(array('done' => 'true', 'status' => 'refresh'));
                    }
                }
            } else {
                $this->_view->zones = Models_Mapper_Zone::getInstance()->fetchAll();
                $config = Models_Mapper_ShippingConfigMapper::getInstance()->find(strtolower(__CLASS__));
                if (!empty($config['config'])) {
                    $config['config']['titleFlatRate'] = $config['config']['title'];
                    $config['config']['unitsFlatRate'] = $config['config']['units'];
                    $form->populate($config['config']);
                }
                $flatrateConf = FlatRateShipping_Models_Mapper_FlatRateShippingConfigMapper::getInstance()->getConfig();
                $this->_view->flatrateConfZones = FlatRateShipping_Models_Mapper_FlatRateShippingConfigMapper::getInstance(
                )->getZones();
                if (empty($flatrateConf)) {
                    $flatrateConf = $this->_defaultFlatshippingRow;
                }
                $this->_view->flatrateConf = $flatrateConf;
            }
            $this->_view->form = $form;
            $this->_view->shoppingConfig = Models_Mapper_ShoppingConfig::getInstance()->getConfigParams();
            echo $this->_view->render('config.phtml');
        }
    }

    /**
     * Calculate flatrateshipping
     *
     * @param bool $noJson
     * @return array|JSON
     * @throws Exceptions_SeotoasterException
     * @throws Exceptions_SeotoasterPluginException
     */
    public function calculateAction($noJson = false)
    {
        if (sizeof(Tools_ShoppingCart::getInstance()->getContent()) === 0) {
            throw new Exceptions_SeotoasterException('Cart is empty');
        }

        $pluginSettings = Models_Mapper_ShippingConfigMapper::getInstance()->find(strtolower(get_called_class()));
        if (!$pluginSettings || !isset($pluginSettings['config'])) {
            throw new Exceptions_SeotoasterPluginException(__CLASS__ . ' Error: plugin is not configured');
        }

        $origination = Tools_Misc::clenupAddress($this->_shoppingConfig);
        $destination = Tools_ShoppingCart::getAddressById(
            Tools_ShoppingCart::getInstance()->getAddressKey(Models_Model_Customer::ADDRESS_TYPE_SHIPPING)
        );
        $deliveryType = ($origination['country'] === $destination['country']) ?
            Forms_Shipping_FreeShipping::DESTINATION_NATIONAL : Forms_Shipping_FreeShipping::DESTINATION_INTERNATIONAL;

        switch ($pluginSettings['config']['units']) {
            case FlatRateShipping_Forms_Config::COMPARE_BY_AMOUNT:
                $comparator = Tools_ShoppingCart::getInstance()->calculateCartPrice();
                break;
            case FlatRateShipping_Forms_Config::COMPARE_BY_WEIGHT:
                $comparator = Tools_ShoppingCart::getInstance()->calculateCartWeight();
                break;
        }

        $finalAmount = 0;
        $matchFound = true;
        $flatRateShippingAnalyzeData = array();
        $flatRateShippingConfigMapper = FlatRateShipping_Models_Mapper_FlatRateShippingConfigMapper::getInstance();
        $zoneIds = $flatRateShippingConfigMapper->getZoneIds();
        $zoneId = $this->_getZone($destination, $zoneIds);

        $predefinedShippingMethods = $flatRateShippingConfigMapper->getPredefinedShippingMethods($deliveryType);
        if (!empty($predefinedShippingMethods)) {
            $flatRateShippingAnalyzeData = $predefinedShippingMethods;
        } elseif ($zoneId != 0) {
            $flatRateShippingAnalyzeData = $flatRateShippingConfigMapper->getByZoneId($zoneId);
        }

        if (!empty($flatRateShippingAnalyzeData)) {
            $finalAmount = $this->_calculateFinalAmount($flatRateShippingAnalyzeData, $comparator);
        } else {
            $matchFound = false;
        }

        $method = array();
        if (!empty($pluginSettings['config']['title'])) {
            $method['type'] = $pluginSettings['config']['title'];
        }

        $method['price'] = $finalAmount;
        if ($matchFound) {
            $storeRatesResult = $this->_storeRates(array($method));
            $method = $storeRatesResult[0];
        }
        if ($method['price'] == '') {
            $response = array('error' => Tools_Misc::getDefaultCheckoutErrorMessage());
            return ($noJson === true ? $response : $this->_jsonHelper->direct(array($response)));
        }
        if ($noJson === true) {
            return $method;
        }
        $method['price'] = $this->_view->currency($method['price']);

        $this->_jsonHelper->direct(array($method));
    }

    /**
     * Apply shipping price according to zone config
     *
     * @param zone config params array(amount_type_limit, amount_limit, amount_zone) $zonesFlatConfigData
     * @param string weight or amount $comparator
     * @return int
     */
    protected function _calculateFinalAmount($zonesFlatConfigData, $comparator)
    {
        $finalAmount = 0;
        foreach ($zonesFlatConfigData as $flatRateZone) {
            if ($finalAmount === 0) {
                if ($flatRateZone['amount_type_limit'] !== self::AMOUNT_TYPE_EACH_OVER) {
                    $maxAmount = $flatRateZone['amount_zone'];
                }
                if ($flatRateZone['amount_type_limit'] === self::AMOUNT_TYPE_UP_TO && $flatRateZone['amount_limit'] > $comparator && $flatRateZone['amount_zone'] !== 0) {
                    $finalAmount = $flatRateZone['amount_zone'];
                } elseif ($flatRateZone['amount_type_limit'] === self::AMOUNT_TYPE_OVER && $flatRateZone['amount_limit'] < $comparator && $flatRateZone['amount_zone'] !== 0) {
                    $finalAmount = $flatRateZone['amount_zone'];
                } elseif ($flatRateZone['amount_type_limit'] === self::AMOUNT_TYPE_EACH_OVER && $flatRateZone['amount_limit'] < $comparator && $flatRateZone['amount_zone'] !== 0) {
                    if (isset($maxAmount)) {
                        $differenceValueAmount = ($comparator - $flatRateZone['amount_limit']) * $flatRateZone['amount_zone'];
                        $finalAmount = $maxAmount + $differenceValueAmount;
                    }
                }
            }
        }
        return $finalAmount;
    }

    /**
     * Find proper zone by shipping address
     *
     * @param array array('country', 'zip', 'state') $address
     * @param array $zoneIds config zone ids
     * @return int
     */
    protected function _getZone($address = null, $zoneIds = array())
    {
        if (is_null($address) || empty($address)) {
            return 0;
        } else {
            $address = Tools_Misc::clenupAddress($address);
        }
        $zones = Models_Mapper_Zone::getInstance()->fetchAll();
        if (is_array($zones) && !empty($zones)) {
            $zoneMatch = 0;
            $maxRate = 0;
            foreach ($zones as $zone) {
                $matchRate = 0;
                if (empty($address['country']) && empty($address['state']) && empty($address['zip'])) {
                    continue;
                }
                $currentZoneId = $zone->getId();
                if (!array_key_exists($currentZoneId, $zoneIds)) {
                   continue;
                }

                $countries = $zone->getCountries(true);
                if ($zone->getZip() && !empty($address['zip'])) {

                    //wildcard zip analyze
                    $zipMatched = false;
                    $wildcardZones = preg_grep('~\*~', $zone->getZip());
                    if (!empty($wildcardZones)) {
                        foreach ($wildcardZones as $wildcardZone) {
                            $wildcardPosition = strpos($wildcardZone, '*');
                            $currentZip = substr_replace($address['zip'], '', $wildcardPosition);
                            $matchZip = substr_replace($wildcardZone, '', $wildcardPosition);
                            if ($currentZip === $matchZip) {
                                $matchRate += 5;
                                $zipMatched = true;
                            }
                        }
                    }

                    if (in_array($address['zip'], $zone->getZip())
                        && !$zipMatched
                    ) {
                        $matchRate += 5;
                    } elseif (!$zipMatched) {
                        continue;
                    }
                }
                if (!empty($address['state'])) {
                    if ($zone->getStates()) {
                        $states = array_map(
                            function ($state) {
                                return $state['id'];
                            },
                            $zone->getStates()
                        );
                        if (in_array($address['state'], $states)) {
                            $matchRate += 3;
                        }
                    }
                }
                if (!empty($countries)) {
                    if (in_array($address['country'], $countries)) {
                        $matchRate += 1;
                    }
                }
                if ($matchRate && $matchRate > $maxRate) {
                    $maxRate = $matchRate;
                    $zoneMatch = $zone->getId();
                }
                unset($countries, $states);
            }
            return $zoneMatch;
        }
        return 0;
    }

}
