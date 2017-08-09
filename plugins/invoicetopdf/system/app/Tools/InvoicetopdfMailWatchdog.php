<?php

/**
 * Created by PhpStorm.
 * User: Vitaly
 * Date: 1/28/2016
 * Time: 13:00
 */
class Tools_InvoicetopdfMailWatchdog implements Interfaces_Observer
{

    const TRIGGER_SEND_INVOICE = 'invoice_send';

    const RECIPIENT_CUSTOMER = 'customer';

    private $_options;

    /**
     * @var Tools_Mail_Mailer instance of mailer
     */
    private $_mailer;

    /**
     * @var Helpers_Action_Config
     */
    private $_configHelper;

    /**
     * @var Helpers_Action_Website
     */
    private $_websiteHelper;

    private $_storeConfig;


    /**
     * @var Instance of watched object
     */
    private $_object;

    public function __construct($options = array())
    {
        $this->_storeConfig = Models_Mapper_ShoppingConfig::getInstance()->getConfigParams();
        $this->_configHelper = Zend_Controller_Action_HelperBroker::getExistingHelper('config');
        $this->_websiteHelper = Zend_Controller_Action_HelperBroker::getExistingHelper('website');
        $this->_options = $options;
        $this->_initMailer();
        $this->_entityParser = new Tools_Content_EntityParser();
    }

    private function _initMailer()
    {
        $config = $this->_configHelper->getConfig();
        $this->_mailer = new Tools_Mail_Mailer();

        if ((bool)$config['useSmtp']) {
            $smtpConfig = array(
                'host' => $config['smtpHost'],
                'username' => $config['smtpLogin'],
                'password' => $config['smtpPassword']
            );
            if ((bool)$config['smtpSsl']) {
                $smtpConfig['ssl'] = $config['smtpSsl'];
            }
            if (!empty($config['smtpPort'])) {
                $smtpConfig['port'] = $config['smtpPort'];
            }
            $this->_mailer->setSmtpConfig($smtpConfig);
            $this->_mailer->setTransport(Tools_Mail_Mailer::MAIL_TYPE_SMTP);
        } else {
            $this->_mailer->setTransport(Tools_Mail_Mailer::MAIL_TYPE_MAIL);
        }
    }

    public function notify($object)
    {
        if (!$object || $this->_options['service'] !== Application_Model_Models_TriggerAction::SERVICE_TYPE_EMAIL) {
            return false;
        }

        $this->_object = $object;

        if (isset($this->_options['template']) && !empty($this->_options['template'])) {
            $this->_template = $this->_preparseEmailTemplate();
        } else {
            throw new Exceptions_SeotoasterException('Missing template for action email trigger');
        }

        $this->_subject = $this->_options['subject'];
        $this->_mailer->setMailFromLabel($this->_storeConfig['company']);

        if (!empty($this->_options['from'])) {
            $this->_mailer->setMailFrom($this->_options['from']);
        } elseif (!empty($this->_storeConfig['email'])) {
            $this->_mailer->setMailFrom($this->_storeConfig['email']);
        } else {
            $this->_mailer->setMailFrom($this->_configHelper->getAdminEmail());
        }

        if (isset($this->_options['trigger'])) {
            $methodName = str_replace('_send', '', $this->_options['trigger']);
            $methodName = '_send' . ucfirst(strtolower(preg_replace('/\s*/', '', $methodName))) . 'Mail';
            if (method_exists($this, $methodName)) {
                $this->$methodName();
            }
        }
    }

    protected function _send()
    {
        if (!$this->_mailer->getMailFrom() || !$this->_mailer->getMailTo()) {
            throw new Exceptions_SeotoasterException('Missing required "from" and "to" fields');
        }

        $this->_mailer->setSubject($this->_entityParser->parse($this->_subject));
        $this->_mailer->setBody($this->_entityParser->parse($this->_template));

        return ($this->_mailer->send() !== false);
    }


    private function _preparseEmailTemplate()
    {
        $tmplName = $this->_options['template'];
        $tmplMessage = $this->_options['message'];
        $mailTemplate = Application_Model_Mappers_TemplateMapper::getInstance()->find($tmplName);

        if (!empty($mailTemplate)) {
            $this->_entityParser->setDictionary(array(
                'emailmessage' => !empty($tmplMessage) ? $tmplMessage : ''
            ));
            //pushing message template to email template and cleaning dictionary
            $mailTemplate = $this->_entityParser->parse($mailTemplate->getContent());
            $this->_entityParser->setDictionary(array());

            $mailTemplate = $this->_entityParser->parse($mailTemplate);

            $themeData = Zend_Registry::get('theme');
            $extConfig = Zend_Registry::get('extConfig');
            $parserOptions = array(
                'websiteUrl' => $this->_websiteHelper->getUrl(),
                'websitePath' => $this->_websiteHelper->getPath(),
                'currentTheme' => $extConfig['currentTheme'],
                'themePath' => $themeData['path'],
            );
            $parser = new Tools_Content_Parser($mailTemplate, Tools_Misc::getCheckoutPage()->toArray(), $parserOptions);

            return Tools_Content_Tools::stripEditLinks($parser->parseSimple());
        }

        return false;
    }


    /**
     * Send email when user change account info
     *
     * @return bool
     * @throws Exceptions_SeotoasterException
     */
    private function _sendInvoiceMail()
    {
        $attachment = new Zend_Mime_Part(file_get_contents($this->_options['attachmentPath']));
        $attachment->type        = 'application/pdf';
        $attachment->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
        $attachment->encoding    = Zend_Mime::ENCODING_BASE64;
        $attachment->filename    = $this->_options['attachmentName'];
        $customer = Models_Mapper_CustomerMapper::getInstance()->find($this->_object->getUserId());
        $this->_mailer->addAttachment($attachment);
        $this->_mailer->setMailToLabel($customer->getFullName())
            ->setMailTo($customer->getEmail());
        $this->_entityParser
            ->objectToDictionary($customer);

        return $this->_send();
    }


}