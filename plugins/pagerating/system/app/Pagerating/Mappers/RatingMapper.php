<?php


class Pagerating_Mappers_RatingMapper extends Application_Model_Mappers_Abstract {

	protected $_dbTable = 'Pagerating_DbTables_RatingDbTable';

	protected $_model   = 'Pagerating_Models_Rating';

	public function save($rating) {
		if(!$rating instanceof $this->_model) {
			throw new Exceptions_SeotoasterException('Given parameter should be ' . $this->_model . ' instance');
		}

		$data = $rating->toArray();
		unset($data['id']);

		if (null !== $this->find(array('pageId' => $data['pageId']))) {
			unset($data['pageId']);
			return $this->getDbTable()->update($data, array('pageId = ?' => $rating->getPageId() ));
		} else {
			return $this->getDbTable()->insert($data);
		}
	}

    public function getProductRatingByProdId($productId) {
        $table = $this->getDbTable();

        $select = $table->select()
            ->setIntegrityCheck(false)
            ->from(array('sh_p'=>'shopping_product'), array(''))
            ->joinLeft(array('pl_r'=>'plugin_pagerating_rating'), 'sh_p.page_id=pl_r.pageId', array('pl_r.pageId', 'pl_r.ratingValue', 'pl_r.ratingCount',	'pl_r.totalPoints'))
            ->where($table->getAdapter()->quoteInto('sh_p.id = ?', $productId));

        $row = $table->fetchRow($select);

        return new $this->_model($row->toArray());
    }
}

