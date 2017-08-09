<?php
/**
 * Netcontent Model
 *
 */
class Netcontent_Models_Model_Netcontent extends Application_Model_Models_Abstract {

    /**
     * User id
     * @var integer
     */
    protected $_uid        = 0;

    /**
     * Widget name
     *
     * @var string
     */
	protected $_widgetName = '';

    /**
     * Widget content
     *
     * @var string
     */
	protected $_content    = '';

    /**
     * Shows that widget = static container right now
     *
     * @var bool
     */
    protected $_p2p        = false;

    /**
     * Date of modification
     *
     * @var string
     */
	protected $_modifyDate = '';

    /**
     * Show that update of content is needed
     *
     * @var null
     */
	protected $_update     = false;

	/**
	 * Publish status
	 *
	 * @var bool
	 */
	protected $_publish = null;


	public function getUid () {
		return $this->_uid;
	}

	public function setUid ($uid) {
		$this->_uid = $uid;
		return $this;
	}

	public function getWidgetName () {
		return $this->_widgetName;
	}

	public function setWidgetName ($widgetName) {
		$this->_widgetName = $widgetName;
		return $this;
	}

	public function getContent () {
		return $this->_content;
	}

	public function setContent ($content) {
		$this->_content = $content;
		return $this;
	}

	public function getP2p () {
		return $this->_p2p;
	}

	public function setP2p ($p2p) {
		$this->_p2p = $p2p;
		return $this;
	}

	public function getModifyDate () {
		return $this->_modifyDate;
	}

	public function setModifyDate ($modifyDate) {
		$this->_modifyDate = $modifyDate;
		return $this;
	}

	public function getUpdate() {
		return $this->_update;
	}

	public function setUpdate($update) {
		$this->_update = $update;
		return $this;
	}

	public function getPublish() {
		return $this->_publish;
	}

	public function setPublish($publish) {
		$this->_publish = $publish;
		return $this;
	}
}

