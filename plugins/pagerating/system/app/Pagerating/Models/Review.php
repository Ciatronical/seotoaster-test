<?php


class Pagerating_Models_Review extends Application_Model_Models_Abstract {

	protected $_pageId = '';

	protected $_name;

	protected $_author;

	protected $_email;

	protected $_description;

	protected $_ratingValue = 0;

	protected $_datePublished;

	protected $_verified;


	public function setAuthor($author) {
		$this->_author = $author;
        return $this;
	}

	public function getAuthor() {
		return $this->_author;
	}

	public function setDatePublished($datePublished) {
		$this->_datePublished = $datePublished;
        return $this;
	}

	public function getDatePublished() {
		return $this->_datePublished;
	}

	public function setDescription($description) {
		$this->_description = $description;
        return $this;
	}

	public function getDescription() {
		return $this->_description;
	}

	public function setEmail($email) {
		$this->_email = $email;
        return $this;
	}

	public function getEmail() {
		return $this->_email;
	}

	public function setName($name) {
		$this->_name = $name;
        return $this;
	}

	public function getName() {
		return $this->_name;
	}

	public function setPageId($pageId) {
		$this->_pageId = $pageId;
        return $this;
	}

	public function getPageId() {
		return $this->_pageId;
	}

	public function setRatingValue($ratingValue) {
		$this->_ratingValue = $ratingValue;
        return $this;
	}

	public function getRatingValue() {
		return $this->_ratingValue;
	}

	public function setVerified($verified) {
		$this->_verified = $verified;
        return $this;
	}

	public function getVerified() {
		return $this->_verified;
	}
}

