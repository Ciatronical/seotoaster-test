<?php
/**
 * Configuration
 * @author: iamne <eugene@seotoaster.com> Seotoaster core team
 * Date: 7/17/12
 * Time: 3:39 PM
 */
class Newslog_Forms_Configuration extends Zend_Form {

    public function init() {

        $this->setMethod(Zend_Form::METHOD_POST)
            ->setDecorators(array(
                'FormElements',
                'Form'
            )
        );

        $this->addElement(new Zend_Form_Element_Text(array(
            'name'     => 'folder',
            'label'    => 'News folder',
			'class'  => 'grid_8 alpha omega',
            'filters'  => array('StringTrim')
        )));

        $this->addElement(new Zend_Form_Element_Button(array(
            'name'   => 'saveConfig',
            'label'  => 'Apply changes',
            'class'  => 'btn ticon-checkmark-3 grid_4 alpha omega mt0px',
            'ignore' => 'true',
            'type'  => 'submit'
        )));

        $this->setElementDecorators(array('ViewHelper'));
    }

}
