<?php

class Pagerating extends Tools_Plugins_Abstract
{

    const MODE_READ_ONLY = 'readonly';

    protected $_formId;
    public static $emailTriggers = array(
        'Pagerating_MailWatchdog'
    );

    protected $_currentConfig;


    protected function _init()
    {
        $this->_layout = new Zend_Layout();
        $this->_layout->setLayoutPath(__DIR__ . '/views/');
        $this->_view->setScriptPath(dirname(__FILE__) . '/views/');
        $this->_configMapper = Application_Model_Mappers_ConfigMapper::getInstance();
        $this->_currentConfig = $this->_configMapper->getConfig();
    }

    public function beforeController()
    {
        $layout = Zend_Layout::getMvcInstance();
        $currentController = $this->_request->getParam('controller');
        if (!preg_match('~backend_~', $currentController)) {
            $layout->getView()->headScript()->appendFile(
                $this->_websiteUrl . 'plugins/pagerating/system/layout/js/pagerating.min.js'
            );
        }
    }

    /**
     * Widget generator {$plugin:pagerating:stars[:product_id[:readonly]]}
     * @return string
     */
    protected function _makeOptionStars()
    {
        if (isset($this->_options[1]) && is_numeric($this->_options[1])) {
            $rating = Pagerating_Mappers_RatingMapper::getInstance()->getProductRatingByProdId($this->_options[1]);
        } elseif (null === ($rating = Pagerating_Mappers_RatingMapper::getInstance()->find(
                $this->_seotoasterData['id']
            ))
        ) {
            $rating = new Pagerating_Models_Rating(array('pageId' => $this->_seotoasterData['id']));
        }

        $readOnly = 'false';
        $voteLink = false;
        if (isset($this->_options[1]) && is_numeric($this->_options[1]) || in_array(
                self::MODE_READ_ONLY,
                $this->_options
            )
        ) {
            $readOnly = 'true';
            if ($rating->getRatingValue() == 0) {
                if ($page = Application_Model_Mappers_PageMapper::getInstance()->find($rating->getPageId())) {
                    $voteLink = $this->_seotoasterData['websiteUrl'] . $page->getUrl();
                }
            }
        }

        $this->_view->readOnly = $readOnly;
        $this->_view->voteLink = $voteLink;
        $this->_view->rating = $rating;

        return $this->_view->render('starrating.phtml');
    }

    /**
     * Widget generator {$plugin:pagerating:review[:moderated]}
     * @return string
     */
    protected function _makeOptionReview()
    {
        $this->_view->reviews = Pagerating_Mappers_ReviewMapper::getInstance()->fetchAll(
            array('pageId = ?' => $this->_seotoasterData['id']),
            'datePublished DESC'
        );

        if (Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_USERS) || !$this->_request->getCookie(
                'pagerating' . $this->_seotoasterData['id']
            )
        ) {
            $form = new Pagerating_Forms_Review();
            $form->setAction(
                trim($this->_websiteUrl, '/') . $this->_view->url(
                    array('name' => strtolower(__CLASS__), 'run' => 'review'),
                    'pluginroute'
                )
            );
            $this->_formId = uniqid('review-form');
            $form->setAttrib('id', $this->_formId);
            $form->getElement('pageId')->setValue($this->_seotoasterData['id']);

            if(isset($this->_currentConfig['reviewNoCaptcha']) && $this->_currentConfig['reviewNoCaptcha'] === '1'){
                $form->removeElement('captcha');
            }
            $form->addElement(
                'text',
                'purgen',
                array(
                    'value' => '',
                    'class' => 'hidden',
                    'id' => $this->_formId.$this->_seotoasterData['id']
                )
            );
        } else {
            $form = null;
        }
        $rating = Pagerating_Mappers_RatingMapper::getInstance()->find(
            array('pageId = ?' => $this->_seotoasterData['id'])
        );
        if (null === $rating) {
            $rating = new Pagerating_Models_Rating(array('pageId' => $this->_seotoasterData['id']));
        }
        $this->_view->rating = $rating;

        $this->_sessionHelper->pageRatingModerated = (bool)(strtolower(end($this->_options)) === 'moderated');

        $this->_view->form = $form;
        return $this->_view->render('reviews.phtml');
    }

    public function ratingAction()
    {
        if ($this->_request->isPost()) {
            $pageId = filter_var($this->_request->getParam('pageid'), FILTER_VALIDATE_INT);
            $score = filter_var($this->_request->getParam('score'), FILTER_VALIDATE_FLOAT);


            if (in_array('shopping', Tools_Plugins_Tools::getEnabledPlugins(true))) {
                if ($productMapper = Models_Mapper_ProductMapper::getInstance()->findByPageId($pageId)) {
                    Zend_Controller_Action_HelperBroker::getStaticHelper('cache')->clean(
                        false,
                        false,
                        array('prodid_' . $productMapper->getId())
                    );
                }
            }

            if (!is_nan($pageId) && !is_nan($score)) {
                $status = $this->_updateRating($pageId, $score);
                if ($status) {
                    $rating = Pagerating_Mappers_RatingMapper::getInstance()->find($pageId);
                    $this->_responseHelper->success($rating->toArray());
                }
                $this->_responseHelper->fail($this->_translator->translate('You already voted'));
            }
        }
    }

    public function reviewAction()
    {
        if ($this->_request->isPost()) {
            $form = new Pagerating_Forms_Review();
            $form->addElement(
                'text',
                'purgen',
                array(
                    'value' => '',
                    'class' => 'hidden'
                )
            );

            if(isset($this->_currentConfig['reviewNoCaptcha']) && $this->_currentConfig['reviewNoCaptcha'] === '1'){
                $form->removeElement('captcha');
            }

            if ($form->isValid($this->_request->getPost())) {
                $data = $form->getValues();
                if(!isset($data['purgen']) || $data['purgen'] != ''){
                    $this->_responseHelper->success('');
                    return true;
                }

                if (!($page = Application_Model_Mappers_PageMapper::getInstance()->find($data['pageId']))) {
                    return json_encode(array('error' => true));
                }

                $review = new Pagerating_Models_Review($data);
                $review->registerObserver(
                    new Tools_Mail_Watchdog(array('trigger' => Pagerating_MailWatchdog::TRIGGER_NEW_REVIEW))
                );
                $review->setVerified($this->_sessionHelper->pageRatingModerated ? 0 : 1);

                $result = Pagerating_Mappers_ReviewMapper::getInstance()->save($review);
                if ($result) {
                    $review->notifyObservers();
                    //processing rating
                    if (!empty($data['ratingValue'])) {
                        $rating = $this->_updateRating($data['pageId'], $data['ratingValue']);
                    }
                    if ($this->_request->isXmlHttpRequest()) {
                        echo json_encode(array('error' => false));
                        return true;
                    } else {
                        echo json_encode(array('error' => false));
                    }
                }
            } else {
                $errorMessages = $form->getMessages();
                $errorMessage = '';
                foreach ($errorMessages as $message) {
                    foreach ($message as $msg) {
                        $errorMessage .= $msg . ' ';
                    }
                }
                $this->_responseHelper->fail($errorMessage);
            }
        }
    }

    public function deleteAction()
    {
        if (Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_PLUGINS) && $this->_request->isPost()) {
            $reviewId = $this->_request->getParam('reviewId');
            $reviewMapper = Pagerating_Mappers_ReviewMapper::getInstance();
            $result = ($review = $reviewMapper->find($reviewId)) ? $reviewMapper->delete($review) : 0;

            echo json_encode(array('reviewId' => $reviewId, 'result' => $result));
        }
    }

    public function publishAction()
    {
        if (Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_PLUGINS) && $this->_request->isPost()) {
            $reviewId = $this->_request->getParam('reviewId');
            $reviewMapper = Pagerating_Mappers_ReviewMapper::getInstance();
            $result = ($review = $reviewMapper->find($reviewId)) ? $reviewMapper->save($review->setVerified(1)) : 0;

            echo json_encode(array('reviewId' => $reviewId, 'result' => $result));
        }
    }

    protected function _updateRating($pageId, $score)
    {
        $pageId = intval($pageId);
        //TODO:possible future improvements to add special case for superadmin to rate always
        if ($this->_request->getCookie('pagerating' . $pageId)) {
            return false;
        }

        $score = floatval($score);

        $rating = Pagerating_Mappers_RatingMapper::getInstance()->find(array('pageId = ?' => $pageId));
        if (is_null($rating)) {
            $rating = new Pagerating_Models_Rating();
            $rating->setPageId($pageId);
        }

        $ratingCurrent = floatval($rating->getTotalPoints());
        $ratingCount = intval($rating->getRatingCount());

        $rating->setTotalPoints(round(($ratingCurrent + $score), 2));
        $rating->setRatingCount(++$ratingCount);

        if (Pagerating_Mappers_RatingMapper::getInstance()->save($rating)) {
            $s = setcookie('pagerating' . $pageId, $score, strtotime('+1 year'), '/');
            return true;
        }
        return false;
    }

    /**
     * Show pagerating config screen
     *
     */
    public function preferencesAction() {
        if (Tools_Security_Acl::isAllowed(Tools_Security_Acl::RESOURCE_PLUGINS))
        {
            $form = new Pagerating_Forms_Configuration();

            if($this->_request->isPost()) {

                if(!$form->isValid($this->_request->getParams())) {
                    $this->_responseHelper->fail(join('<br />', $form->getMessages()));
                }

                $formData = $form->getValues();

                if(Application_Model_Mappers_ConfigMapper::getInstance()->save($formData)) {
                    $this->_responseHelper->success($this->_translator->translate('Configuration updated'));
                }
                $this->_responseHelper->fail($this->_translator->translate('Cannot update configuration.'));
            }

            $form->populate($this->_currentConfig);
            $this->_view->form        = $form;
            $this->_layout->content = $this->_view->render('preferences.phtml');
            echo $this->_layout->render();
        } else {
            throw new Exceptions_SeotoasterException('You have no rights to access this settings');
        }


    }

}

