<?php

/**
 * Review Form for PageRating plugin
 * @author Pavel Kovalyov <pavlo.kovalyov@gmail.com>
 */
class Pagerating_Forms_Review extends Zend_Form
{

    public function init()
    {
        $this->setAttrib('id', 'pagerating-post-review')
            ->setDecorators(
                array(
                    'FormElements',
                    'Form'
                )
            )
            ->setElementDecorators(
                array(
                    'ViewHelper',
                    'Label',
                    new Zend_Form_Decorator_HtmlTag(array('tag' => 'p', 'class' => 'formreview-element'))
                )
            )
            ->setElementFilters(array('StripTags', 'StringTrim'))
            ->setMethod(Zend_Form::METHOD_POST);

        $this->addElement(
            'text',
            'name',
            array(
                'label' => 'Topic',
                'class' => 'reviewform-topic'
            )
        );

        $currentUser = Zend_Controller_Action_HelperBroker::getExistingHelper('session')->getCurrentUser();
        $this->addElement(
            'text',
            'author',
            array(
                'label' => 'Author',
                'class' => 'reviewform-author',
                'value' => $currentUser->getFullName()
            )
        );

        $this->addElement(
            'text',
            'email',
            array(
                'label'      => 'Email',
                'class'      => 'reviewform-email',
                'value'      => $currentUser->getEmail(),
                'validators' => array('EmailAddress'),
                'required'   => true
            )
        );

        $this->addElement(
            'textarea',
            'description',
            array(
                'label' => 'Review',
                'rows'  => 5
            )
        );

        if (!Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_ADMINPANEL)) {
            $websiteConfig = Zend_Controller_Action_HelperBroker::getStaticHelper('config')->getConfig();
            $request = Zend_Controller_Front::getInstance()->getRequest();
            $params = null;
            if ($request->isSecure()) {
                $params = array(
                    'ssl'   => true,
                    'error' => null,
                    'xhtml' => false
                );
            }
            $recaptchaService = new Zend_Service_ReCaptcha($websiteConfig[Tools_System_Tools::RECAPTCHA_PUBLIC_KEY], $websiteConfig[Tools_System_Tools::RECAPTCHA_PRIVATE_KEY], $params);
            $captcha = new Zend_Form_Element_Captcha('captcha', array(
                'captcha'                      => 'ReCaptcha',
                'captchaOptions'               => array(
                    'captcha' => 'ReCaptcha',
                    'service' => $recaptchaService,
                    'theme'   => 'custom'
                ),
                'disableLoadDefaultDecorators' => true,
                'decorators'                   => array(
                    'Captcha_ReCaptcha',
                    array(
                        'ViewScript',
                        array(
                            'viewScript' => 'backend/form/recaptcha.phtml',
                            'placement'  => false,
                        ),
                    ),
                )
            ));

            $this->addElement($captcha);
        }

        $this->addElement(
            'hidden',
            'ratingValue',
            array(
                'value' => 0
            )
        );

        $this->addElement(
            'hidden',
            'pageId',
            array(
                'required' => true
            )
        );

        $this->addElement(
            'submit',
            'starratingpostreview',
            array(
                'label'      => 'Post review',
                'class'      => 'reviewform-btn',
                'decorators' => array('ViewHelper')
            )
        );

    }
}
