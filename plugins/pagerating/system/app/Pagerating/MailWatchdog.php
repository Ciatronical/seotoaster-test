<?php
/**
 * MailWatchdog.php
 * @author Pavel Kovalyov <pavlo.kovalyov@gmail.com>
 */
class Pagerating_MailWatchdog implements Interfaces_Observer  {

	const TRIGGER_NEW_REVIEW = 'newreview';

	public function __construct($options = array()) {
		$this->_configHelper = Zend_Controller_Action_HelperBroker::getExistingHelper('config');
		$this->_websiteHelper = Zend_Controller_Action_HelperBroker::getExistingHelper('website');
		$this->_options = $options;
		$this->_initMailer();
		$this->_entityParser = new Tools_Content_EntityParser();
	}

	public function notify($object) {
		if (!$object){
			return false;
		}
		if (isset($this->_options['trigger'])){
			$methodName = '_send'.ucfirst(strtolower(preg_replace('/\s*/', '', $this->_options['trigger']))).'Mail';
			if (method_exists($this, $methodName)){
				$this->$methodName($object);
			}
		}
	}

	private function _sendNewreviewMail(Pagerating_Models_Review $review){
        $userMapper = Application_Model_Mappers_UserMapper::getInstance();
        $adminEmail = isset($systemConfig['adminEmail'])?$systemConfig['adminEmail']:'admin@localhost';
        switch ($this->_options['recipient']) {
			case Tools_Security_Acl::ROLE_SUPERADMIN:
				$su = $userMapper->getDbTable()->fetchAll(array('role_id = ?'=>Tools_Security_Acl::ROLE_SUPERADMIN));
				if ($su->count()){
					foreach($su as $user){
						$this->_mailer->setMailTo($user['email']);
					}
				} else {
					return false;
				}
				break;
            case Tools_Security_Acl::ROLE_ADMIN:
                $this->_mailer->setMailToLabel('Admin')
                    ->setMailTo($adminEmail);
                $where = $userMapper->getDbTable()->getAdapter()->quoteInto("role_id = ?", Tools_Security_Acl::ROLE_ADMIN);
                $adminUsers = $userMapper->fetchAll($where);
                if(!empty($adminUsers)){
                    $adminBccArray = array();
                    foreach($adminUsers as $admin){
                        array_push($adminBccArray, $admin->getEmail());
                    }
                    if(!empty($adminBccArray)){
                        $this->_mailer->setMailBcc($adminBccArray);
                    }
                }
                break;
            case Tools_Security_Acl::ROLE_GUEST:
                $this->_mailer->setMailToLabel($review->getName())->setMailTo($review->getEmail());
                break;
            default:
                error_log('Unsupported recipient '.$this->_options['recipient'].' given');
                return false;
                break;
		}

		if (false === ($body = $this->_prepareEmailBody(array($review)))) {
			return false;
		}

		$this->_entityParser->objectToDictionary($review);

        $page = Application_Model_Mappers_PageMapper::getInstance()->find($review->getPageId());
        if ($page instanceof Application_Model_Models_Page) {
            $reviewPage = '<a title="page review" href="'.$this->_websiteHelper->getUrl().$page->getUrl().'">'.$this->_websiteHelper->getUrl().$page->getUrl().'</a>';
            $this->_entityParser->addToDictionary(array('review:pageurl' => $reviewPage));
        }

		$this->_mailer
			->setBody($this->_entityParser->parse($body))
			->setSubject($this->_options['subject'])
			->setMailFrom($this->_options['from'])
			->setMailFromLabel($this->_options['from']);
		return ($this->_mailer->send() !== false);
	}

	private function _initMailer(){
		$config = $this->_configHelper->getConfig();
		$this->_mailer = new Tools_Mail_Mailer();

		if ((bool)$config['useSmtp']){
			$smtpConfig = array(
				'host'      => $config['smtpHost'],
				'username'  => $config['smtpLogin'],
				'password'  => $config['smtpPassword']
			);
			if ((bool)$config['smtpSsl']){
				$smtpConfig['ssl'] = $config['smtpSsl'];
			}
			if (!empty($config['smtpPort'])){
				$smtpConfig['port'] = $config['smtpPort'];
			}
			$this->_mailer->setSmtpConfig($smtpConfig);
			$this->_mailer->setTransport(Tools_Mail_Mailer::MAIL_TYPE_SMTP);
		} else {
			$this->_mailer->setTransport(Tools_Mail_Mailer::MAIL_TYPE_MAIL);
		}
	}

	private function _prepareEmailBody(){
		$tmplName = $this->_options['template'];
		$tmplMessage = $this->_options['message'];
		$mailTemplate = Application_Model_Mappers_TemplateMapper::getInstance()->find($tmplName);

		if (!empty($mailTemplate)){
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
				'websiteUrl'   => $this->_websiteHelper->getUrl(),
				'websitePath'  => $this->_websiteHelper->getPath(),
				'currentTheme' => $extConfig['currentTheme'],
				'themePath'    => $themeData['path'],
			);
			$parser = new Tools_Content_Parser($mailTemplate, array(), $parserOptions);

			return $parser->parseSimple();
		}

		return false;
	}
}
