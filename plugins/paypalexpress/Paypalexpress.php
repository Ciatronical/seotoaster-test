<?php

class Paypalexpress extends Tools_Plugins_Abstract{

	 protected function _init() {
     
        $this->_view->setScriptPath(__DIR__ . '/system/views/');
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
				
				$this->_checkoutSession->returnAllowed = array(
					self::STEP_LANDING 
				);
				
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
		$this->_getCheckoutPage();
		$this->_view->returnAllowed = $this->_checkoutSession->returnAllowed;
		return $this->_view->render('paypalbutton.phtml'); //Zeigt den Button an
	} 
    
  


}