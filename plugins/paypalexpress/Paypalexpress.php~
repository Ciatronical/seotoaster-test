<?php

include "Debug.php"; 

class Paypalexpress extends Tools_Plugins_Abstract{

	 protected function _init() {
     
     $this->_layout = new Zend_Layout();
     $this->_layout->setLayoutPath(__DIR__ . '/system/views/');
     //$this->_layout->setLayoutPath(Zend_Layout::getMvcInstance()->getLayoutPath());
     
     $this->_cartStorage = Tools_ShoppingCart::getInstance();
		$this->_productMapper = Models_Mapper_ProductMapper::getInstance();
		$this->_shoppingConfig = Models_Mapper_ShoppingConfig::getInstance()->getConfigParams();
		$this->_sessionHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('session');
		$this->_jsonHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('json');
		$this->_view->weightSign = isset($this->_shoppingConfig['weightUnit']) ? $this->_shoppingConfig['weightUnit'] : 'kg';
		
		

		
    //Überträgt den Preis der Bestellung zum Paypalplugin  	
	$type = $this->_request->getParam('type');
		if (isset($type) && $type == 'json') {
			$summary = $this->_cartStorage->calculate();
			if (Zend_Registry::isRegistered('Zend_Currency')) {
				$currency = Zend_Registry::get('Zend_Currency');
				

				
				return array('subTotal' => $currency->toCurrency($summary['subTotal']), 'totalTax' => $currency->toCurrency($summary['totalTax']),
				             'shipping' => $summary['shipping'], 'total' => $currency->toCurrency($summary['total']));
			}
			return $this->_cartStorage->calculate();
		}
		$this->_view->summary = $this->_cartStorage->calculate();
		$this->_view->taxIncPrice = (bool)$this->_shoppingConfig['showPriceIncTax'];
		
		$this->_view->returnAllowed = $this->_checkoutSession->returnAllowed;
		$this->_view->setScriptPath(dirname(__FILE__) . '/system/views/');
    }



public function settingsAction() {

	if (Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_PLUGINS)){
	  	
	  	$paypalConfigMapper = Paypalexpress_Models_Mapper_PaypalExpressSettingsMapper::getInstance();
      $paypalModelConfig  = new Paypalexpress_Models_Models_PaypalExpressSettingsModel();
	  	writeLog($paypalModelConfig);
	  	if($this->_request->isPost()){ 
               
					
      $prodID = $this->_request->getParam('prodID');
      $sandID = $this->_request->getParam('sandID');
      $useSandBox = $this->_request->getParam('useSandBox');
      
      $paypalModelConfig->setProdID($prodID);
	   $paypalModelConfig->setSandID($sandID);
	   $paypalModelConfig->setUseSandbox($useSandBox);
	    
	   $paypalConfigMapper->save($paypalModelConfig);
	   $this->_responseHelper->success('');
	   
       }else {
	  	
	  	
	  	$paypalSettings = $paypalConfigMapper->selectSettings();
       $this->_view->prodID = $paypalSettings[0]->getProdID();
       $this->_view->sandID = $paypalSettings[0]->getSandID();
       $this->_view->useSandBox = $paypalSettings[0]->getUseSandbox();
	  	 $this->_view->translator = $this->_translator;
	  	 $this->_layout->content = $this->_view->render('settings.phtml');
       echo $this->_layout->render();
	  
	  }

	
}
}

   
 //Paypal Express   
//Einbinden des Buttons in das Template mit {$store:paypalbutton}
public function _makeOptionPaypalbutton() {
     	
     	//Integration der Paypalkundendaten in die Datenbank 
		//wird nachdem Zahlungsvorgang ausgeführt	
			if(isset($_GET["firstname"])){		//überprüft ob ein Wert in der Url vorhanden ist
					
					//Kundendaten
					$firstname=$_GET["firstname"];
					$lastname=$_GET["lastname"];
					$email=$_GET["email"];
					
					
				//KundenAdresse	
				$city=$_GET["city"];
				$street=$_GET["street"];
				$postalCode=$_GET["postalCode"];				
				
				
				$cart = Tools_ShoppingCart::getInstance();// die aktuelle Bestellung
				
				
				
				//Vorbereiten des KundenArray
				$customerData = array(
   			 "firstname"    => $firstname,
   			 "lastname"  => $lastname,
   			 "email"  => $email,
   			 "mobilecountrycode" => "DE",
   			 "mobile" => "",
					);				
				$addressType = Models_Model_Customer::ADDRESS_TYPE_BILLING; //Adresstyp
				
			
				$this->_checkoutSession->initialCustomerInfo = $customerData;// die aktuelle Session
				$customer = Shopping::processCustomer($customerData);	//übertragen des Kundenarrays in die Datenbank
				
								
					
				if ($customer->getId()) {
                    $customer->setAttribute('mobilecountrycode', $customerData['mobilecountrycode']);
                    Application_Model_Mappers_UserMapper::getInstance()->saveUserAttributes($customer); //Speichert die Kundendaten
            
            $address =array("firstname" => $firstname, "lastname" => $lastname,"company"=>"", "email"=>$email, "address1"=>$street,"address2"=>"","country"=>"DE","city"=>$city, "state"=>"", "zip"=>$postalCode, "phone"=>"","notes"=>"","mobilecountrycode"=>"DE", "mobile"=>"");
				$addressId = Models_Mapper_CustomerMapper::getInstance()->addAddress($customer, $address, $addressType); //Speichern der KundenAdresse in die Datenbank
				$cart->setShippingAddressKey($addressId); //Setzen der 
				$cart->setBillingAddressKey($addressId);
				
				$cart->setCustomerId($customer->getId())->calculate(true);
				$cart->save()->saveCartSession($customer); //Speichern der Bestellung mit Kundendaten
				
				//Setzt den Status der Bestellung auf Bezahlt
				$orderId=$cart->getCartId();	
				
				$orderModel = Models_Mapper_CartSessionMapper::getInstance()->find($orderId);
				
				$orderModel->setStatus(Models_Model_CartSession::CART_STATUS_COMPLETED);
				$result = Models_Mapper_CartSessionMapper::getInstance()->save($orderModel);				
				
					
					
				//Thank you page-> Zusammenfassung für den Kunden	
				$this->_redirector->gotoUrl($this->_websiteUrl.'plugin/shopping/run/thankyou/');
            

				}
		
     	
     	}
  
  
  $paypalConfigMapper = Paypalexpress_Models_Mapper_PaypalExpressSettingsMapper::getInstance();


  $paypalSettings = $paypalConfigMapper->selectSettings();
  
  $prodID = $paypalSettings[0]->getProdID();
  $sandID= $paypalSettings[0]->getSandID();
  $useSandBox = $paypalSettings[0]->getUseSandbox();
  
 //Überträgt den Preis der Bestellung zum Paypalplugin  	
	$type = $this->_request->getParam('type');
		if (isset($type) && $type == 'json') {
			$summary = $this->_cartStorage->calculate();
			if (Zend_Registry::isRegistered('Zend_Currency')) {
				$currency = Zend_Registry::get('Zend_Currency');
				

				
				return array('subTotal' => $currency->toCurrency($summary['subTotal']), 'totalTax' => $currency->toCurrency($summary['totalTax']),
				             'shipping' => $summary['shipping'], 'total' => $currency->toCurrency($summary['total']));
			}
			return $this->_cartStorage->calculate();
		}
		
		return $this->_view->render('paypalbutton.phtml'); //Zeigt den Button an
	} 
    
  


}