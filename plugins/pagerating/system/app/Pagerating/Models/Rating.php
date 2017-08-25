<?php
class Pagerating_Models_Rating extends Application_Model_Models_Abstract {

	protected $_pageId;

	protected $_ratingValue;

	protected $_ratingCount;

	protected $_totalPoints;


	public function setPageId($pageId) {
		$this->_pageId = $pageId;
        return $this;
	}

	public function getPageId() {
		return $this->_pageId;
	}

	public function setRatingCount($ratingCount) {
		$this->_ratingCount = $ratingCount;
        return $this;
	}

	public function getRatingCount() {
		return $this->_ratingCount;
	}

	public function getRatingValue() {
		return $this->_ratingCount ? round(($this->_totalPoints / $this->_ratingCount), 2) : 0 ;
	}

	public function setTotalPoints($totalPoints) {
		$this->_totalPoints = $totalPoints;
        return $this;
	}

	public function getTotalPoints() {
		return $this->_totalPoints;
	}
}

