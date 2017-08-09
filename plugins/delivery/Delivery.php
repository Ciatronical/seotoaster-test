<?php


class Delivery extends Tools_Shipping_Plugin{

	private $_googleDistanceService = 'http://maps.googleapis.com/maps/api/distancematrix/json?origins=';
    private $_origination;
    private $_destination;
    private $_packageWeight;
    private $_nationalServices;
    private $_config;
    const DELIVERY_SERVICE_NAME = 'Delivery service';

    /**
     * Secure token
     */
    const DELIVERY_SECURE_TOKEN = 'DeliveryToken';

    /*
     * Alias for the shipping config screen
     */
    const PLUGIN_ALIAS = 'Fee by distance to destination';

    
    public function configAction(){
        if(Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_PLUGINS)){
            $form = new Delivery_Forms_SettingsForm();
            $name = strtolower(__CLASS__);
            $storeConfig = Models_Mapper_ShoppingConfig::getInstance()->getConfigParams();
            $this->_view->storeLocation = $this->_prepareStoreAddress($storeConfig);
			if($this->_request->isPost()){
                $secureToken = $this->_request->getParam(Tools_System_Tools::CSRF_SECURE_TOKEN, false);
                $tokenValid = Tools_System_Tools::validateToken($secureToken, self::DELIVERY_SECURE_TOKEN);
                if (!$tokenValid) {
                    $this->_responseHelper->fail('');
                }
                if ($form->isValid($this->_request->getParams())){
					$config = array(
						'name' => $name,
						'config' => $form->getValues()
					);
					Models_Mapper_ShippingConfigMapper::getInstance()->save($config);
				}
			}else {
				$pluginConfig = Models_Mapper_ShippingConfigMapper::getInstance()->find($name);
				if (isset($pluginConfig['config']) && !empty($pluginConfig['config'])){
					$form->populate($pluginConfig['config']);
				}
			}
            $form->setAction($this->_websiteUrl.'plugin/delivery/run/config/');
            $quantityWithoutWeightProducts = Models_Mapper_ProductMapper::getInstance()->countProductsWithoutWeight();
            $this->_view->quantityWithoutWeightProducts = $quantityWithoutWeightProducts;
			$this->_view->form = $form;
            echo $this->_view->render('settings.phtml');
        }
    }
    
    private function _prepareStoreAddress($storeConfig){
        if(!empty($storeConfig)){
            if(isset($storeConfig['state']) && $storeConfig['state'] != ''){
                $state = Tools_Geo::getStateById($storeConfig['state']);
                return $storeConfig['address1'].' '.$storeConfig['address2'].' '.$storeConfig['city'].' '.$state['state'].' '.$storeConfig['zip'].' '.$storeConfig['country']; 
            }
            return $storeConfig['address1'].' '.$storeConfig['address2'].' '.$storeConfig['city'].' '.$storeConfig['zip'].' '.$storeConfig['country'];
        }
        return '';
    }
    
    public function calculateAction(){
        if (sizeof(Tools_ShoppingCart::getInstance()->getContent()) === 0) {
			return $this->_jsonHelper->direct(array(
                array('error' => 'Cart is empty')
            ));
		}
        $config = Models_Mapper_ShippingConfigMapper::getInstance()->find(strtolower(get_called_class()));
        if (!$config) {
			return $this->_jsonHelper->direct(array(
                array('error' => 'Delivery Error: plugin is not configured')
            ));
      	}
        $this->setConfig($config['config']);
        $this->setWeight(Tools_ShoppingCart::getInstance()->calculateCartWeight(), $this->_shoppingConfig['weightUnit']);
        $this->setOrigination(Tools_Misc::clenupAddress($this->_shoppingConfig));
		$this->setDestination(Tools_ShoppingCart::getAddressById(Tools_ShoppingCart::getInstance()->getAddressKey(Models_Model_Customer::ADDRESS_TYPE_SHIPPING)));
        try {
	        $data = $this->_calculateShipping();
			$data = $this->_storeRates($data);
            if(isset($data[0]['price'])){
                $resultArray = array();
                foreach($data as $value){
                    $resultArray[] = array (
                        'type'  => $value['type'],
                        'price' => $this->_view->currency($value['price']),
                        'descr' => $value['descr']
                    );

                }
                $data = $resultArray;
            }
		} catch(Exception $e) {
			$data = array(
				array('error' => $e->getMessage())
			);
			$this->_storeRates(null);
		}
		$this->_jsonHelper->direct($data);
    }
          
    public function setConfig($config) {
        if(is_array($config)){
            $this->_config = $config;
        }
    }
    
 	public function  __construct($options, $seotoasterData) {
		parent::__construct($options, $seotoasterData);
        $this->_view->setScriptPath(dirname(__FILE__) . '/system/views/');
        $this->_shoppingConfig = Models_Mapper_ShoppingConfig::getInstance()->getConfigParams();
    }
    
    public function setOrigination(array $address){
        $state = Tools_Geo::getStateById($address['state']);
        if(isset($state)){
            $address['state'] = $state['state'];
        }
        $this->_origination = $address;
    }
	
	public function setDestination(array $address){
        $state = Tools_Geo::getStateById($address['state']);
        if(isset($state)){
            $address['state'] = $state['state'];
        }
        $this->_destination = $address;
    }
    
    public function setWeight($weight, $unit = ''){
        switch  ($unit) {
            case 'lbs':
                if (is_array($weight)){
                    $this->_packageWeight = array(
                        'kg' => ceil($weight[0]/2.2)
                     );
                } elseif(is_float($weight) && $weight !='') {
                     $this->_packageWeight = array(
                        'kg' => ceil($weight/2.2)
                     );
                } 
            case 'kg': if (is_array($weight)){
                    $this->_packageWeight = array(
                        'kg' => $weight[0]
                     );
                } elseif(is_float($weight) && $weight !='') {
                     $this->_packageWeight = array(
                        'kg' => $weight
                     );
                } 
            break;
        }
    }
    
    
    private function _calculateShipping(){
        $destinationType = '';
        $deliveryServiceName = self::DELIVERY_SERVICE_NAME;
        if(isset($this->_config['servicename']) && $this->_config['servicename'] != ''){
            $deliveryServiceName = $this->_config['servicename'];
        }
        $serviceNotAviable = array(array(
                                'error' => 'No services available for your destination'
                             ));
        if(isset($this->_config['destination']) && $this->_config['destination'] != ''){
            $destinationType = $this->_config['destination'];
            if($destinationType == 'national'){
                if($this->_origination['country'] != $this->_destination['country']){
                    return $serviceNotAviable;
                }
            }
            if($destinationType == 'international'){
                if($this->_origination['country'] == $this->_destination['country']){
                    return $serviceNotAviable;
                }
            }
                 
            $distance = $this->_calculateDistance();
            if(!isset($distance['error'])){
                $distanceValue = $distance['rows'][0]['elements'][0]['distance']['value']/1000;
                $shippingDistancePrice = '';
                if(isset($this->_config) && isset($this->_config['diststart1']) && isset($this->_config['distend1']) && isset($this->_config['price1'])){
                    if($distanceValue >= $this->_config['diststart1'] && $distanceValue <=$this->_config['distend1']){
                        $shippingDistancePrice = $this->_config['price1'];
                    }
                }
                if(isset($this->_config) && isset($this->_config['diststart2']) && isset($this->_config['distend2']) && isset($this->_config['price2'])){
                    if($distanceValue >= $this->_config['diststart2'] && $distanceValue <=$this->_config['distend2']){
                        $shippingDistancePrice = $this->_config['price2'];
                    }
                }
                if(isset($this->_config) && isset($this->_config['diststart3']) && isset($this->_config['distend3']) && isset($this->_config['price3'])){
                    if($distanceValue >= $this->_config['diststart3'] && $distanceValue <=$this->_config['distend3']){
                        $shippingDistancePrice = $this->_config['price3'];
                    }
                }
                if(isset($this->_config) && isset($this->_config['specialdistance']) && isset($this->_config['specialprice'])){
                    if($distanceValue > $this->_config['specialdistance']){
                        $shippingDistancePrice = $distanceValue*$this->_config['specialprice'];
                    }
                }
                if($shippingDistancePrice != ''){
                    return array(array(
                        'type'  => $deliveryServiceName,
                        'price' => $shippingDistancePrice,
                        'descr' => ''
                    ));
                }else{
                    return $error = array(array(
                        'error' => 'Can\'t calculate distance for your destination'
                    ));
                }
            }else{
               return $error = array(array(
                    'error' => 'Can\'t calculate distance for your destination'
               ));
            }
        }
               
    }
    
    private function _calculateDistance(){
        $units = 'metric';
        if(isset($this->_config['units']) && $this->_config['units'] == 'miles'){
            $units = 'imperial';
        }
        $destinationAddressOrigin = '';
        $destinationAddressDestination = '';
        if(isset($this->_origination['address1'])){
            $destinationAddressOrigin = urlencode($this->_origination['address1']).',';
        }
        if(isset($this->_destination['address1'])){
           $destinationAddressDestination = urlencode($this->_destination['address1']).',';
        }
        $apiUrl = $this->_googleDistanceService.$destinationAddressOrigin.urlencode($this->_origination['zip']).','.urlencode($this->_origination['country']).'&destinations='.$destinationAddressDestination.urlencode($this->_destination['zip']).','.urlencode($this->_destination['country']).'&mode=driving&units='.urlencode($units).'&sensor=false';
        $destinationApiResult = $this->_apiCall($apiUrl);
        return $destinationApiResult;
    }
      
    private function _apiCall($url, $data = ''){
        $curl = curl_init();		
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $rawResult = curl_exec($curl);
        $result = json_decode($rawResult, true);
        curl_close($curl);
        if($result['rows'][0]['elements'][0]['status'] != "ZERO_RESULTS"){
            return $result;
        }else{
            return array('error'=>'1');
        }
        
    }
             
}
