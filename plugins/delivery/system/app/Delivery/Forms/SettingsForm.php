<?php


class Delivery_Forms_SettingsForm extends Zend_Form {

    const COMPARE_BY_KILOMETERS = 'kilometers';

	const COMPARE_BY_MILES = 'miles';
    
    const DESTINATION_NATIONAL = 'national';
    
    const DESTINATION_INTERNATIONAL = 'international';
    
    const DESTINATION_BOTH = 'both';
    
	public function init(){
		$this->setElementDecorators(array(
			'ViewHelper',
			array('Label', array('class' => 'mt10px')),
		));

        
        $this->addElement('select', 'destination', array(
			'label' => 'Delivery to:',
			'value' => 'national',
			'multiOptions' => array(
				self::DESTINATION_NATIONAL      => 'national',
				self::DESTINATION_INTERNATIONAL => 'international',
                self::DESTINATION_BOTH          => 'both'
			)
		));
        
        $this->addElement('select', 'units', array(
			'label' => 'Units:',
			'value' => 'kilometers',
			'multiOptions' => array(
				self::COMPARE_BY_KILOMETERS => 'kilometers',
				self::COMPARE_BY_MILES      => 'miles'
			)
		));
        
        $this->addElement('text', 'servicename', array(
			'label'     => 'Delivery Service name:',
            'value'     => Delivery::DELIVERY_SERVICE_NAME
		));
                
		//start distance
		$this->addElement('text', 'diststart1', array(
			'label'     => 'From:'
		));
		$this->addElement('text', 'diststart2', array(
			'label'     => 'From:'
		));
		$this->addElement('text', 'diststart3', array(
			'label'     => 'From:'
		));
        
        //end distance
		$this->addElement('text', 'distend1', array(
			'label'     => 'to:'
		));
		$this->addElement('text', 'distend2', array(
			'label'     => 'to:'
		));
		$this->addElement('text', 'distend3', array(
			'label'     => 'to:'
		));
        
        //price
		$this->addElement('text', 'price1', array(
			'label'     => 'Price'
		));
		$this->addElement('text', 'price2', array(
			'label'     => 'Price'
		));
		$this->addElement('text', 'price3', array(
			'label'     => 'Price'
		));
        
        $this->addElement('text', 'specialdistance', array(
			'label'     => 'Over this distance:'
		));
        
        $this->addElement('text', 'specialprice', array(
			'label'     => 'Price per mile:'
		));
        
        
        				
	}
}
