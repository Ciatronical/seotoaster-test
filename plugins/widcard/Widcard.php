<?php
/**
 * Description of Widcard
 */
set_include_path(implode(PATH_SEPARATOR, array(
	dirname(__FILE__) . '/system/classes/dbtables',
	dirname(__FILE__) . '/system/classes/mappers',
	dirname(__FILE__) . '/system/classes/models',
    get_include_path()
)));

class Widcard extends Tools_Plugins_Abstract {

	const REGISTER_URL = 'https://mojo.seosamba.com/plugin/signup/run/showRegisterForm/landing/toaster'; ///agency/13

    const WIDCARD_SECURE_TOKEN = 'WidcardToken';

	private $_notag = null;
	private $_href = null;
	private $src = null;

    const SECTION_WIDCARD = 'widcard';

	protected function _init() {

        $this->_layout = new Zend_Layout();
        $this->_layout->setLayoutPath(Zend_Layout::getMvcInstance()->getLayoutPath());

        if(($scriptPaths = Zend_Layout::getMvcInstance()->getView()->getScriptPaths()) !== false) {
            $this->_view->setScriptPath($scriptPaths);
        }
        $this->_view->addScriptPath(__DIR__ . '/views/');
	}

	public function run($requestedParams = array()) {
		$runnedPlugin = Zend_Registry::isRegistered('runnedWic') ? Zend_Registry::get('runnedWic') : array();
		if( isset($runnedPlugin[__CLASS__])) {
			$runned = $runnedPlugin[__CLASS__];
			$this->_view->noScript = $runnedPlugin[__CLASS__];
		}
		else {
			$runned = true;
			$runnedPlugin[__CLASS__] = $runned;
			Zend_Registry::set('runnedWic', $runnedPlugin);
		}
		$this->_notag = (isset ($this->_options['1']) && ($this->_options['1'] == 'notag') ) ? true : null;
		$this->_href = (isset ($this->_options['1']) && ($this->_options['1'] == 'href') ) ? true : null;
		$this->_requestedParams = $requestedParams;
		$dispatchersResult      = parent::run($requestedParams);
		if($dispatchersResult) {
			return $dispatchersResult;
		}
	}

    public function beforeController()
    {
        if (Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_ADMINPANEL)) {
            $config = Application_Model_Mappers_ConfigMapper::getInstance()->getConfig();
            if (isset($config['whiteLabelLogo'])) {
                Zend_Layout::getMvcInstance()->getView()->placeholder('logoSource')->set($config['whiteLabelLogo']);
            }
        }
    }

	private function _renderWicOption($optionName) {
		$value = null;
		$configHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('config');
		$configHelper->init();
        $loggedUserRole = $this->_sessionHelper->getCurrentUser()->getRoleId();
		$config = $configHelper->getConfig();
			if(isset($config[$optionName]) && ($config[$optionName] != '')) {
				$value = $config[$optionName];
			}
			else {
                $value = '';
                if ($loggedUserRole === Tools_Security_Acl::ROLE_SUPERADMIN) {
                    $value = $this->_translator->translate('Add...');
                }
				$this->_view->default = true;
			}
            if ($optionName === 'waCode') {
                $analyticsParams = unserialize($config['wicAnalyticsType']);
                if (is_array($analyticsParams) && in_array('WA', $analyticsParams)) {
                    return $config['waCode'];
                }
            }
			if($optionName == 'wicOrganizationCountry') {
				if(isset($config['wicOrganizationCountry']) && ($config['wicOrganizationCountry'] != -1 ) ) {
					$locale = Zend_Controller_Action_HelperBroker::getStaticHelper('session');
					$countryList = Zend_Locale::getTranslationList('territory', $locale->locale, 2);
					$value = $countryList[$value];
				}
				else {$value = $this->_translator->translate('');}
				$this->_view->locationBlock = true;
			}
			if($optionName == 'wicCountryState') {
				if(isset($config['wicCountryState']) && ($config['wicOrganizationCountry'] == ('US' || 'CA' || 'AU') ) ) {
					if($config['wicOrganizationCountry'] == 'US'){
						$value = self::$stateListUSA[$config['wicCountryState']];
					}
					elseif($config['wicOrganizationCountry'] == 'CA') {
						$value = self::$stateListCanada[$config['wicCountryState']];
					}
                    elseif($config['wicOrganizationCountry'] == 'AU') {
                        $value = self::$stateListAustralia[$config['wicCountryState']];
                    }
					else {$value = $this->_translator->translate('');}
					$this->_view->locationBlock = true;
				}
			}
			if($optionName == 'wicCorporateLogo') {
				$this->_view->locationBlock = true;
				$this->_view->image = true;
				$this->_src = (isset ($this->_options['1']) && ($this->_options['1'] == 'url') ) ? true : null;
				$this->_view->src = $this->_src;
				if(isset($config['wicCorporateLogo']) && ($config['wicCorporateLogo'] != '')) {
					if($this->_src) {
						$value = $this->_websiteUrl.$config['wicCorporateLogo'];
					}
					else {
						$value = '<img src="'.$this->_websiteUrl.$config['wicCorporateLogo'].'" class="inlineLogo" alt="'.$this->_translator->translate('Corporate logo').'" />';
					}
				}
				else {
					if($this->_src) {
						$value = $this->_websiteUrl.'system/images/noimage.png';
					}
					else
					{
						$value = '<img src="'.$this->_websiteUrl.'system/images/noimage.png'.'" class="inlineLogo" alt="'.$this->_translator->translate('Corporate logo').'" />';
					}
				}
			}

			if($optionName == 'wicIndustryType') {
				$this->_view->locationBlock = true;
				$this->_view->image = true;
				if(isset($config['wicIndustryType']) && ($config['wicIndustryType'] != '')) {
					$industries = unserialize($config['wicIndustryType']);
                    $industryList = $this->_getIndustryList();
					$industryVal = array();
					foreach ($industries as $item) {
						$industryVal[] = $industryList[$item];
					}
					$value = implode(', ', $industryVal);
				}
				else {
					$value = $this->_translator->translate('');
				}
			}

            if($optionName == 'wicOrganizationName') {
                $value = htmlentities($config['wicOrganizationName'], null, 'UTF-8');
            }
            $accountOrChannel = array('wicFbAccount', 'wicTwitAccount' ,'wicLinkedInAccount' ,'wicGplusAccount', 'wicYoutubeChannel' ,'wicRssChannel');
            if(in_array($optionName, $accountOrChannel)) {
                $parsedUrl = parse_url($value);
                if(!empty($parsedUrl['query'])) {
                    parse_str($parsedUrl['query'], $pieces);
                    $value = $parsedUrl['scheme'].'://' . $parsedUrl['host'] . $parsedUrl['path'] . '?' . str_replace('&', '&amp;', http_build_query($pieces));
                }
            }

			$wicTitle = null;
			switch ($optionName) {
				case 'wicOrganizationName':
					$wicTitle = $this->_translator->translate('Organization name');
					break;
				case 'wicOrganizationDescription':
					$wicTitle = $this->_translator->translate('Organization description');
					break;
				case 'wicPhone':
					$wicTitle = $this->_translator->translate('Phone number');
					break;
				case 'wicEmail':
					$wicTitle = $this->_translator->translate('E-mail address');
					break;
				case 'wicOrganizationCountry':
					$wicTitle = $this->_translator->translate('Country');
					break;
				case 'wicCity':
					$wicTitle = $this->_translator->translate('City');
					break;
				case 'wicCountryState':
					$wicTitle = $this->_translator->translate('State');
					break;
				case 'wicZip':
					$wicTitle = $this->_translator->translate('ZIP');
					break;
				case 'wicAddress1':
					$wicTitle = $this->_translator->translate('Address1');
					break;
				case 'wicAddress2':
					$wicTitle = $this->_translator->translate('Address2');
					break;
				case 'wicCorporateLogo':
					$wicTitle = $this->_translator->translate('Corporate logo');
					break;
				case 'wicIndustryType':
					$wicTitle = $this->_translator->translate('Industry type');
					break;
			}

			$this->_view->fieldTitle = $wicTitle;
			$this->_view->fieldName = $optionName;
			$this->_view->fieldValue = $value;
			$this->_view->notag = $this->_notag;
			$this->_view->href = $this->_href;
            $this->_view->currentUserRole = $loggedUserRole;
			$this->_view->actionUrl = $this->_websiteUrl.'plugin/widcard/run/setWicField';
			$inline = $this->_view->render('wicInline.phtml');
			return trim($inline);
	}


	protected function _makeOptionBizOrgName() {
		return $this->_renderWicOption('wicOrganizationName');
	}

	protected function _makeOptionBizOrgDesc() {
		return $this->_renderWicOption('wicOrganizationDescription');
	}

	protected function _makeOptionBizTelephone() {
		return $this->_renderWicOption('wicPhone');
	}

	protected function _makeOptionBizEmail() {
		return $this->_renderWicOption('wicEmail');
	}

	protected function _makeOptionBizCountry() {
		return $this->_renderWicOption('wicOrganizationCountry');
	}

	protected function _makeOptionBizCity() {
		return $this->_renderWicOption('wicCity');
	}

	protected function _makeOptionBizState() {
		return $this->_renderWicOption('wicCountryState');
	}

	protected function _makeOptionBizZip() {
		return $this->_renderWicOption('wicZip');
	}

	protected function _makeOptionBizAddress1() {
		return $this->_renderWicOption('wicAddress1');
	}

	protected function _makeOptionBizAddress2() {
		return $this->_renderWicOption('wicAddress2');
	}

	protected function _makeOptionBizLogo() {
		return $this->_renderWicOption('wicCorporateLogo');
	}

	protected function _makeOptionBizIndustry() {
		return $this->_renderWicOption('wicIndustryType');
	}

	protected function _makeOptionBizFbAccount() {
		return $this->_renderWicOption('wicFbAccount');
	}

	protected function _makeOptionBizTwitterAccount() {
		return $this->_renderWicOption('wicTwitAccount');
	}

    protected function _makeOptionBizLinkedInAccount() {
        return $this->_renderWicOption('wicLinkedInAccount');
    }

    protected function _makeOptionBizGplusAccount() {
        return $this->_renderWicOption('wicGplusAccount');
    }

    protected function _makeOptionBizYoutubeChannel() {
        return $this->_renderWicOption('wicYoutubeChannel');
    }

    protected function _makeOptionBizRssChannel() {
        return $this->_renderWicOption('wicRssChannel');
    }

    protected function _makeOptionAnalyticsCode()
    {
        return $this->_renderWicOption('waCode');
    }

    protected function _makeOptionLanding() {
		$this->_setWicToView();
		$this->_view->landingUrl = Tools_System_Tools::getRequestUri();
		return $this->_view->render('landingWic.phtml');
	}

	public function setWicFieldAction() {
        $loggedUserRole = $this->_sessionHelper->getCurrentUser()->getRoleId();
        if($loggedUserRole === Tools_Security_Acl::ROLE_SUPERADMIN) {
            $tokenToValidate = $this->_request->getParam(Tools_System_Tools::CSRF_SECURE_TOKEN, false);
            $valid = Tools_System_Tools::validateToken($tokenToValidate, self::WIDCARD_SECURE_TOKEN);
            if (!$valid) {
                exit;
            }
            $config[$this->_requestedParams['key']] = $this->_requestedParams['value'];
			Application_Model_Mappers_ConfigMapper::getInstance()->save($config);
            $this->_responseHelper->success($this->_translator->translate('Field has been updated'));
		}
	}

	public function renderView() {
        $loggedUserRole = $this->_sessionHelper->getCurrentUser()->getRoleId();
        if ($loggedUserRole === Tools_Security_Acl::ROLE_SUPERADMIN) {
			$this->_setWicToView();

            $this->_view->helpSection = self::SECTION_WIDCARD;
            $this->_layout->content = $this->_view->render('registrationForm.phtml');
            echo $this->_layout->render();
		}
	}

	private function _setWicToView () {
		$config = Application_Model_Mappers_ConfigMapper::getInstance()->getConfig();
			$configHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('config');
			$languageHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('language');
			$langList = $languageHelper->getLanguages(false);
			$language = ( ($languageHelper->getCurrentLanguage() === null) || ( $languageHelper->getCurrentLanguage() == '' ) ) ? $configHelper->getConfig('language') : $languageHelper->getCurrentLanguage();
			$this->_view->websiteUrl = $this->_websiteUrl;
			$locale = Zend_Controller_Action_HelperBroker::getStaticHelper('session');
			$this->_view->countryList = Zend_Locale::getTranslationList('territory', $locale->locale, 2);
			asort($this->_view->countryList);
			if(isset($config['wicOrganizationCountry'])) {
				if($config['wicOrganizationCountry'] == 'US') {
					$this->_view->stateList = self::$stateListUSA;
				}
				elseif($config['wicOrganizationCountry'] == 'CA') {
					$this->_view->stateList = self::$stateListCanada;
				}
                elseif($config['wicOrganizationCountry'] == 'AU') {
                    $this->_view->stateList = self::$stateListAustralia;
                }
				else {$this->_view->stateList = '';}
			}

			if(!isset($config['language'])) {
				$this->_view->toasterLang = $langList[$language];
			}
			else {
				$this->_view->toasterLang = $langList[$config['language']];
			}
			$this->_view->langList = $langList;

			$confFilePath = Zend_Registry::get('website');
			$confFilePath = $confFilePath['path'].'plugins/widcard/config/paymentType.ini';
			$paymentTypes = new Zend_Config_Ini($confFilePath);
			$confFilePath = Zend_Registry::get('website');
			$this->_view->addScriptPath($confFilePath['path'].'seotoaster_core/application/views/scripts/');
			$this->_view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
			$this->_view->currentWebsiteUrl = $this->_websiteUrl;
			$this->_view->organizationName = isset($config['wicOrganizationName']) ? $config['wicOrganizationName'] : '';
			$this->_view->organizationDescription = isset($config['wicOrganizationDescription']) ? $config['wicOrganizationDescription'] : '';
			$this->_view->organizationCountry = isset($config['wicOrganizationCountry']) ? $config['wicOrganizationCountry'] : '';
			$this->_view->address1 = isset($config['wicAddress1']) ? $config['wicAddress1'] : '';
			$this->_view->address2 = isset($config['wicAddress2']) ? $config['wicAddress2'] : '';
			$this->_view->city = isset($config['wicCity']) ? $config['wicCity'] : '';
			$this->_view->countryState = isset($config['wicCountryState']) ? $config['wicCountryState'] : '';
			$this->_view->zip = isset($config['wicZip']) ? $config['wicZip'] : '';
			$this->_view->phone = isset($config['wicPhone']) ? $config['wicPhone'] : '';
			$this->_view->email = isset($config['wicEmail']) ? $config['wicEmail'] : '';
			$this->_view->fbAccount = isset ($config['wicFbAccount']) ? $config['wicFbAccount'] :'' ;
			$this->_view->twitAccount = isset ($config['wicTwitAccount']) ? $config['wicTwitAccount'] :'' ;
            $this->_view->linkedInAccount = isset($config['wicLinkedInAccount']) ? $config['wicLinkedInAccount']: '';
            $this->_view->gplusAccount = isset($config['wicGplusAccount'])? $config['wicGplusAccount']: '';
            $this->_view->youtubeChannel = isset($config['wicYoutubeChannel'])? $config['wicYoutubeChannel']: '';
            $this->_view->rssChannel = isset($config['wicRssChannel'])? $config['wicRssChannel']: '';
			$this->_view->industryType = isset($config['wicIndustryType']) ? unserialize($config['wicIndustryType']) : '';
			$this->_view->paymentTypes = $paymentTypes->toArray();
			$this->_view->paymentType = isset($config['wicPaymentType']) ? unserialize($config['wicPaymentType']) : '';
			$this->_view->business = isset($config['wicBusiness']) ? $config['wicBusiness'] : '';
			$this->_view->isEcommerce = isset($config['wicIsEcommerce']) ? $config['wicIsEcommerce'] : '0';
			$this->_view->payOnline = isset($config['wicPayOnline']) ? $config['wicPayOnline'] : '';
			$this->_view->corporateLogo = (isset($config['wicCorporateLogo']) && ($config['wicCorporateLogo'] != '') ) ? $this->_websiteUrl.$config['wicCorporateLogo'] : $this->_websiteUrl.'system/images/noimage.png';
			$this->_view->waCode = (isset($config['waCode'])) ? $config['waCode'] : '';
			$this->_view->sambaToken = isset($config['sambaToken']) ? $config['sambaToken'] : '';
			if(isset($config['wicAnalyticsType'])){
				$analyticsType = unserialize($config['wicAnalyticsType']);
				if( is_array($analyticsType)) {
					$this->_view->analyticsType = $analyticsType[0];
					$this->_view->analyticsTypeUseGA = $analyticsType[1];
				}
				else {$this->_view->analyticsType = $analyticsType;}
			}
			else{$this->_view->analyticsType = '';}
			$this->_view->agreement = isset($config['wicAgreement']) ? $config['wicAgreement'] : null;
			$data['sambaToken'] = isset($config['sambaToken']) ? $config['sambaToken'] : '';
			$this->_view->industryList = $this->_getIndustryList();
			if(isset($this->_sessionHelper->widcardErr)) {
				$this->_view->errors = $this->_sessionHelper->widcardErr;
				unset($this->_sessionHelper->widcardErr);
			}
			if(isset($this->_sessionHelper->widcardMessage)) {
				$this->_view->widcardMessage = $this->_sessionHelper->widcardMessage;
				unset($this->_sessionHelper->widcardMessage);
			}
			$this->_view->wicInlineEditField = (isset($this->_requestedParams['wicInline']));
	}

	public function getWebsiteIdCardAction() {
		echo $this->renderView();
	}

	public function uploadLogoAction() {
        $loggedUserRole = $this->_sessionHelper->getCurrentUser()->getRoleId();
        if ($loggedUserRole === Tools_Security_Acl::ROLE_SUPERADMIN) { //ADMINPANEL
			$uploadWebsiteIdCardImage = new Zend_File_Transfer_Adapter_Http();
			$fileInfo = $uploadWebsiteIdCardImage->getFileInfo();

			$fileExtension = pathinfo($fileInfo['file']['name'], PATHINFO_EXTENSION);
			if($fileInfo['file']['type'] != null) {
				$miscConfig       = Zend_Registry::get('misc');
				$uploadWebsiteIdCardImage
				->addValidator('Extension', false,  array('jpg', 'jpeg', 'png', 'gif'))
				->addValidator('ImageSize', false, array('maxwidth' => $miscConfig['imgMaxWidth'], 'maxheight' => $miscConfig['imgMaxWidth']));
				if($uploadWebsiteIdCardImage->isValid()) {
					$teaserImage = 'plugins/widcard/system/userdata/CorporateLogo.'.$fileExtension; // CORPORATE LOGO
					$uploadWebsiteIdCardImage->addFilter('Rename', array('target' => $this->_websiteHelper->getPath().'plugins/widcard/system/userdata/'.'CorporateLogo.'.$fileExtension, 'overwrite' => true));
					$uploadWebsiteIdCardImage->receive();
					$configMapper = Application_Model_Mappers_ConfigMapper::getInstance();
					$config = $configMapper->getConfig();
					$config['wicCorporateLogo'] = $teaserImage;
					$configMapper->save($config);
					$this->_responseHelper->success(array('src' => $this->_websiteUrl.'plugins/widcard/system/userdata/CorporateLogo.' . $fileExtension . '?' . microtime(1)));
				}
				else {
					$errMessage['imageUploadErr'] = $uploadWebsiteIdCardImage->getMessages();
					$this->_responseHelper->fail(array('data' => $errMessage['imageUploadErr']));
				}
			}
		}
	}

	public function setWebsiteIdCardAction() {
        $loggedUserRole = $this->_sessionHelper->getCurrentUser()->getRoleId();
        if ($loggedUserRole === Tools_Security_Acl::ROLE_SUPERADMIN) { //ADMINPANEL
            if($this->_request->isPost()) {
                $tokenToValidate = $this->_request->getParam(Tools_System_Tools::CSRF_SECURE_TOKEN, false);
                $valid = Tools_System_Tools::validateToken($tokenToValidate, self::WIDCARD_SECURE_TOKEN);
                if (!$valid) {
                    exit;
                }
                $errData = array();
			if( isset($this->_requestedParams['sambaUrl']) ) {
				$redirectUrl = (($this->_requestedParams['sambaUrl'] != '') ) ? $this->_websiteUrl : $this->_websiteUrl.'plugin/widcard/run/getWebsiteIdCard';
			}
			if(isset($this->_requestedParams['landingUrl'])) {
				$redirectUrl = $this->_requestedParams['landingUrl'];
			}

			$configMapper = Application_Model_Mappers_ConfigMapper::getInstance();
			$toasterConfig = $configMapper->getConfig();
			if( isset($this->_requestedParams['analytics']) ) {
				$analyticsType = $this->_requestedParams['analytics'];
				if(isset($this->_requestedParams['useGA'])) {
					$analyticsType = array($this->_requestedParams['analytics'], $this->_requestedParams['useGA']);
				}
			}
			else {$analyticsType = 'NA';}
			$config = array(
				'wicOrganizationName' => isset($this->_requestedParams['organization_name']) ? $this->_requestedParams['organization_name'] : '',
				'wicOrganizationDescription' => isset($this->_requestedParams['organization_description']) ? $this->_requestedParams['organization_description'] : '',
				'wicCorporateLogo' => (isset($toasterConfig['wicCorporateLogo']) && ($toasterConfig['wicCorporateLogo'] != '') ) ? $toasterConfig['wicCorporateLogo'] : $this->_websiteUrl.'system/images/noimage.png',
				'wicOrganizationCountry' => isset($this->_requestedParams['organization_country']) ? $this->_requestedParams['organization_country'] : '',
				'wicAddress1' => isset($this->_requestedParams['address1']) ? $this->_requestedParams['address1'] : '',
				'wicAddress2' => isset($this->_requestedParams['address2']) ? $this->_requestedParams['address2'] : '',
				'wicCity' => isset($this->_requestedParams['city']) ? $this->_requestedParams['city'] : '',
				'wicCountryState' => isset($this->_requestedParams['country_state']) ? $this->_requestedParams['country_state'] : '',
				'wicZip' => isset($this->_requestedParams['zip']) ? $this->_requestedParams['zip'] : '',
				'wicPhone' => isset($this->_requestedParams['phone']) ? $this->_requestedParams['phone'] : '',
				'wicEmail' => isset($this->_requestedParams['email']) ? $this->_requestedParams['email'] : '',
				'wicFbAccount' => isset($this->_requestedParams['fbAccount'])? $this->_requestedParams['fbAccount'] : '',
				'wicTwitAccount' => isset($this->_requestedParams['twitAccount'])? $this->_requestedParams['twitAccount']: '',
                'wicLinkedInAccount' => isset($this->_requestedParams['linkedInAccount'])? $this->_requestedParams['linkedInAccount']: '',
                'wicGplusAccount' => isset($this->_requestedParams['gplusAccount'])? $this->_requestedParams['gplusAccount']: '',
                'wicYoutubeChannel' => isset($this->_requestedParams['youtubeChannel'])? $this->_requestedParams['youtubeChannel']: '',
                'wicRssChannel' => isset($this->_requestedParams['rssChannel'])? $this->_requestedParams['rssChannel']: '',
				'wicIndustryType' => isset($this->_requestedParams['industry_type']) ? serialize($this->_requestedParams['industry_type']) : '',
				'wicPaymentType' => isset($this->_requestedParams['payway']) ? serialize($this->_requestedParams['payway']) : '',
				'wicBusiness' => isset($this->_requestedParams['business']) ? $this->_requestedParams['business'] : '',
				'wicPayOnline' => isset($this->_requestedParams['pay-online']) ? $this->_requestedParams['pay-online'] : '',
				'wicAnalyticsType' => serialize($analyticsType),
				'language' => $this->_requestedParams['websiteLang'],
				'sambaToken' => isset($this->_requestedParams['samba_token']) ? $this->_requestedParams['samba_token'] : ''
			);
			if(isset($toasterConfig['websiteId'])) {$config['websiteId'] = $toasterConfig['websiteId'];}
			if(isset($this->_requestedParams['landingUrl'])){$config['landingPage'] = true;}
			if(isset ($this->_requestedParams['agreement'])){$config['wicAgreement'] = true;}
			$data = array();
			if(isset($this->_requestedParams['analytics']) && ($this->_requestedParams['analytics'] == 'WA')) {
				if( isset($this->_requestedParams['waCode']) ) {
					if(isset($this->_requestedParams['useGA'])) {
						$data['googleAnalyticsCode'] = $this->_requestedParams['waCode'];

					}
					else {
						$data['webAnalyticsCode'] = $this->_requestedParams['waCode'];
					}
					$config['waCode'] = $this->_requestedParams['waCode'];
				}
			}
			$configMapper->save($config);
			$this->_createKmlFromWidData();
			unset($config['wicAnalyticsType']);
			$config['wicAnalyticsType'] = $this->_requestedParams['analytics'];
			$languageHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('language');
			if ($this->_requestedParams['websiteLang'] != $languageHelper->getCurrentLanguage()) {
					$languageHelper->setLanguage($this->_requestedParams['websiteLang']);
			}
			$newsFolderName = Newslog_Models_Mapper_ConfigurationMapper::getInstance()->fetchConfigParam('folder');
			$data = array_merge($config, $data);
			if ($newsFolderName !== null){
				$data['newsFolderName'] = $newsFolderName;
			}
			unset($data['sambaToken']);
			$fileExtension = substr($data['wicCorporateLogo'], strrpos($data['wicCorporateLogo'], '.')) ;
			$logoFile = realpath(__DIR__).'/system/userdata/CorporateLogo'.$fileExtension;
			if(file_exists($logoFile)) {
				$imgBinary = fread(fopen($logoFile, "r"), filesize($logoFile));;
				$src = base64_encode($imgBinary);
				$data['wicCorporateLogo'] = array('extension' => substr($fileExtension, 1), 'src' => $src);
			}
			else {
				$data['wicCorporateLogo'] = '';
			}
			$data['websiteUrl'] = $this->_websiteUrl;
			if( isset ($this->_requestedParams['samba_token']) && ( trim($this->_requestedParams['samba_token']) != '') ) {
				$data['sambaToken'] = $this->_requestedParams['samba_token'];
				$register = Tools_Factory_PluginFactory::createPlugin('api',array(), array('websiteUrl' => $this->_websiteUrl));
				$registerResult = (object)$register::request('post', 'website', $data);//, truejson_decode();
				unset($config);
				if($registerResult->done == false) {
					$errData['authenticateErr'] = $registerResult->message;
				}
				else {
					if(property_exists($registerResult, 'websiteId')) {
						$config['websiteId'] = $registerResult->websiteId;
						$config['registered'] = true;
					}
					if(property_exists($registerResult, 'whiteLabelLogo')) {
						$config['whiteLabelLogo'] = $registerResult->whiteLabelLogo;
					}
					else {
						$configDbTable = new Application_Model_DbTable_Config();
						$configDbTable->getAdapter()->delete('config', $configDbTable->getAdapter()->quoteInto('name = ?', 'whiteLabelLogo'));
					}
					if( $registerResult->status == 'Updated' ) {
						$config['registered'] = true;
						if(isset($this->_requestedParams['landingUrl'])) {
							$this->_sessionHelper->widcardMessage = $this->_translator->translate($registerResult->message);
							$redirectUrl = $redirectUrl . '#step2';
						}
						else if(isset($this->_requestedParams['sambaUrl'])) {
							$this->_sessionHelper->widcardMessage = $this->_translator->translate($registerResult->message);
						}
					}
					$configMapper->save($config);
					if($registerResult->status == 'Added') {
						if(isset($this->_requestedParams['landingUrl'])) {
							$redirectUrl = $redirectUrl . '#step2';
						}
						$this->_sessionHelper->widcardMessage = $this->_translator->translate($registerResult->message);
					}
				}
			}
			else {
				$errData['loginErr'] = $this->_translator->translate('Please enter SEOSAMBA token');
				$errData['tokenLink'] = '<a href="http://www.seotoaster.com/seosamba-token-where-to-get-and-why-you-need-it.html" target="_blank" title="SEO Samba token usage.">SEOSAMBA token - where to get and why you need it</a>';
			}
			$this->_sessionHelper->widcardErr = (!empty($errData)) ? $errData : null;
			$this->_redirector->gotoUrl($redirectUrl);
		}
		}
	}

	public function sambaUpdateWebsiteIdCard($params) {
        $config = array(
			'wicOrganizationName' => isset($params['organization_name']) ? $params['organization_name'] : '',
			'wicOrganizationDescription' => isset($params['organization_description']) ? $params['organization_description'] : '',
			'wicOrganizationCountry' => isset($params['organization_country']) ? $params['organization_country'] : '',
			'wicAddress1' => isset($params['address1']) ? $params['address1'] : '',
			'wicAddress2' => isset($params['address2']) ? $params['address2'] : '',
			'wicCity' => isset($params['city']) ? $params['city'] : '',
			'wicCountryState' => isset($params['country_state']) ? $params['country_state'] : '',
			'wicZip' => isset($params['zip']) ? $params['zip'] : '',
			'wicPhone' => isset($params['phone']) ? $params['phone'] : '',
			'wicEmail' => isset($params['email']) ? $params['email'] : '',
            'wicFbAccount' => isset($params['fbAccount'])? $params['fbAccount'] : '',
            'wicTwitAccount' => isset($params['twitAccount'])? $params['twitAccount']: '',
            'wicLinkedInAccount' => isset($params['linkedInAccount'])? $params['linkedInAccount']: '',
            'wicGplusAccount' => isset($params['gplusAccount'])? $params['gplusAccount']: '',
            'wicYoutubeChannel' => isset($params['youtubeChannel'])? $params['youtubeChannel']: '',
            'wicRssChannel' => isset($params['rssChannel'])? $params['rssChannel']: '',
			'wicIndustryType' => isset($params['industry_type']) ? serialize($params['industry_type']) : '',
            'wicBusiness' => isset($params['business']) ? $params['business'] : '',
			'wicPayOnline' => isset($params['pay-online']) ? $params['pay-online'] : '',
			'wicPaymentType' => isset($params['payway']) ? serialize($params['payway']) : ''
        );

		if(isset($params['imageSrc'])) {
			$ifp = fopen( $this->_websiteHelper->getPath().'plugins/widcard/system/userdata/'.'CorporateLogo.'.$params['imgExt'], 'wb' );
			fwrite( $ifp, base64_decode($params['imageSrc']));
			fclose( $ifp );
			$config['wicCorporateLogo'] = 'plugins/widcard/system/userdata/CorporateLogo.'.$params['imgExt'];
		}
		$configMapper = Application_Model_Mappers_ConfigMapper::getInstance();
		$configMapper->save($config);
		$data['done'] = true;
		return $data;
	}

    public function sambaReplacePhoneNumber($params) {
        if(!empty($params['phoneNumber'])) {
            Application_Model_Mappers_ConfigMapper::getInstance()->save(array('wicPhone' => $params['phoneNumber']));
            return array('done' => true);
        }else {
            Application_Model_Mappers_ConfigMapper::getInstance()->save(array('wicPhone' => ''));
            return array('done' => true);
        }
    }

	public function getStatesAction() {
		if(Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_ADMINPANEL)) {
			if($this->_requestedParams['countryCode'] == 'CA') {
				echo json_encode(array('states' => self::$stateListCanada));
			}
			elseif($this->_requestedParams['countryCode'] == 'US') {
				echo json_encode(array('states' => self::$stateListUSA));
			}
            elseif($this->_requestedParams['countryCode'] == 'AU') {
                echo json_encode(array('states' => self::$stateListAustralia));
            }
		}
	}

    private function _createKmlFromWidData()
    {
        $configData = Application_Model_Mappers_ConfigMapper::getInstance()->getConfig();
        if (!empty($configData['wicAddress1']) && isset($configData['wicAddress2']) && isset($configData['wicOrganizationName']) && isset($configData['wicOrganizationCountry'])) {
            $addressFilds = array('wicAddress1', 'wicAddress2', 'wicCity', 'wicCountryState', 'wicOrganizationCountry', 'wicZip');
            $address = '';
            foreach ($addressFilds as $key) {
                $address .= !empty($configData[$key]) ? $configData[$key] . ' ' : '';
            }
            $coordinates = $this->_createCoordForKml($address);
            if ($coordinates['coord']['lat'] !== 0) {
                // Creates the Document.
                $industrysForKml = '';
                if ($configData['wicIndustryType']) {
                    $industryTypes = unserialize($configData['wicIndustryType']);
                    $data['sambaToken'] = isset($configData['sambaToken']) ? $configData['sambaToken'] : '';
                    $industryList = $this->_getIndustryList();
                    $industrysArray = array();
                    if ($industryList != null) {
                        foreach ($industryList as $key => $item) {
                            $industrysArray[$key] = $item;
                        }
                        if ($industrysArray != null) {
                            if (is_array($industryTypes)) {
                                foreach ($industryTypes as $item) {
                                    if (isset($industrysArray[$item])) {
                                        $industrysForKml .= $industrysArray[$item] . ',';
                                    }
                                }
                            }

                        }
                    }
                    $industrysForKml = substr_replace($industrysForKml, "", -1);
                }

                $dom = new DOMDocument('1.0', 'UTF-8');

                // Creates the root KML element and appends it to the root document.
                $node = $dom->createElementNS('http://www.opengis.net/kml/2.2', 'kml');
                $parNode = $dom->appendChild($node);

                // Creates a KML Document element and append it to the KML element.
                $dnode = $dom->createElement('Document');
                $docNode = $parNode->appendChild($dnode);

                // Creating one marker
                $node = $dom->createElement('Placemark');
                $placeNode = $docNode->appendChild($node);

                // Creates an id attribute and assign it the value of id column.
                //$placeNode->setAttribute('id', 'placemark' . 1);

                // Create name, and description elements and assigns them the values of the name and address columns from the results.
                $nameNode = $dom->createElement(
                    'name',
                    htmlentities($configData['wicOrganizationName'], null, 'UTF-8')
                );
                $placeNode->appendChild($nameNode);
                //$descNode = $dom->createElement('description', htmlentities($configData['wicAddress1'].$configData['wicAddress2'].'/'.$configData['wicCity'].'/'.$configData['wicCountryState'].$configData['wicZip'].'/'.$configData['wicOrganizationCountry']));
                $descNode = $dom->createElement(
                    'description',
                    '<p style="overflow:hidden; margin-bottom:10px;"><img style="float:left; margin-right:10px" src="' . $this->_websiteUrl . $configData['wicCorporateLogo'] . '" width=100 alt="company logo">' . $configData['wicAddress1'] . ',' . $configData['wicAddress2'] . '<br />' . $configData['wicCity'] . ', ' . $configData['wicCountryState'] . ' ' . $configData['wicZip'] . ', ' . $configData['wicOrganizationCountry'] . '<br />' . $configData['wicPhone'] . ' ' . $configData['wicEmail'] . '<br /> Industries: ' . $industrysForKml . '<br />' . $this->_websiteUrl . '</p><p style="clear:both;">' . $configData['wicOrganizationDescription'] . '</p>'
                );
                $placeNode->appendChild($descNode);
                // Creates a Point element.
                $pointNode = $dom->createElement('Point');
                $placeNode->appendChild($pointNode);
                // Creates a coordinates element and gives it the value of the lng and lat columns from the results.
                $coorStr = $coordinates['coord']['lng'] . ',' . $coordinates['coord']['lat'];
                $coorNode = $dom->createElement('coordinates', $coorStr);
                $pointNode->appendChild($coorNode);
                //End creating marker

                $kmlOutput = $dom->saveXML();
                $configWebsite = Zend_Controller_Action_HelperBroker::getStaticHelper('website');
                $websitePath = $configWebsite->getPath();
                $fileKml = $websitePath . 'website.kml';
                if (file_exists($fileKml) && is_writable($fileKml)) {
                    $fp = fopen($fileKml, "w");
                    fwrite($fp, $kmlOutput);
                    fclose($fp);
                } elseif (is_writable($websitePath) && is_executable($websitePath)) {
                    $fp = fopen($websitePath . 'website.kml', 'w');
                    fwrite($fp, $kmlOutput);
                    fclose($fp);
                }

            }
        }
    }

     private function _createCoordForKml($wicAddress){
            $addressUrl   = "http://maps.googleapis.com/maps/api/geocode/xml?address=".urlencode($wicAddress)."&sensor=false";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $addressUrl);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            $xml = new SimpleXMLElement($response);
            $coord['lat'] = 0;
            if($xml->status !== "ZERO_RESULTS"){
                $coord['lat'] = $xml->result->geometry->location->lat;
                $coord['lng'] = $xml->result->geometry->location->lng;
            }
            return array('address' => $wicAddress, 'coord' => $coord);
    }

    public function afterController() {
        $view = Zend_Layout::getMvcInstance()->getView();
        if($view->pageData['url'] == 'index.html') {
            $websiteConfig = Application_Model_Mappers_ConfigMapper::getInstance()->getConfig();
            if(isset($websiteConfig['gwmtVerificationCodeClient'])) {
                $view->headMeta()->appendName('google-site-verification', $websiteConfig['gwmtVerificationCodeClient']);
            }
            if(isset($websiteConfig['gwmtVerificationCodeAgency'])) {
                $view->headMeta()->appendName('google-site-verification', $websiteConfig['gwmtVerificationCodeAgency']);
            }
            if( isset($websiteConfig['gwmtVerificationCodeClient']) || isset($websiteConfig['gwmtVerificationCodeAgency']) ) {
                Zend_Layout::getMvcInstance()->setView($view);
            }
        }
	}

	public static $stateListUSA = array(
        'AL'=>"Alabama",
        'AK'=>"Alaska",
        'AZ'=>"Arizona",
        'AR'=>"Arkansas",
        'CA'=>"California",
        'CO'=>"Colorado",
        'CT'=>"Connecticut",
        'DE'=>"Delaware",
        'DC'=>"District Of Columbia",
        'FL'=>"Florida",
        'GA'=>"Georgia",
        'HI'=>"Hawaii",
        'ID'=>"Idaho",
        'IL'=>"Illinois",
        'IN'=>"Indiana",
        'IA'=>"Iowa",
        'KS'=>"Kansas",
        'KY'=>"Kentucky",
        'LA'=>"Louisiana",
        'ME'=>"Maine",
        'MD'=>"Maryland",
        'MA'=>"Massachusetts",
        'MI'=>"Michigan",
        'MN'=>"Minnesota",
        'MS'=>"Mississippi",
        'MO'=>"Missouri",
        'MT'=>"Montana",
        'NE'=>"Nebraska",
        'NV'=>"Nevada",
        'NH'=>"New Hampshire",
        'NJ'=>"New Jersey",
        'NM'=>"New Mexico",
        'NY'=>"New York",
        'NC'=>"North Carolina",
        'ND'=>"North Dakota",
        'OH'=>"Ohio",
        'OK'=>"Oklahoma",
        'OR'=>"Oregon",
        'PA'=>"Pennsylvania",
        'RI'=>"Rhode Island",
        'SC'=>"South Carolina",
        'SD'=>"South Dakota",
        'TN'=>"Tennessee",
        'TX'=>"Texas",
        'UT'=>"Utah",
        'VT'=>"Vermont",
        'VA'=>"Virginia",
        'WA'=>"Washington",
        'WV'=>"West Virginia",
        'WI'=>"Wisconsin",
        'WY'=>"Wyoming");

    public static $stateListCanada = array(
        'AB'	=>	'Alberta',
        'BC'	=>	'British Columbia',
        'MB'	=>	'Manitoba',
        'NB'	=>	'New Brunswick',
        'NF'	=>	'Newfoundland and Labrador',
        'NT'	=>	'Northwest Territories',
        'NS'	=>	'Nova Scotia',
        'NU'	=>	'Nunavut',
        'ON'	=>	'Ontario',
        'PE'	=>	'Prince Edward Island',
        'QC'	=>	'Quebec',
        'SK'	=>	'Saskatchewan',
        'YT'	=>	'Yukon Territory'
    );

    public static $stateListAustralia = array(
        'ACT' => 'Australian Capital Territory',
        'NSW' => 'New South Wales',
        'NT' => 'Northern Territory',
        'QLD' => 'Queensland',
        'SA' => 'South Australia',
        'TAS' => 'Tasmania',
        'VIC' => 'Victoria',
        'WA' => 'Western Australia'
    );

	public function showTermsAction() {
		$addressUrl   = "http://www.seosamba.com/terms.txt";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $addressUrl);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
		$this->_view->terms = $response;
		echo $this->_view->render('terms.phtml');
	}

	private function _getIndustryList() {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'https://mojo.seosamba.com/plugin/api/run/industryList');
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Industry list REQUEST'));
		curl_setopt($curl, CURLOPT_VERBOSE, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		return json_decode(curl_exec($curl));
	}

}