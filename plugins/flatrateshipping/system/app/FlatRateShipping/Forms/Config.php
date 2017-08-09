<?php
/**
 * FlatRateShipping_Forms_Config.php
 * @author Pavel Kovalyov <pavlo.kovalyov@gmail.com>
 */

class FlatRateShipping_Forms_Config extends Zend_Form
{

    const COMPARE_BY_AMOUNT = 'amount';

    const COMPARE_BY_WEIGHT = 'weight';

    public function init()
    {

        $this->setDecorators(array('Form', 'FormElements'));
        $this->setElementDecorators(
            array(
                array('Label', array('class' => '')),
                'ViewHelper'
            )
        );

        $this->addElement(
            'text',
            'titleFlatRate',
            array(
                'label' => 'Custom title'
            )
        );

        $this->addElement('hash', 'secureToken', array(
                'ignore' => true,
                'timeout' => 1440
        ));

        $this->addElement(
            'select',
            'unitsFlatRate',
            array(
                'label' => 'Units',
                'value' => 'amount',
                'multiOptions' => array(
                    self::COMPARE_BY_AMOUNT => 'total amount',
                    self::COMPARE_BY_WEIGHT => 'order weight'
                )
            )
        );

    }
}
