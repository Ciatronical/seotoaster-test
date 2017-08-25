<?php
/**
 * Configuration
 * @author: <andrei.m@seotoaster.com> Seotoaster core team
 * Date: 24.04.2014
 *
 */
class Pagerating_Forms_Configuration extends Zend_Form {
    public function init() {

        $this->setMethod(Zend_Form::METHOD_POST)
            ->setDecorators(array(
                    'FormElements',
                    'Form'
                )
            );

        $this->addElement('checkbox', 'reviewNoCaptcha', array(
                'name'   => 'reviewNoCaptcha',
                'label' => 'Don\'t use captcha in review?'
            ));

        $this->addElement(new Zend_Form_Element_Button(array(
                'name'   => 'saveConfig',
                'label'  => 'Apply changes',
                'ignore' => 'true',
                'type'   => 'submit',
                'class'  => 'btn ticon-checkmark-3',
            )));

        $this->setElementDecorators(array('ViewHelper'));
    }
}