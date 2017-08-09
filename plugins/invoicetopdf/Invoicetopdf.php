<?php

/**
 * Class Invoicetopdf Plugin Invoicetopdf used for making invoices in pdf format for the shopping plugin.
 */
class Invoicetopdf extends Tools_Plugins_Abstract
{

    /**
     * store management resource
     */
    const RESOURCE_STORE_MANAGEMENT = 'storemanagement';

    /**
     * Role customer
     */
    const ROLE_CUSTOMER = 'customer';

    /**
     * secure token namespace
     */
    const INVOICETOPDF_SECURE_TOKEN = 'InvoicetopdfToken';

    protected $_pdfPath;

    /**
     * init
     */
    protected function _init()
    {
        $this->_view->setScriptPath(__DIR__ . '/system/views/');
    }

    /**
     * Main entry point
     *
     * @param array $requestedParams
     * @return mixed $dispatcherResult
     */
    public function run($requestedParams = array())
    {
        $dispatcherResult = parent::run($requestedParams);
        return ($dispatcherResult) ? $dispatcherResult : '';
    }

    /**
     * @param array $options
     * @param array $seotoasterData
     */
    public function  __construct($options, $seotoasterData)
    {
        parent::__construct($options, $seotoasterData);
        $this->_view->setScriptPath(dirname(__FILE__) . '/system/views/');
        $this->_websiteConfig = Zend_Registry::get('website');
        $this->_websiteHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('website');
        $this->_configHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('config');
        $this->_cartStorage = Tools_ShoppingCart::getInstance();
        $this->_pdfPath = $this->_websiteConfig['path'] .'plugins' . DIRECTORY_SEPARATOR . 'invoicetopdf' . DIRECTORY_SEPARATOR . 'invoices' . DIRECTORY_SEPARATOR;
        $invoiceFiles = glob($this->_pdfPath .'Invoice_*');
        $packingFiles = glob($this->_pdfPath .'packing_slip_*');
        $files = array_merge($invoiceFiles, $packingFiles);
        foreach($files as $file){
            if(is_file($file))
                unlink($file);
        }

    }

    /**
     * Create invoice in pdf format
     */
    public function createPdfInvoiceAction()
    {
        $data = $this->_request->getParams();
        $fileInfo = $this->_prepareInvoice($data);
        if (!empty($fileInfo)) {
            if ($data['dwn'] == 1) {
                header("Content-Description: File Transfer");
                header("Content-Disposition: attachment; filename=$fileInfo[fileName]");
                header("Content-type: application/pdf");
                readfile($fileInfo['folder'] . $fileInfo['fileName']);
            }
            if ($data['dwn'] == 0) {
                $this->_redirector->gotoUrl($this->_websiteHelper->getUrl() . 'plugins/invoicetopdf/invoices/' . $fileInfo['fileName']);
            }
        }
    }
    /**
     * Send invoice to user as pdf attachment via email
     */
    public function sendInvoiceToUserAction()
    {
        $emailTrigger = Application_Model_Mappers_EmailTriggersMapper::getInstance()->findByTriggerName(Tools_InvoicetopdfMailWatchdog::TRIGGER_SEND_INVOICE)->toArray();
        if(empty($emailTrigger[0])) {
            $this->_responseHelper->fail($this->_translator->translate('The invoice was not sent. Please create action email first'));
        }
        $data = $this->_request->getParams();
        $fileInfo = $this->_prepareInvoice($data);
        if (!empty($fileInfo)) {
            // sending mails
            $cartSession = $fileInfo['cartSession'];
            $cartSession->registerObserver(new Tools_Mail_Watchdog(array(
                'trigger' => Tools_InvoicetopdfMailWatchdog::TRIGGER_SEND_INVOICE,
                'attachmentUrl' => $this->_websiteHelper->getUrl() . 'plugins/invoicetopdf/invoices/' . $fileInfo['fileName'],
                'attachmentPath' => $this->_pdfPath . $fileInfo['fileName'],
                'attachmentName' => $fileInfo['fileName']
            )));
            $cartSession->notifyObservers();
            $this->_responseHelper->success();
        }
    }

    /**
     * Return shipping or billing information about purchase
     * by shipping or billing keys
     *  lastname -> last name
     *  firstname -> first name
     *  address1 -> address
     *  address2 -> address
     *  city    ->  city
     *  state   -> state
     *  zip -> zip
     *  country -> country
     *  phone -> phone
     *  mobile -> mobile
     *  email -> email
     *
     * @return mixed
     */
    public function _makeOptionCustomer()
    {
        if (isset($this->_sessionHelper->storeCartSessionKey)) {
            $customerShipping = $this->_sessionHelper->customerShippingInvoice;
            $customerBilling = $this->_sessionHelper->customerBillingInvoice;
            if (isset($this->_options[1]) && isset($this->_options[2])) {
                if ($this->_options[1] === Models_Model_Customer::ADDRESS_TYPE_BILLING) {
                    return $customerBilling[0][$this->_options[2]];
                }
                if ($this->_options[1] === Models_Model_Customer::ADDRESS_TYPE_SHIPPING) {
                    return $customerShipping[0][$this->_options[2]];
                }
            }
        }
    }

    /**
     * Date of creation invoice
     *
     * @return string
     */
    public function _makeOptionInvoiceDate()
    {
        return date("d-M-Y", strtotime("now"));
    }

    /**
     * Return Cart id
     *
     * @return int
     */
    public function _makeOptionInvoiceNumber()
    {
        if (isset($this->_sessionHelper->storeCartSessionKey)) {
            return $this->_sessionHelper->storeCartSessionKey;
        }
    }

    /**
     * Date when purchase was done
     *
     * @return string
     */
    public function _makeOptionCreated()
    {
        $currentUser = $this->_sessionHelper->getCurrentUser()->getRoleId();
        if (isset($this->_sessionHelper->storeCartSessionKey)) {
            $cartId = $this->_sessionHelper->storeCartSessionKey;
            if (Tools_Security_Acl::isAllowed(self::RESOURCE_STORE_MANAGEMENT) || $currentUser == self::ROLE_CUSTOMER) {
                $cartSession = Models_Mapper_CartSessionMapper::getInstance()->find($cartId);
                if (!empty($cartSession)) {
                    return date("d-M-Y", strtotime($cartSession->getCreatedAt()));
                }
            }
        }
    }

    /**
     * save config action
     */
    public function configAction()
    {
        if (Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_PLUGINS)) {
            $invoicetopdfSettingsMapper = Invoicetopdf_Models_Mapper_InvoicetopdfSettingsMapper::getInstance();
            if ($this->_request->isPost()) {
                $secureToken = $this->_request->getParam(Tools_System_Tools::CSRF_SECURE_TOKEN, false);
                $tokenValid = Tools_System_Tools::validateToken($secureToken, self::INVOICETOPDF_SECURE_TOKEN);
                if (!$tokenValid) {
                    exit;
                }
                $configParams = $this->_request->getParams();
                $invoicetopdfSettingsMapper->save($configParams);
                $this->_responseHelper->success('');
            } else {
                $invoiceTemplates = Application_Model_Mappers_TemplateMapper::getInstance()->findByType('typeinvoice');
                $invoicetopdfSettings = $invoicetopdfSettingsMapper->getConfigParams();
                $this->_view->settings = $invoicetopdfSettings;
                $this->_view->invoiceTemplates = $invoiceTemplates;
                $this->_view->translator = $this->_translator;
                echo $this->_view->render('invoiceConfig.phtml');
            }

        }
    }

    /**
     * Adding invoice to pdf under shopping configuration tab
     *
     * @return array
     */
    public static function getEcommerceConfigTab()
    {
        $translator = Zend_Controller_Action_HelperBroker::getStaticHelper('language');
        return array(
            'title' => $translator->translate('Documents'),
            'contentUrl' => Zend_Controller_Action_HelperBroker::getStaticHelper('website')->getUrl(
            ) . 'plugin/invoicetopdf/run/config/'
        );
    }

    /**
     * Return country full name
     *
     * @param string $country country code
     * @return mixed
     */
    private function _prepareCountry($country)
    {
        if (!empty($country)) {
            $countries = Tools_Geo::getCountries(true);
            $country = $countries[$country];
        }
        return $country;
    }

    protected function _prepareInvoice($data)
    {
        $currentUser = $this->_sessionHelper->getCurrentUser()->getRoleId();
        $userId = $this->_sessionHelper->getCurrentUser()->getId();
        if (Tools_Security_Acl::isAllowed(self::RESOURCE_STORE_MANAGEMENT) || $currentUser == self::ROLE_CUSTOMER) {
            $invoicetopdfSettings = Invoicetopdf_Models_Mapper_InvoicetopdfSettingsMapper::getInstance()->getConfigParams();
            if (isset($data['cartId']) && isset($invoicetopdfSettings['invoiceTemplate']) && isset($data['dwn'])) {
                $cartId = $data['cartId'];
                $templateTable = new Application_Model_DbTable_Template;
                if (isset($data['packing'])) {
                    $where = $templateTable->getAdapter()->quoteInto(
                        'name = ?',
                        $invoicetopdfSettings['packingTemplate']
                    );
                } else {
                    $where = $templateTable->getAdapter()->quoteInto(
                        'name = ?',
                        $invoicetopdfSettings['invoiceTemplate']
                    );
                }
                $invoiceTemplate = Application_Model_Mappers_TemplateMapper::getInstance()->fetchAll($where);
                $templateContent = $invoiceTemplate[0]->getContent();
                $cartSession = Models_Mapper_CartSessionMapper::getInstance()->find($cartId);
                if (!empty($cartSession)) {
                    if ($currentUser == self::ROLE_CUSTOMER && $cartSession->getUserId() != $userId) {
                        $this->_redirector->gotoUrl($this->_websiteHelper->getUrl());
                    }
                    $this->_sessionHelper->storeCartSessionKey = $cartId;
                    $this->_sessionHelper->storeCartSessionConversionKey = $cartId;
                    $customerDbTable = new Quote_Models_DbTable_ShoppingCustomerAddress();
                    $whereBilling = $customerDbTable->getAdapter()->quoteInto(
                        'id = ?',
                        $cartSession->getBillingAddressId()
                    );
                    $whereShipping = $customerDbTable->getAdapter()->quoteInto(
                        'id = ?',
                        $cartSession->getShippingAddressId()
                    );
                    $customerShipping = $customerDbTable->getAdapter()->fetchAll(
                        $customerDbTable->select()->from('shopping_customer_address')->where($whereShipping)
                    );
                    $customerBilling = $customerDbTable->getAdapter()->fetchAll(
                        $customerDbTable->select()->from('shopping_customer_address')->where($whereBilling)
                    );
                    if (!empty($customerShipping)) {
                        $customerShipping[0]['country'] = $this->_prepareCountry($customerShipping[0]['country']);
                        $this->_sessionHelper->customerShippingInvoice = $customerShipping;
                    }
                    if (!empty($customerBilling)) {
                        $customerBilling[0]['country'] = $this->_prepareCountry($customerBilling[0]['country']);
                        $this->_sessionHelper->customerBillingInvoice = $customerBilling;
                    }

                    $themeData = Zend_Registry::get('theme');
                    $pageMapper = Application_Model_Mappers_PageMapper::getInstance();
                    $parserOptions = array(
                        'websiteUrl' => $this->_websiteHelper->getUrl(),
                        'websitePath' => $this->_websiteHelper->getPath(),
                        'currentTheme' => $this->_configHelper->getConfig('currentTheme'),
                        'themePath' => $themeData['path'],
                    );
                    $page = $pageMapper->findByUrl('index.html');
                    $page = $page->toArray();
                    $parser = new Tools_Content_Parser($templateContent, $page, $parserOptions);
                    $content = $parser->parse();
                    $this->_cartStorage->clean();

                    if (!defined('_MPDF_TEMP_PATH')) {
                        define('_MPDF_TEMP_PATH', $this->_pdfPath);
                    }
                    require_once(__DIR__ . '/system/library/mpdf/mpdf.php');

                    $pdfFile = new mPDF('utf-8', 'A4');
                    $pdfFile->WriteHTML($content);

                    if (isset($data['packing'])) {
                        $pdfFileName = 'packing_slip_' . md5($cartId . microtime()) . '.pdf';
                    } else {
                        $pdfFileName = 'Invoice_' . md5($cartId . microtime()) . '.pdf';
                    }
                    $pdfFile->Output($this->_pdfPath . $pdfFileName, 'F');

                    return array('fileName' => $pdfFileName, 'folder' => $this->_pdfPath, 'cartSession' => $cartSession);
                }
            }
        }
        return false;
    }
}

