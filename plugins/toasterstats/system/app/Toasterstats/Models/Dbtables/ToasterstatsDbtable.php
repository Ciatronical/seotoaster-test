<?php

class Toasterstats_Models_Dbtables_ToasterstatsDbtable extends Zend_Db_Table_Abstract {


	const CART_STATUS_SHIPPED       = 'shipped';
    const CART_STATUS_DELIVERED     = 'delivered';
    const CART_STATUS_COMPLETED     = 'completed';
    const QUOTE_GATEWAY             = 'Quote';
    
	protected $_shoppingCartSession = 'shopping_cart_session';
    protected $_shoppingQuote = 'shopping_quote';
    protected $_user = 'user';
    protected $_shoppingCustomerAddress = 'shopping_customer_address';
    protected $_shoppingProduct = 'shopping_product';
    protected $_shoppingCartSessionContent='shopping_cart_session_content';
    protected $_page = 'page';
    protected $_billingAddressId = 'billing_address_id';
    protected $_shoppingListState = 'shopping_list_state';
    
    public function selectAllSales() {
		$where = '( '.$this->getAdapter()->quoteInto('status = ?', self::CART_STATUS_COMPLETED);
        $where .= ' OR ' . $this->getAdapter()->quoteInto('status = ?', self::CART_STATUS_DELIVERED);
        $where .= ' OR ' . $this->getAdapter()->quoteInto('status = ?', self::CART_STATUS_SHIPPED);
        $where .= ') AND scp.cart_id IS NULL';
        $select = $this->getAdapter()->select()->from(array('scs' => $this->_shoppingCartSession))
            ->joinLeft(array('scp' => 'shopping_recurring_payment'), 'scp.cart_id=scs.id', array())
            ->where($where);
        return $this->getAdapter()->fetchAll($select);
        
	}
    
    public function selectAllNewSales() {
		$where = $this->getAdapter()->quoteInto('status = ?', 'new');
        $select = $this->getAdapter()->select()->from($this->_shoppingCartSession)->where($where);
        return $this->getAdapter()->fetchAll($select);
    }

    public function selectAllSalesCountNotDependentFromQuote() {
        $where = $this->getAdapter()->quoteInto('status <> ?', Models_Model_CartSession::CART_STATUS_CANCELED);
        $where .= ' AND '. $this->getAdapter()->quoteInto('status <> ?', Models_Model_CartSession::CART_STATUS_PROCESSING);
        $where .= ' AND '. $this->getAdapter()->quoteInto('status <> ?', Models_Model_CartSession::CART_STATUS_PENDING);
        $where .= ' AND scp.cart_id IS NULL';
        $select = $this->getAdapter()->select()
            ->from(array('scs' => $this->_shoppingCartSession), array('status', 'StatusCount' => 'count(status)'))
            ->joinLeft(array('scp' => 'shopping_recurring_payment'), 'scp.cart_id=scs.id', array())
            ->where($where)->group('status')->order('StatusCount');
        return $this->getAdapter()->fetchAssoc($select);
    }

    public function getSalesByStatusExcludeOrIncludeQuoteGateway($status, $quoteExclude = false){
        $where = $this->getAdapter()->quoteInto('status = ?', $status);
        if($quoteExclude){
            $where .= ' AND '. $this->getAdapter()->quoteInto('gateway <> ?', self::QUOTE_GATEWAY);
        }else{
            $where .= ' AND '. $this->getAdapter()->quoteInto('gateway = ?', self::QUOTE_GATEWAY);
        }
        $where .= ' AND scp.cart_id IS NULL';
        $select = $this->getAdapter()->select()->from(array('scs' => $this->_shoppingCartSession), array('status', 'StatusCount' => 'count(status)'))
            ->joinLeft(array('scp' => 'shopping_recurring_payment'), 'scp.cart_id=scs.id', array())
            ->where($where)->group('status')->order('StatusCount');
        return $this->getAdapter()->fetchAssoc($select);
    }
    
    public function selectSalesFromPeriod($period) {
        $where = '((created_at '.$period.' and status="completed" and gateway<>"Quote")';
        $where .= ' OR( created_at '.$period.' AND gateway<>"Quote" AND ' . $this->getAdapter()->quoteInto('status = ?', self::CART_STATUS_DELIVERED). ')' ;
        $where .= ' OR( created_at '.$period.' AND gateway<>"Quote" AND ' . $this->getAdapter()->quoteInto('status = ?', self::CART_STATUS_SHIPPED). '))';
        $where .= ' AND scp.cart_id IS NULL';
        $select = $this->getAdapter()->select()->from(array('scs' => $this->_shoppingCartSession))
            ->joinLeft(array('scp' => 'shopping_recurring_payment'), 'scp.cart_id=scs.id', array())
            ->where($where);
        return $this->getAdapter()->fetchAll($select);
        
    }
    
    public function selectAllMoney() {
        $where = '('.$this->getAdapter()->quoteInto('status = ?', self::CART_STATUS_COMPLETED);
        $where .= ' OR ' . $this->getAdapter()->quoteInto('status = ?', self::CART_STATUS_DELIVERED);
        $where .= ' OR ' . $this->getAdapter()->quoteInto('status = ?', self::CART_STATUS_SHIPPED);
        $where .= ') AND scp.cart_id IS NULL';
        $select = $this->getAdapter()->select()->from(array('scs' => $this->_shoppingCartSession), array('SUM(total) as count'))
            ->joinLeft(array('scp' => 'shopping_recurring_payment'), 'scp.cart_id=scs.id', array())
            ->where($where);
        return $this->getAdapter()->fetchAll($select);
               
    }
    
    public function selectAllMoneyFromPeriod($period) {
        $where = '((created_at '.$period.' and status="completed")';
        $where .= ' OR( created_at '.$period.' AND ' . $this->getAdapter()->quoteInto('status = ?', self::CART_STATUS_DELIVERED).')';
        $where .= ' OR( created_at '.$period.' AND ' . $this->getAdapter()->quoteInto('status = ?', self::CART_STATUS_SHIPPED).'))';
        $where .= ' AND scp.cart_id IS NULL';
        $select = $this->getAdapter()->select()->from(array('scs' => $this->_shoppingCartSession), array('SUM(total) as count'))
            ->joinLeft(array('scp' => 'shopping_recurring_payment'), 'scp.cart_id=scs.id', array())
            ->where($where);
        return $this->getAdapter()->fetchAll($select);
               
    }
    
    public function selectAllQuotes() {
        $where = $this->getAdapter()->quoteInto('q.status = ?', 'sold');
        $select = $this->getAdapter()->select()->from(array('s'=>$this->_shoppingCartSession), array('SUM(total) as count'))->join(array('q' => $this->_shoppingQuote),
                    's.id = q.cart_id', array('created_at'))->where($where)->group('q.created_at');

        return $this->getAdapter()->fetchAll($select);

    }

    /**
     * Find amount for all quotes in period of time
     *
     * @param string $period query time period
     * @param int $includeTax with or without product tax
     * @param int $includeShipping with or without shipping
     * @return array
     */
    public function selectAllQuotesAmountFromPeriod($period, $includeTax = 0, $includeShipping = 0)
    {

        $sumQuery = 'SUM(s.sub_total)';

        if ($includeTax && $includeShipping) {
            $sumQuery = 'SUM(s.total)';
        }

        if ($includeTax && !$includeShipping) {
            $sumQuery = 'SUM(s.total)-SUM(s.shipping_tax)-SUM(s.shipping_price)';
        }

        if (!$includeTax && $includeShipping) {
            $sumQuery = 'SUM(s.sub_total) + SUM(s.shipping_price)';
        }

        $where = 'q.created_at ' . $period . ' and q.status="sold"';
        $select = $this->getAdapter()->select()->from(array('s' => $this->_shoppingCartSession),
            array('count' => new Zend_Db_Expr($sumQuery)))->join(array('q' => $this->_shoppingQuote),
            's.id = q.cart_id', array('created_at'))->where($where)->group('q.created_at');

        return $this->getAdapter()->fetchAll($select);
    }

    public function selectQuotesFromPeriod($period) {
        $where = 'created_at '.$period.' ';
        $select = $this->getAdapter()->select()->from($this->_shoppingQuote, array('COUNT(*) as count'))->where($where);
        return $this->getAdapter()->fetchAll($select);
               
    }
    
    public function selectQuotesFromPeriodWithoutStatus($period) {
        $where = 'created_at '.$period.'';
        $select = $this->getAdapter()->select()->from($this->_shoppingQuote)->where($where);
        return $this->getAdapter()->fetchAll($select);
               
    }
    
    public function selectLastOrder() {
        $where = '('.$this->getAdapter()->quoteInto('status = ?', self::CART_STATUS_COMPLETED);
        $where .= ' OR ' . $this->getAdapter()->quoteInto('status = ?', self::CART_STATUS_DELIVERED);
        $where .= ' OR ' . $this->getAdapter()->quoteInto('status = ?', self::CART_STATUS_SHIPPED).')';
        $where .= ' AND scp.cart_id IS NULL';
        $select = $this->getAdapter()->select()->from(array('scs' => $this->_shoppingCartSession))
            ->joinLeft(array('scp' => 'shopping_recurring_payment'), 'scp.cart_id=scs.id', array())
            ->where($where)->order('created_at DESC');
        return $this->getAdapter()->fetchAll($select);
               
    }
    
    public function selectAverageTotalOrder() {
        $where = '('.$this->getAdapter()->quoteInto('status = ?', self::CART_STATUS_COMPLETED);
        $where .= ' OR ' . $this->getAdapter()->quoteInto('status = ?', self::CART_STATUS_DELIVERED);
        $where .= ' OR ' . $this->getAdapter()->quoteInto('status = ?', self::CART_STATUS_SHIPPED).')';
        $where .= ' AND scp.cart_id IS NULL';
        $select = $this->getAdapter()->select()->from(array('scs' => $this->_shoppingCartSession), array('avg(total) as count'))
            ->joinLeft(array('scp' => 'shopping_recurring_payment'), 'scp.cart_id=scs.id', array())
            ->where($where);
        return $this->getAdapter()->fetchAll($select);
               
    }
    
    public function selectAverageOrdersFromPeriod($period) {
        $where = '((created_at '.$period.' and status="completed")';
        $where .= ' OR (created_at '.$period.' AND ' . $this->getAdapter()->quoteInto('status = ?', self::CART_STATUS_DELIVERED).')';
        $where .= ' OR (created_at '.$period.' AND ' . $this->getAdapter()->quoteInto('status = ?', self::CART_STATUS_SHIPPED).'))';
        $where .= ' AND scp.cart_id IS NULL';
        $select = $this->getAdapter()->select()->from(array('scs' => $this->_shoppingCartSession), array('avg(total) as count'))
            ->joinLeft(array('scp' => 'shopping_recurring_payment'), 'scp.cart_id=scs.id', array())
            ->where($where);
        return $this->getAdapter()->fetchAll($select);
               
    }

    /**
     * Find sales amount in period of time
     *
     * @param string $period query time period
     * @param int $includeTax with or without product tax
     * @param int $includeShipping with or without shipping
     * @return array
     */
    public function selectAmountFromPeriod($period, $includeTax = 0, $includeShipping = 0)
    {
        $sumQuery = 'SUM(scs.sub_total)';

        if ($includeTax && $includeShipping) {
            $sumQuery = 'SUM(scs.total)';
        }

        if ($includeTax && !$includeShipping) {
            $sumQuery = 'SUM(scs.total)-SUM(scs.shipping_tax)-SUM(scs.shipping_price)';
        }

        if (!$includeTax && $includeShipping) {
            $sumQuery = 'SUM(scs.sub_total) + SUM(scs.shipping_price)';
        }

        $where = '((created_at ' . $period . ' and status="completed" and gateway<>"Quote")';
        $where .= ' OR( created_at ' . $period . ' AND gateway<>"Quote" AND ' . $this->getAdapter()->quoteInto('status = ?',
                self::CART_STATUS_DELIVERED) . ')';
        $where .= ' OR( created_at ' . $period . ' AND gateway<>"Quote" AND ' . $this->getAdapter()->quoteInto('status = ?',
                self::CART_STATUS_SHIPPED) . '))';
        $where .= ' AND scp.cart_id IS NULL';
        $select = $this->getAdapter()->select()->from(array('scs' => $this->_shoppingCartSession),
            array('count' => new Zend_Db_Expr($sumQuery), 'created_at'))
            ->joinLeft(array('scp' => 'shopping_recurring_payment'), 'scp.cart_id=scs.id', array())
            ->where($where)->group('created_at');

        return $this->getAdapter()->fetchAll($select);

    }
    public function salesamountByProductProduct($period, $includeTax = 0){
        $taxPrice = 'scsc.price';
        if ($includeTax) {
            $taxPrice = 'scsc.tax_price';
        }
        $where = $this->getAdapter()->quoteInto('scs.status IN (?)', array(
            Models_Model_CartSession::CART_STATUS_COMPLETED,
            Models_Model_CartSession::CART_STATUS_DELIVERED,
            Models_Model_CartSession::CART_STATUS_SHIPPED
        ));
        $where .= ' AND ' . $this->getAdapter()->quoteInto('scs.created_at ?', new Zend_Db_Expr($period));
        $where .= ' AND srp.cart_id IS NULL';

        $select = $this->getAdapter()->select()->from(array('scsc' => 'shopping_cart_session_content'),
            array('sp.name AS Product Name','sb.name AS Brand Name','sp.sku AS SKU' , 'Units Sold' => 'scsc.qty', 'Amount' => $taxPrice, 'Amount with Tax' => 'scsc.tax_price'))
            ->joinLeft(array('sp' => 'shopping_product'), 'scsc.product_id=sp.id', array())
            ->joinLeft(array('sb' => 'shopping_brands'), 'sb.id=sp.brand_id', array())
            ->joinLeft(array('scs' => 'shopping_cart_session'), 'scs.id=scsc.cart_id', array())
            ->joinLeft(array('srp' => 'shopping_recurring_payment'), 'srp.cart_id=scsc.cart_id', array())
            ->where($where);

        return $this->getAdapter()->fetchAll($select);
    }

    
    /**
     * Find sales per product in period of time
     *
     * @param string $period query time period
     * @param int $includeTax with or without product tax
     * @return array
     */
    public function salesamountByProduct($period, $includeTax = 0)
    {
        $taxPrice = 'scsc.price';
        if ($includeTax) {
            $taxPrice = 'scsc.tax_price';
        }
        $where = $this->getAdapter()->quoteInto('scs.status IN (?)', array(
            Models_Model_CartSession::CART_STATUS_COMPLETED,
            Models_Model_CartSession::CART_STATUS_DELIVERED,
            Models_Model_CartSession::CART_STATUS_SHIPPED
        ));
        $where .= ' AND ' . $this->getAdapter()->quoteInto('scs.created_at ?', new Zend_Db_Expr($period));
        $where .= ' AND srp.cart_id IS NULL';
        $select = $this->getAdapter()->select()->from(array('scsc' => 'shopping_cart_session_content'),
            array('sp.name', 'sp.id', 'count' => 'scsc.qty', 'tax_price' => $taxPrice))
            ->joinLeft(array('sp' => 'shopping_product'), 'scsc.product_id=sp.id', array())
            ->joinLeft(array('scs' => 'shopping_cart_session'), 'scs.id=scsc.cart_id', array())
            ->joinLeft(array('srp' => 'shopping_recurring_payment'), 'srp.cart_id=scsc.cart_id', array())
            ->where($where);

        return $this->getAdapter()->fetchAll($select);
    }

    /**
     * Find sales per brand in period of time
     *
     * @param string $period query time period
     * @param int $includeTax with or without product tax
     * @return array
     */
    public function salesamountByBrand($period, $includeTax = 0)
    {

        $taxPrice = 'scsc.price';
        if ($includeTax) {
            $taxPrice = 'scsc.tax_price';
        }
        $where = $this->getAdapter()->quoteInto('scs.status IN (?)', array(
            Models_Model_CartSession::CART_STATUS_COMPLETED,
            Models_Model_CartSession::CART_STATUS_DELIVERED,
            Models_Model_CartSession::CART_STATUS_SHIPPED
        ));
        $where .= ' AND ' . $this->getAdapter()->quoteInto('scs.created_at ?', new Zend_Db_Expr($period));
        $where .= ' AND srp.cart_id IS NULL';
        $select = $this->getAdapter()->select()->from(array('scsc' => 'shopping_cart_session_content'),
            array('sb.name', 'sp.id', 'count' => 'scsc.qty', 'tax_price' => $taxPrice))
            ->joinLeft(array('sp' => 'shopping_product'), 'scsc.product_id=sp.id', array())
            ->joinLeft(array('sb' => 'shopping_brands'), 'sb.id=sp.brand_id', array())
            ->joinLeft(array('scs' => 'shopping_cart_session'), 'scs.id=scsc.cart_id', array())
            ->joinLeft(array('srp' => 'shopping_recurring_payment'), 'srp.cart_id=scsc.cart_id', array())
            ->where($where);

        return $this->getAdapter()->fetchAll($select);
    }

    /**
     *
     * Find sales per tag in period of time
     *
     * @param string $period query time period
     * @param int $includeTax with or without product tax
     * @return array
     */
    public function salesamountByTag($period, $includeTax = 0)
    {

        $taxPrice = 'scsc.price';
        if ($includeTax) {
            $taxPrice = 'scsc.tax_price';
        }
        $where = $this->getAdapter()->quoteInto('scs.status IN (?)', array(
            Models_Model_CartSession::CART_STATUS_COMPLETED,
            Models_Model_CartSession::CART_STATUS_DELIVERED,
            Models_Model_CartSession::CART_STATUS_SHIPPED
        ));
        $where .= ' AND ' . $this->getAdapter()->quoteInto('scs.created_at ?', new Zend_Db_Expr($period));
        $where .= ' AND srp.cart_id IS NULL ';
        $where .= ' AND sp.id IS NOT NULL';
        $select = $this->getAdapter()->select()->from(array('scsc' => 'shopping_cart_session_content'),
            array('st.name', 'sp.id', 'count' => 'scsc.qty', 'tax_price' => $taxPrice))
            ->joinLeft(array('sp' => 'shopping_product'), 'scsc.product_id=sp.id', array())
            ->joinLeft(array('spht' => 'shopping_product_has_tag'), 'spht.product_id=sp.id', array())
            ->joinLeft(array('st' => 'shopping_tags'), 'st.id=spht.tag_id', array())
            ->joinLeft(array('scs' => 'shopping_cart_session'), 'scs.id=scsc.cart_id', array())
            ->joinLeft(array('srp' => 'shopping_recurring_payment'), 'srp.cart_id=scsc.cart_id', array())
            ->where($where);

        return $this->getAdapter()->fetchAll($select);
    }

    public function salesamountByTagTag($period, $includeTax = 0)
    {

        $taxPrice = 'scsc.price';

        $where = $this->getAdapter()->quoteInto('scs.status IN (?)', array(
            Models_Model_CartSession::CART_STATUS_COMPLETED,
            Models_Model_CartSession::CART_STATUS_DELIVERED,
            Models_Model_CartSession::CART_STATUS_SHIPPED
        ));
        $where .= ' AND ' . $this->getAdapter()->quoteInto('scs.created_at ?', new Zend_Db_Expr($period));
        $where .= ' AND srp.cart_id IS NULL ';
        $where .= ' AND sp.id IS NOT NULL';
        $select = $this->getAdapter()->select()->from(array('scsc' => 'shopping_cart_session_content'),
            array('st.name', 'sp.id', 'count' => 'scsc.qty','sp.sku', 'tax_price' => $taxPrice, 'tax' => 'scsc.tax_price'))
            ->joinLeft(array('sp' => 'shopping_product'), 'scsc.product_id=sp.id', array())
            ->joinLeft(array('spht' => 'shopping_product_has_tag'), 'spht.product_id=sp.id', array())
            ->joinLeft(array('st' => 'shopping_tags'), 'st.id=spht.tag_id', array())
            ->joinLeft(array('scs' => 'shopping_cart_session'), 'scs.id=scsc.cart_id', array())
            ->joinLeft(array('srp' => 'shopping_recurring_payment'), 'srp.cart_id=scsc.cart_id', array())
            ->where($where);

        return $this->getAdapter()->fetchAll($select);
    }

    /**
     *
     * Find sales per customer in period of time
     *
     * @param string $period query time period
     * @param int $includeTax with or without product tax
     * @param int $includeShipping with or without shipping
     * @return array
     */
    public function salesamountByCustomer($period, $includeTax = 0, $includeShipping = 0)
    {

        $where = $this->getAdapter()->quoteInto('scs.status IN (?)', array(
            Models_Model_CartSession::CART_STATUS_COMPLETED,
            Models_Model_CartSession::CART_STATUS_DELIVERED,
            Models_Model_CartSession::CART_STATUS_SHIPPED
        ));
        $where .= ' AND ' . $this->getAdapter()->quoteInto('scs.created_at ?', new Zend_Db_Expr($period));
        $where .= ' AND srp.cart_id IS NULL';

        $sumQuery = 'SUM(scs.sub_total)';

        if ($includeTax && $includeShipping) {
            $sumQuery = 'SUM(scs.total)';
        }

        if ($includeTax && !$includeShipping) {
            $sumQuery = 'SUM(scs.total)-SUM(scs.shipping_tax)-SUM(scs.shipping_price)';
        }

        if (!$includeTax && $includeShipping) {
            $sumQuery = 'SUM(scs.sub_total) + SUM(scs.shipping_price)';
        }


        $select = $this->getAdapter()->select()->from(array('scs' => 'shopping_cart_session'),
            array('count' => new Zend_Db_Expr($sumQuery), 'name' => 'u.full_name'))
            ->join(array('u' => 'user'), 'u.id=scs.user_id', array())
            ->joinLeft(array('srp' => 'shopping_recurring_payment'), 'srp.cart_id=scs.id', array())
            ->where($where)->group('u.id');

        return $this->getAdapter()->fetchAll($select);
    }

    /**
     * Find sales amount for quotes in period of time
     *
     * @param string $period query time period
     * @param int $includeTax with or without product tax
     * @param int $includeShipping with or without shipping
     * @return array
     */
    public function salesamountByTypeSalesQuotes($period, $includeTax = 0, $includeShipping = 0)
    {

        $sumQuery = 'SUM(sub_total)';

        if ($includeTax && $includeShipping) {
            $sumQuery = 'SUM(total)';
        }

        if ($includeTax && !$includeShipping) {
            $sumQuery = 'SUM(total)-SUM(shipping_tax)-SUM(shipping_price)';
        }

        if (!$includeTax && $includeShipping) {
            $sumQuery = 'SUM(sub_total) + SUM(shipping_price)';
        }


        $where = 'created_at ' . $period . ' and status="completed" and gateway="Quote"';
        $where .= ' OR created_at ' . $period . ' AND gateway="Quote" AND ' . $this->getAdapter()->quoteInto('status = ?',
                self::CART_STATUS_DELIVERED);
        $where .= ' OR created_at ' . $period . ' AND gateway="Quote" AND ' . $this->getAdapter()->quoteInto('status = ?',
                self::CART_STATUS_SHIPPED);
        $select = $this->getAdapter()->select()->from($this->_shoppingCartSession,
            array('count' => new Zend_Db_Expr($sumQuery)))->where($where);

        return $this->getAdapter()->fetchAll($select);
    }

    /**
     * Find sales amount in period of time
     *
     * @param string $period query time period
     * @param int $includeTax with or without product tax
     * @param int $includeShipping with or without shipping
     * @return array
     */
    public function salesamountByTypeSalesCart($period, $includeTax = 0, $includeShipping = 0)
    {
        $sumQuery = 'SUM(sub_total)';

        if ($includeTax && $includeShipping) {
            $sumQuery = 'SUM(total)';
        }

        if ($includeTax && !$includeShipping) {
            $sumQuery = 'SUM(total)-SUM(shipping_tax)-SUM(shipping_price)';
        }

        if (!$includeTax && $includeShipping) {
            $sumQuery = 'SUM(sub_total) + SUM(shipping_price)';
        }

        $where = '((created_at ' . $period . ' and status="completed" and gateway<>"Quote")';
        $where .= ' OR( created_at ' . $period . ' AND gateway<>"Quote" AND ' . $this->getAdapter()->quoteInto('status = ?',
                self::CART_STATUS_DELIVERED) . ')';
        $where .= ' OR( created_at ' . $period . ' AND gateway<>"Quote" AND ' . $this->getAdapter()->quoteInto('status = ?',
                self::CART_STATUS_SHIPPED) . '))';
        $where .= ' AND scp.cart_id IS NULL';
        $select = $this->getAdapter()->select()->from(array('scs' => $this->_shoppingCartSession),
            array('count' => new Zend_Db_Expr($sumQuery)))
            ->joinLeft(array('scp' => 'shopping_recurring_payment'), 'scp.cart_id=scs.id', array())
            ->where($where);

        return $this->getAdapter()->fetchAll($select);
    }

    /**
     * Find amount of customers for countries in period of time
     *
     * @param string $period query time period
     * @return array
     */
    public function salesCustomersCountriesByPeriod($period)
    {

        $where = $this->getAdapter()->quoteInto('scs.status IN (?)', array(
            Models_Model_CartSession::CART_STATUS_COMPLETED,
            Models_Model_CartSession::CART_STATUS_DELIVERED,
            Models_Model_CartSession::CART_STATUS_SHIPPED
        ));
        $where .= ' AND ' . $this->getAdapter()->quoteInto('scs.created_at ?', new Zend_Db_Expr($period));
        $where .= ' AND srp.cart_id IS NULL';

        $select = $this->getAdapter()->select()->from(array('sca' => 'shopping_customer_address'),
            array('sca.country', 'scs.total'))
            ->join(array('scs' => 'shopping_cart_session'), 'scs.billing_address_id=sca.id', array())
            ->joinLeft(array('srp' => 'shopping_recurring_payment'), 'srp.cart_id=scs.id', array())
            ->where($where);

        return $this->getAdapter()->fetchAll($select);
    }

    /**
     * Find amount of customers for states in period of time
     *
     * @param string $period query time period
     * @return array
     */
    public function salesCustomersStatesByPeriod($period)
    {
        $where = $this->getAdapter()->quoteInto('scs.status IN (?)', array(
            Models_Model_CartSession::CART_STATUS_COMPLETED,
            Models_Model_CartSession::CART_STATUS_DELIVERED,
            Models_Model_CartSession::CART_STATUS_SHIPPED
        ));
        $where .= ' AND ' . $this->getAdapter()->quoteInto('scs.created_at ?', new Zend_Db_Expr($period));
        $where .= ' AND srp.cart_id IS NULL';

        $select = $this->getAdapter()->select()->from(array('sca' => 'shopping_customer_address'),
            array('sca.country', 'scs.total', 'sls.state'))
            ->join(array('scs' => 'shopping_cart_session'), 'scs.billing_address_id=sca.id', array())
            ->join(array('sls' => 'shopping_list_state'), 'sls.id=sca.state', array())
            ->joinLeft(array('srp' => 'shopping_recurring_payment'), 'srp.cart_id=scs.id', array())
            ->where($where);

        return $this->getAdapter()->fetchAll($select);
    }
    
    public function lastNewCustomers($limit=5) {
        $where = '((s.status="completed") or (s.status="pending") or (s.status="delivered") or (s.status="shipped")) AND scp.cart_id IS NULL';
        $select = $this->getAdapter()->select()->from(array('u'=>$this->_user), 'u.full_name')->join(array('s' => $this->_shoppingCartSession),
                    'u.id = s.user_id', array('u.reg_date', 's.total'))->join(array('sk' => $this->_shoppingCustomerAddress),
                    'sk.id = s.billing_address_id')
            ->joinLeft(array('scp' => 'shopping_recurring_payment'), 'scp.cart_id=s.id', array())
            ->where($where)->group('u.id')->order('u.reg_date DESC')->limit($limit,0);
        
        return $this->getAdapter()->fetchAll($select);
    }
    
    public function lastNewCustomersOrders($limit=5) {
        $where = '((s.status="completed") or (s.status="pending" AND s.gateway<>"Quote") or (s.status="delivered") or (s.status="shipped")) AND scp.cart_id IS NULL';
        $select = $this->getAdapter()->select()->from(array('u'=>$this->_user), 'u.full_name')->join(array('s' => $this->_shoppingCartSession),
                    'u.id = s.user_id', array('u.reg_date', 's.total', 's.created_at'))->join(array('sk' => $this->_shoppingCustomerAddress),
                    'sk.id = s.billing_address_id')
            ->joinLeft(array('scp' => 'shopping_recurring_payment'), 'scp.cart_id=s.id', array())
            ->where($where)->order('s.created_at DESC')->limit($limit,0);
        return $this->getAdapter()->fetchAll($select);
    }

    public function mostSaledProducts($period, $limit=5) {
        $where = '((scs.created_at '.$period.' and scs.status="completed") or (scs.created_at '.$period.' and scs.status="delivered") or (scs.created_at '.$period.' and scs.status="shipped")) AND scp.cart_id IS NULL';
        $select = $this->getAdapter()->select()->from(array('scsc'=>$this->_shoppingCartSessionContent),  array('sum(scsc.qty) as count', 'scsc.tax_price'))->joinRight(array('sp' => $this->_shoppingProduct),
                    'sp.id = scsc.product_id', array('sp.name', 'sp.id'))->joinRight(array('scs' => $this->_shoppingCartSession),
                    'scs.id = scsc.cart_id')->joinRight(array('p' => $this->_page),
                    'p.id = sp.page_id')
            ->joinLeft(array('scp' => 'shopping_recurring_payment'), 'scp.cart_id=scs.id', array())
            ->where($where)->group('sp.name')->order('count DESC')->limit($limit,0);
        
        return $this->getAdapter()->fetchAll($select); 
    }
    
    public function mostSaledProductsAllTime($limit=5) {
        $where = '((scs.status="completed") or (scs.status="delivered") or (scs.status="shipped") AND scp.cart_id IS NULL';
        $select = $this->getAdapter()->select()->from(array('scsc'=>$this->_shoppingCartSessionContent),  array('sum(scsc.qty) as count', 'scsc.tax_price'))->joinRight(array('sp' => $this->_shoppingProduct),
                    'sp.id = scsc.product_id', array('sp.name', 'sp.id'))->joinRight(array('scs' => $this->_shoppingCartSession),
                    'scs.id = scsc.cart_id')->joinRight(array('p' => $this->_page),
                    'p.id = sp.page_id')
            ->joinLeft(array('scp' => 'shopping_recurring_payment'), 'scp.cart_id=scs.id', array())
            ->where($where)->group('sp.name')->order('count DESC')->limit($limit,0);
        
        return $this->getAdapter()->fetchAll($select); 
    }
    
    public function quantityOfStaticMenuPages(){
        $pageDbTable = new Application_Model_DbTable_Page();
        $where = $pageDbTable->getAdapter()->quoteInto("show_in_menu = '?'", Application_Model_Models_Page::IN_STATICMENU);
        $select = $pageDbTable->getAdapter()->select()->from('page', array("id"=>"COUNT(*)"))->where($where);
        return $pageDbTable->getAdapter()->fetchAll($select); 
    }
    
    public function quantityOfNomenuPages(){
        $pageDbTable = new Application_Model_DbTable_Page();
        $where = sprintf("show_in_menu = '%s' AND parent_id = %d", Application_Model_Models_Page::IN_NOMENU, Application_Model_Models_Page::IDCATEGORY_DEFAULT);
        $select = $pageDbTable->getAdapter()->select()->from('page', array("id"=>"COUNT(*)"))->where($where);
        return $pageDbTable->getAdapter()->fetchAll($select);
    }
    
    public function quantityOfProducts(){
        $productDbTable = new Models_DbTable_Product();
        $select = $productDbTable->getAdapter()->select()->from('shopping_product', array("id"=>"COUNT(*)"));
        return $productDbTable->getAdapter()->fetchAll($select);
    }

    /**
     * Get sales amount per coupon for period of time
     *
     * @param string $period period of time
     * @param bool $withTax flag for prices with or without tax
     * @return array
     */
    public function getSalesAmountByCoupon($period, $withTax = true)
    {
        $where = $this->getAdapter()->quoteInto('scs.status IN (?)', array(
            Models_Model_CartSession::CART_STATUS_COMPLETED,
            Models_Model_CartSession::CART_STATUS_DELIVERED,
            Models_Model_CartSession::CART_STATUS_SHIPPED
        ));
        if ($withTax) {
            $taxAmountField = 'total';
        } else {
            $taxAmountField = 'sub_total';
        }
        $where .= ' AND ' . $this->getAdapter()->quoteInto('scs.created_at ?', new Zend_Db_Expr($period));
        $select = $this->getAdapter()->select()->from(array('scoup' => 'shopping_coupon_sales'),
            array(
                'name' => 'coupon_code',
                'count' => new Zend_Db_Expr('COUNT(coupon_code)'),
                'tax_price' => 'scs.' . $taxAmountField . ''
            ))
            ->joinLeft(array('scs' => 'shopping_cart_session'), 'scs.id=scoup.cart_id', null)
            ->where($where)
            ->group('scoup.coupon_code');

        return $this->getAdapter()->fetchAll($select);
    }

    /**
     * Get sales count per coupon for period of time
     *
     * @param string $period period of time
     * @return array
     */
    public function salesCountByCoupon($period)
    {
        $where = $this->getAdapter()->quoteInto('scs.status IN (?)', array(
            Models_Model_CartSession::CART_STATUS_COMPLETED,
            Models_Model_CartSession::CART_STATUS_DELIVERED,
            Models_Model_CartSession::CART_STATUS_SHIPPED
        ));
        $where .= ' AND ' . $this->getAdapter()->quoteInto('scs.created_at ?', new Zend_Db_Expr($period));
        $select = $this->getAdapter()->select()->from(array('scoup' => 'shopping_coupon_sales'),
            array(
                'name' => 'coupon_code',
                'count' => new Zend_Db_Expr('COUNT(coupon_code)')
            ))
            ->joinLeft(array('scs' => 'shopping_cart_session'), 'scs.id=scoup.cart_id', null)
            ->where($where)
            ->group('scoup.coupon_code');

        return $this->getAdapter()->fetchAll($select);
    }

    /**
     * Get amount of emails sent by trigger names
     *
     * @param array $triggerNames (new, completed, shipped, delivered etc...)
     * @param string $period period of time
     * @return array
     */
    public function findEmailAmountByTriggerNames(array $triggerNames, $period)
    {
        $where = $this->getAdapter()->quoteInto('pcq.cartStatus IN (?)', $triggerNames);
        $where .= ' AND ' . $this->getAdapter()->quoteInto('pcq.sentAt ?', new Zend_Db_Expr($period));
        $select = $this->getAdapter()->select()->from(array('pcq' => 'plugin_cartstatusemail_queue'),
            array('pcq.cartStatus', 'count' => new Zend_Db_Expr('COUNT(*)')))
            ->where($where)->group('pcq.cartStatus');

        return $this->getAdapter()->fetchPairs($select);

    }

    /**
     * Get restored cart sales info
     *
     * @param string $period period of time
     * @param int $withTax flag for prices with or without tax
     * @param string $triggerName (new, completed, shipped, delivered etc...)
     * @param int $includeShipping with or without shipping
     * @return array
     */
    public function getRestoredCartInfo($period, $triggerName, $withTax = 0, $includeShipping = 0)
    {
        $where = $this->getAdapter()->quoteInto('scs.created_at ?', new Zend_Db_Expr($period));
        $where .= ' AND srp.cart_id IS NULL';
        $where .= ' AND '.$this->getAdapter()->quoteInto('pcr.cart_status = ?', $triggerName);

        $sumQuery = '`scs`.`sub_total`';

        if ($withTax && $includeShipping) {
            $sumQuery = '`scs`.`total`';
        }

        if ($withTax && !$includeShipping) {
            $sumQuery = '`scs`.`total`-`scs`.`shipping_tax`-`scs`.`shipping_price`';
        }

        if (!$withTax && $includeShipping) {
            $sumQuery = '`scs`.`sub_total` + `scs`.`shipping_price`';
        }

        //Find total for restored carts with states (shipped, delivered, completed)
        $restoredTotal = new Zend_Db_Expr('SUM(IF((`scs`.`status`="' . Models_Model_CartSession::CART_STATUS_COMPLETED . '"
        OR `scs`.`status`="' . Models_Model_CartSession::CART_STATUS_SHIPPED . '"
        OR `scs`.`status`="' . Models_Model_CartSession::CART_STATUS_DELIVERED . '") AND `pcr`.`cart_id` IS NOT NULL, '.$sumQuery.', 0))');

        //Count of carts restored for states (shipped, delivered, completed)
        $restoredCount = new Zend_Db_Expr('SUM(IF((`scs`.`status`="' . Models_Model_CartSession::CART_STATUS_COMPLETED . '"
        OR `scs`.`status`="' . Models_Model_CartSession::CART_STATUS_SHIPPED . '"
        OR `scs`.`status`="' . Models_Model_CartSession::CART_STATUS_DELIVERED . '") AND `pcr`.`cart_id` IS NOT NULL, 1, 0))');


        $select = $this->getAdapter()->select()->from(array('scs' => 'shopping_cart_session'),
            array(
                'restoredTotal' => $restoredTotal,
                'countPaid' => $restoredCount,
                'total' => new Zend_Db_Expr('SUM(IF(`pcr`.`cart_id` IS NOT NULL, '.$sumQuery.', 0))'),
                'countAll' => new Zend_Db_Expr('COUNT(`pcr`.`cart_id`)')
            ))
            ->joinLeft(array('pcr' => 'plugin_cartstatusemail_restored_cart'), 'pcr.cart_id=scs.id', array())
            ->joinLeft(array('srp' => 'shopping_recurring_payment'), 'srp.cart_id=scs.id', array())
            ->where($where);

        return $this->getAdapter()->fetchRow($select);
    }

    /**
     * Get users quantity for each type
     */
    public function getUserQuantity()
    {
        $select = $this->getAdapter()->select()->from(array('u' => 'user'),
            array('role_id', 'userQuantity' => new Zend_Db_Expr('COUNT(`role_id`)')))->group('role_id');

        return $this->getAdapter()->fetchAssoc($select);
    }

    public function getProducts(){
        $productDbTable = new Models_DbTable_Product();
        $select = $productDbTable->getAdapter()->select()->from('shopping_product');
        $result = $productDbTable->getAdapter()->fetchAll($select);
        return $result;
    }
    
}
