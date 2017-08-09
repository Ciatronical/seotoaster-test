<?php

class Toasterstats extends Tools_Plugins_Abstract {
	
    const RESOURCE_TOASTER_STATS = 'toaster_stats';
    const ROLE_SALESPERSON = 'sales person';
    const SAMPLE_FILE_IMPORT_DIR = 'toasterstats/web/sample/';
    const SAMPLE_FILE_IMPORT_NAME = 'export.csv';
    /**
     * sales by coupon
     */
    const TYPE_COUPON_SALES = 'coupon';

    /**
     * remarketing stats flag
     */
    const TYPE_EMAIL_REMARKETING = 'remarketing';
    
    private $_periodArray = array('days', 'week', 'month', 'year', 'totalPeriod');
    private $_typeOfPiarChartGraf = array('product', 'brand', 'tag', 'customer', 'type', self::TYPE_COUPON_SALES, self::TYPE_EMAIL_REMARKETING);
    private $_remarketingTriggerNames = array(
        'new',
        'pending',
        'processing',
        'completed',
        'shipped',
        'delivered',
        'refunded',
        'new_quote',
        'quote_sent',
        'lost_opportunity'
    );
    
	public function  __construct($options, $seotoasterData) {
		parent::__construct($options, $seotoasterData);
		$this->_dbTable = new Zend_Db_Table();
        $this->_view->addScriptPath(__DIR__ . '/system/views/');
	}

    public function beforeController(){
        $acl = Zend_Registry::get('acl');
        if(!$acl->has(self::RESOURCE_TOASTER_STATS)) {
            $acl->addResource(new Zend_Acl_Resource(self::RESOURCE_TOASTER_STATS));
        }
        $acl->allow(self::ROLE_SALESPERSON, self::RESOURCE_TOASTER_STATS);
        $acl->allow(Tools_Security_Acl::ROLE_ADMIN, self::RESOURCE_TOASTER_STATS);
        $acl->allow(Tools_Security_Acl::ROLE_SUPERADMIN, self::RESOURCE_TOASTER_STATS);
        Zend_Registry::set('acl', $acl);
    }
    
    
    public function _makeOptionGraph(){
        if(Tools_Security_Acl::isAllowed(self::RESOURCE_TOASTER_STATS)){
            if(isset($this->_options[1]) && isset($this->_options[2]) && isset($this->_options[3]) && isset($this->_options[4])){
                $currencyHandler = Zend_Registry::get('Zend_Currency');
                $currencySymbol = $currencyHandler->getSymbol();
                $toasterstatsDbTable = new Toasterstats_Models_Dbtables_ToasterstatsDbtable();
                $this->_view->currencySymbol = $currencySymbol;
                if($this->_options[1] == 'geo' && isset($this->_options[2])){
                    if($this->_options[2] == 'map'){
                        if(in_array($this->_options[3], $this->_periodArray)){
                            if(!isset($this->_options[4])){
                                $period = $this->_createTimePeriod(1, $this->_options[3]);
                            }
                            else{
                                $period = $this->_createTimePeriod($this->_options[4], $this->_options[3]);
                            }
                        }
                        $mapData = $this->_geoMapHandler($period);
                        $this->_view->typeOfMap = $mapData['typeofMap'];
                        $this->_view->mapData = $mapData['mapData'];
                        isset($this->_options[5]) ? $this->_view->width = $this->_options[5] : $this->_view->width = 240;
                        isset($this->_options[6]) ? $this->_view->height = $this->_options[6] : $this->_view->height = 150;
                        return $this->_view->render('geochart.phtml');
                    }
                    
                }
                if($this->_options[1] == 'linechart'){
                   if($this->_options[2] == 'count' || $this->_options[2] == 'amount' || $this->_options[2] == 'averageamount'){
                   $this->_view->typeSales = $this->_options[3];
                   $this->_view->typeOfGrafic = $this->_options[2];
                   if($this->_options[2] == 'count'){
                        $this->_view->typeSalesCount = $this->_options[3];
                   }
                   if($this->_options[2] == 'averageamount'){
                        $this->_view->typeSalesAverageAmount = $this->_options[3];
                   }
                   $totalSales = array('0'=>array('created_at'=>''));
                   $totalQuotes = array('0'=>array('created_at'=>''));
                   if(in_array($this->_options[4], $this->_periodArray)){
                       if(!isset($this->_options[5])){
                            $period = $this->_createTimePeriod(1, $this->_options[4]);
                            $datepickerPeriod = $this->_createTimePeriodForDatepicker(1, $this->_options[4]);
                       }
                       else{
                            $period = $this->_createTimePeriod($this->_options[5], $this->_options[4]);
                            $datepickerPeriod = $this->_createTimePeriodForDatepicker($this->_options[5], $this->_options[4]);
                       }
                       $datePikerPeriod = explode('|', $datepickerPeriod);
                       $datePikerPeriod[0] = date("d-M-Y", strtotime($datePikerPeriod[0]));
                       $datePikerPeriod[1] = date("d-M-Y", strtotime($datePikerPeriod[1]));
                       $this->_view->datepickerPeriod = $datePikerPeriod;
                       $rightPeriodForGraf = $this->_rigthGrafhPeriod($this->_options[4]);
                       if(isset($this->_options[4])){
                            $date = $this->_checkPeriod($this->_options[4], $this->_options[5]);
                            
                       }else{
                            $date = $this->_checkPeriod($this->_options[4], 1);
                       }
                       $this->_view->timePeriod = $this->_options[5].$this->_options[4];
                       $data =  $this->_createGraficsData($this->_options[2], $this->_options[3], $period, $rightPeriodForGraf, $date, 0, 1);
                       if(count($data)>1){
                           $data = array_reverse($data);
                       }
                       $this->_view->data = $data;
                       isset($this->_options[6]) ? $this->_view->width = $this->_options[6] : $this->_view->width = 240;
                       isset($this->_options[7]) ? $this->_view->height = $this->_options[7] : $this->_view->height = 150;
                       return $this->_view->render('linechart.phtml');
                   }
                 }
                }
                if($this->_options[1] == 'piechart'){
                    if($this->_options[2] == 'amount'){
                        $this->_view->typeSales = $this->_options[3];
                        $this->_view->typeOfGrafic = $this->_options[2];
                    }
                    if(in_array($this->_options[4], $this->_periodArray)){
                       if(!isset($this->_options[5])){
                            $period = $this->_createTimePeriod(1, $this->_options[4]);
                       }
                       else{
                            $period = $this->_createTimePeriod($this->_options[5], $this->_options[4]);
                       }
                    }
                    if(in_array($this->_options[3], $this->_typeOfPiarChartGraf)){
                        $totalProductsFromPeriod = $this->_changePirchartAmountGrafic($this->_options[3], $period, 0, 1);
                    }
                    $this->_view->dataArray = $totalProductsFromPeriod;
                    isset($this->_options[6]) ? $this->_view->width = $this->_options[6] : $this->_view->width = 240;
                    isset($this->_options[7]) ? $this->_view->height = $this->_options[7] : $this->_view->height = 150;
                    if(in_array('table', $this->_options)) {
                        $this->_view->period = $period;
                        $this->_view->table = true;
                        if($this->_options[3] === 'product'){
                            return $this->_view->render('piechartTableProduct.phtml');
                        }
                        if($this->_options[3] === 'type'){
                            return $this->_view->render('piechartTableType.phtml');
                        }
                        if($this->_options[3] === 'customer'){
                            return $this->_view->render('piechartTableCustomer.phtml');
                        }
                        if($this->_options[3] === 'brand'){
                            return $this->_view->render('piechartTableBrand.phtml');
                        }
                    }else {
                        return $this->_view->render('piechart.phtml');
                    }
                }
                if($this->_options[1] == 'columnchart'){
                   if($this->_options[2] == 'count' || $this->_options[2] == 'amount'){
                        $this->_view->typeSales = $this->_options[3];
                        $this->_view->typeOfGrafic = $this->_options[2];
                        if($this->_options[2] == 'count'){
                           $this->_view->typeSalesCount = $this->_options[3];
                   }
                   if($this->_options[2] == 'amount'){
                        $this->_view->typeSalesAmount = $this->_options[3];
                   }
                   $totalSales = array('0'=>array('created_at'=>''));
                   $totalQuotes = array('0'=>array('created_at'=>''));
                   if(in_array($this->_options[4], $this->_periodArray)){
                       if(!isset($this->_options[5])){
                            $period = $this->_createTimePeriod(1, $this->_options[4]);
                       }
                       else{
                            $period = $this->_createTimePeriod($this->_options[5], $this->_options[4]);
                       }
                       $rightPeriodForGraf = $this->_rigthGrafhPeriod($this->_options[4]);
                       if(isset($this->_options[4])){
                            $date = $this->_checkPeriod($this->_options[4], $this->_options[5]);
                            
                       }else{
                            $date = $this->_checkPeriod($this->_options[4], 1);
                       }
                       isset($this->_options[6]) ? $this->_view->width = $this->_options[6] : $this->_view->width = 240;
                       isset($this->_options[7]) ? $this->_view->height = $this->_options[7] : $this->_view->height = 150;
                       $this->_view->columnName = ucfirst($this->_options[3]);
                       $this->_view->visualizationName= 'visualizationcolumnCount'.ucfirst($this->_options[3]).'s';
                       if ($this->_options[3] === self::TYPE_COUPON_SALES) {
                           $totalCoupons = $this->_createCountCouponGraf($period);
                           $this->_view->data = $totalCoupons;
                           if(in_array('table', $this->_options)){
                               $this->_view->period = $period;
                               $this->_view->table = true;
                               return $this->_view->render('columnchartTagTableCoupon.phtml');
                           }else {
                               return $this->_view->render('columnchartTag.phtml');
                           }
                       }
                       if ($this->_options[3] === self::TYPE_EMAIL_REMARKETING) {

                           $allTriggers = array_search('all', $this->_options);
                           if ($allTriggers) {
                              $triggerNames = $this->_remarketingTriggerNames;
                           } else {
                              $triggerNames = array($this->_options[8]);
                           }
                           $totalEmailsSentByTrigger = $this->_createCountEmailRemarketingGraf($period, $triggerNames);
                           $this->_view->data = $totalEmailsSentByTrigger;
                           if(in_array('table', $this->_options)){
                               $this->_view->period = $period;
                               $this->_view->table = true;
                               $this->_view->trigger = $triggerNames;
                               return $this->_view->render('columnchartTagTableRemarketing.phtml');
                           }else {
                               return $this->_view->render('columnchartTag.phtml');
                           }
                       }
                       if($this->_options[3] == 'tag'){
                            $totalTags = $this->_createCountTagGraf($period);
                           arsort($totalTags,SORT_REGULAR);
                            $this->_view->data = $totalTags;
                           if(in_array('table', $this->_options)) {
                               $this->_view->period = $period;
                               $this->_view->table = true;
                               return $this->_view->render('columnchartTagTableTag.phtml');
                           }else {
                               return $this->_view->render('columnchartTag.phtml');
                           }
                       }
                       $data =  $this->_createGraficsData($this->_options[2], $this->_options[3], $period, $rightPeriodForGraf, $date, 0, 1);
                       if(count($data)>1){
                           $data = array_reverse($data);
                       }
                       $this->_view->data = $data;
                       return $this->_view->render('columnchart.phtml');
                  }
               }         
            }
        }

      }
    }

    private function _createTimePeriod($period, $periodUnits){
		$now = date('Y-m-d H:i:s');
        if($period == '1'){
            switch ($periodUnits) {
                case 'days':
                    $endDate = date('Y-m-d');
                    break;
                case 'week':
                    $endDate = date('Y-m-d', strtotime("Last Sunday"));
                    break;
                case 'month':
                    $endDate = date('Y-m-d', strtotime("first day of this month"));
                    break;
                case 'year':
                    $endDate = date('Y-m-d', strtotime("first day of January"));
                    break;
                default:
                    $endDate = $now;
                    break;
            }
       }else{
            $period = $period - 1;
            switch ($periodUnits) {
                case 'days':
                    $endDate = date('Y-m-d', strtotime('-'.$period. 'day'));
                    break;
                case 'week':
                    $endDate = date('Y-m-d', strtotime('this week -'.$period.' week'));
                    break;
                case 'month':
                    $endDate = date('Y-m-d', strtotime('first day of this month -'.$period. 'month'));
                    break;
                case 'year':
                    $interval = strtotime('1/1 this year -'.$period. 'year');
                    $endDate = date('Y-m-d', $interval);
                	break;
                default:
                    $endDate = $now;
                    break;
            }
        }
        return "between '".$endDate.' 00:00:00'."' AND '".$now."'";
	}
    
        private function _geoMapHandler($period){
             $toasterstatsDbTable = new Toasterstats_Models_Dbtables_ToasterstatsDbtable();
             $mapData = $toasterstatsDbTable->salesCustomersCountriesByPeriod($period);
             $typeOfMap = 'World';

             if(!empty($mapData)){
                  $usaOrWorld = $this->_checkCountry($mapData);
                  $mapResult = array();
                  if($usaOrWorld == '1'){
                     $typeOfMap  = 'USA';
                     $mapData = $toasterstatsDbTable->salesCustomersStatesByPeriod($period);
                     foreach($mapData as $information){
                        if(!isset($mapResult[$information['country'].'-'.$information['state']])){
                            $mapResult[$information['country'].'-'.$information['state']] = 1;
                        }
                        else{
                            $mapResult[$information['country'].'-'.$information['state']] = $mapResult[$information['country'].'-'.$information['state']] + 1;
                        }

                     }
                  }else{
                     $typeOfMap = 'World';
                     foreach($mapData as $information){
                        if(!isset($mapResult[$information['country']])){
                            $mapResult[$information['country']] = 1;
                        }
                        else{
                            $mapResult[$information['country']] = $mapResult[$information['country']] + 1;
                        }

                     }
                  }
             }
             if(!isset($mapResult)){
                 $mapResult = array('Nothing' =>0);
             }
             return array('typeofMap' => $typeOfMap, 'mapData' => $mapResult);
        }

        private function _checkCountry($mapData){
            foreach($mapData as $data){
               if($data['country'] != 'US'){
                   return 0;
               }

            }
            return 1;

        }

    private function _createTimePeriodForDatepicker($period, $periodUnits){
		$now = date('Y-m-d');
        if($period == '1'){
            switch ($periodUnits) {
                case 'days':
                    $endDate = date('Y-m-d');
                    break;
                case 'week':
                    $endDate = date('Y-m-d', strtotime("Last Sunday"));
                    break;
                case 'month':
                    $endDate = date('Y-m-d', strtotime("first day of this month"));
                    break;
                case 'year':
                    $endDate = date('Y-m-d', strtotime("first day of January"));
                    break;
                default:
                    $endDate = $now;
                    break;
            }

        }else{
            $period = $period - 1;
            switch ($periodUnits) {
                case 'days':
                    $endDate = date('Y-m-d', strtotime('-'.$period. 'day'));
                    break;
                case 'week':
                    $endDate = date('Y-m-d', strtotime('this week -'.$period.' week'));
                    break;
                case 'month':
                    $endDate = date('Y-m-d', strtotime('first day of this month -'.$period. 'month'));
                    break;
                case 'year':
                    $interval = strtotime('1/1 this year -'.$period. 'year');
                    $endDate = date('Y-m-d', $interval);
                	break;
                default:
                    $endDate = $now;
                    break;
            }
        }
        return $endDate.'|'.$now;
	}

    private function _rigthGrafhPeriod($periodUnits){
        switch ($periodUnits) {
                case 'days':
                    $rightPeriod = 10;
                    break;
                case 'week':
                    $rightPeriod = 10;
                    break;
                case 'month':
                    $rightPeriod = 7;
                    break;
                case 'year':
                    $rightPeriod = 4;
                    break;
            }
        return $rightPeriod;

    }
    
    private function _checkPeriod($periodUnits, $period){
        switch ($periodUnits) {
                case 'days':
                    if($period == '1'){
                        $period = 2;
                    }
                    for($i = 0; $i<$period;$i++){
                         $date[date('Y-m-d', strtotime('-'.$i.' day'))] = array('sales' => 0, 'quotes' => 0);
                    }
                    break;
                case 'week':
                    $period = $period*7;
                    for($i = 0; $i<$period;$i++){
                        $date[date('Y-m-d',  strtotime('-'.$i.' day'))] = array('sales' => 0, 'quotes' => 0);
                    }
                    break;
                case 'month':
                    for($i = 0; $i<$period;$i++){
                        $date[date('Y-m', strtotime(date("Y-m-1") . " -$i month"))] = array('sales' => 0, 'quotes' => 0);
                    }
                    break;
                case 'year':
                    if($period == '1'){
                        $date[date('Y', strtotime("first day of January"))] = array('sales' => 0, 'quotes' => 0);
                    }
                    else{
                        for($i = 0; $i<$period;$i++){
                            $date[date('Y', strtotime('-'.$period.' year'))] = array('sales' => 0, 'quotes' => 0);
                        }
                    }
                    break;
            }
        return $date;

    }
    
   private function _createGraficsData($typeGrafic, $typeColumn, $period, $rightPeriodForGraf, $date, $usingTax = 0, $usingShipping = 0){
        $toasterstatsDbTable = new Toasterstats_Models_Dbtables_ToasterstatsDbtable();
        if(preg_match('/\|/', $typeColumn)){
            $typeOfGraf  = explode('|', $typeColumn);
             if($typeGrafic == 'count'){
                 foreach($typeOfGraf as $type){
                     if($type== 'sales'){
                          $totalSalesPeriod = $toasterstatsDbTable->selectSalesFromPeriod($period);
                          if(isset($totalSalesPeriod) && $totalSalesPeriod != null && !empty($totalSalesPeriod)){
                                $totalSales = $totalSalesPeriod;
                                foreach($totalSales as $key => $value){
                                    $temDate = substr($value['created_at'], 0, $rightPeriodForGraf);
                                    $date[$temDate]['sales']++;
                                }
                           }
                     }
                     if($type== 'quotes'){
                          $totalQuotesPeriod = $toasterstatsDbTable->selectQuotesFromPeriodWithoutStatus($period);
                          if(isset($totalQuotesPeriod) && $totalQuotesPeriod != null && !empty($totalQuotesPeriod)){
                                $totalQuotes = $totalQuotesPeriod;
                                foreach($totalQuotes as $key => $value){
                                    $temDate = substr($value['created_at'], 0, $rightPeriodForGraf);
                                    $date[$temDate]['quotes']++;
                                }
                         }
                    }
                 }
             }
             if($typeGrafic == 'amount'){
                foreach($typeOfGraf as $type){
                    if($type== 'sales'){
                        $totalSalesPeriod = $toasterstatsDbTable->selectAmountFromPeriod($period, 1, 1);
                        if(isset($totalSalesPeriod) && $totalSalesPeriod != null && !empty($totalSalesPeriod)){
                             $totalSales = $totalSalesPeriod;
                             foreach($totalSales as $key => $value){
                                 $temDate = substr($value['created_at'], 0, $rightPeriodForGraf);
                                 $date[$temDate]['sales'] = $date[$temDate]['sales']+$value['count'];
                             }
                        }
                    }
                    if($type== 'quotes'){
                          $totalQuotesPeriod = $toasterstatsDbTable->selectAllQuotesAmountFromPeriod($period, 1, 1);
                          if(isset($totalQuotesPeriod) && $totalQuotesPeriod != null && !empty($totalQuotesPeriod)){
                                $totalQuotes = $totalQuotesPeriod;
                                foreach($totalQuotes as $key => $value){
                                    $temDate = substr($value['created_at'], 0, $rightPeriodForGraf);
                                    $date[$temDate]['quotes'] = $date[$temDate]['quotes']+$value['count'];
                                }
                         }
                    }
                }
            }
            if($typeGrafic == 'averageamount'){
              foreach($typeOfGraf as $type){
                   if($type == 'sales'){
                        $totalSalesPeriod = $toasterstatsDbTable->selectAmountFromPeriod($period, $usingTax, $usingShipping);
                        if(isset($totalSalesPeriod) && $totalSalesPeriod != null && !empty($totalSalesPeriod)){
                            $totalSales = $totalSalesPeriod;
                            $date1 = $date;
                            foreach($totalSales as $key => $value){
                                $temDate = substr($value['created_at'], 0, $rightPeriodForGraf);
                                $date1[$temDate]['sales'] = $date1[$temDate]['sales']+$value['count'];
                            }
                            $date2 = $date;
                            foreach($totalSales as $key => $value){
                                $temDate = substr($value['created_at'], 0, $rightPeriodForGraf);
                                $date2[$temDate]['sales']++;
                            }
                            foreach($date1 as $key=>$salesInformation){
                                if($date2[$key]['sales'] != 0){
                                    $date[$key]['sales'] = $date1[$key]['sales']/$date2[$key]['sales'];
                                }
                            }
                        }


                }
              if($type == 'quotes'){
                   $totalQuotesPeriod = $toasterstatsDbTable->selectAllQuotesAmountFromPeriod($period, $usingTax, $usingShipping);
                   if(isset($totalQuotesPeriod) && $totalQuotesPeriod != null && !empty($totalQuotesPeriod)){
                       $totalQuotes = $totalQuotesPeriod;
                       $date1 = $date;
                       foreach($totalQuotes as $key => $value){
                           $temDate = substr($value['created_at'], 0, $rightPeriodForGraf);
                           $date1[$temDate]['quotes'] = $date1[$temDate]['quotes']+$value['count'];
                       }
                       $date2 = $date;
                       foreach($totalQuotes as $key => $value){
                           $temDate = substr($value['created_at'], 0, $rightPeriodForGraf);
                           $date2[$temDate]['quotes']++;
                       }

                       foreach($date1 as $key=>$salesInformation){
                            if($date2[$key]['quotes'] != 0){
                                $date[$key]['quotes'] = $date1[$key]['quotes']/$date2[$key]['quotes'];
                            }
                       }
                  }
              }
          }

         }
        }
         else{
             if($typeGrafic == 'count'){
                 if($typeColumn== 'sales'){
                    $totalSalesPeriod = $toasterstatsDbTable->selectSalesFromPeriod($period);
                    if(isset($totalSalesPeriod) && $totalSalesPeriod != null && !empty($totalSalesPeriod)){
                        $totalSales = $totalSalesPeriod;
                        foreach($totalSales as $key => $value){
                            $temDate = substr($value['created_at'], 0, $rightPeriodForGraf);
                            $date[$temDate]['sales']++;
                        }
                    }
                 }
                 if($typeColumn == 'quotes'){
                       $totalQuotesPeriod = $toasterstatsDbTable->selectQuotesFromPeriodWithoutStatus($period);
                       if(isset($totalQuotesPeriod) && $totalQuotesPeriod != null && !empty($totalQuotesPeriod)){
                            $totalQuotes = $totalQuotesPeriod;
                            foreach($totalQuotes as $key => $value){
                                $temDate = substr($value['created_at'], 0, $rightPeriodForGraf);
                                 $date[$temDate]['quotes']++;
                            }
                       }
                 }
          }
          if($typeGrafic == 'amount'){
              if($typeColumn== 'sales'){
                   $totalSalesPeriod = $toasterstatsDbTable->selectAmountFromPeriod($period, 1, 1);
                   if(isset($totalSalesPeriod) && $totalSalesPeriod != null && !empty($totalSalesPeriod)){
                         $totalSales = $totalSalesPeriod;
                         foreach($totalSales as $key => $value){
                             $temDate = substr($value['created_at'], 0, $rightPeriodForGraf);
                             $date[$temDate]['sales'] = $date[$temDate]['sales']+$value['count'];
                         }
                   }
              }
              if($typeColumn == 'quotes'){
                   $totalQuotesPeriod = $toasterstatsDbTable->selectAllQuotesAmountFromPeriod($period, 1, 1);
                   if(isset($totalQuotesPeriod) && $totalQuotesPeriod != null && !empty($totalQuotesPeriod)){
                       $totalQuotes = $totalQuotesPeriod;
                       foreach($totalQuotes as $key => $value){
                           $temDate = substr($value['created_at'], 0, $rightPeriodForGraf);
                           $date[$temDate]['quotes'] = $date[$temDate]['quotes']+$value['count'];
                       }
                   }
              }
          }
          if($typeGrafic == 'averageamount'){
              if($typeColumn== 'sales'){
                   $totalSalesPeriod = $toasterstatsDbTable->selectAmountFromPeriod($period, $usingTax, $usingShipping);
                   if(isset($totalSalesPeriod) && $totalSalesPeriod != null && !empty($totalSalesPeriod)){
                         $totalSales = $totalSalesPeriod;
                         $date1 = $date;
                         foreach($totalSales as $key => $value){
                             $temDate = substr($value['created_at'], 0, $rightPeriodForGraf);
                             $date1[$temDate]['sales'] = $date1[$temDate]['sales']+$value['count'];
                         }
                         $date2 = $date;
                         foreach($totalSales as $key => $value){
                            $temDate = substr($value['created_at'], 0, $rightPeriodForGraf);
                            $date2[$temDate]['sales']++;
                         }
                         foreach($date1 as $key=>$salesInformation){
                            if($date2[$key]['sales'] != 0){
                                $date[$key]['sales'] = $date1[$key]['sales']/$date2[$key]['sales'];
                            }
                         }
                 }

              }
              if($typeColumn == 'quotes'){
                   $totalQuotesPeriod = $toasterstatsDbTable->selectAllQuotesAmountFromPeriod($period, $usingTax, $usingShipping);
                   if(isset($totalQuotesPeriod) && $totalQuotesPeriod != null && !empty($totalQuotesPeriod)){
                       $totalQuotes = $totalQuotesPeriod;
                       $date1 = $date;
                       foreach($totalQuotes as $key => $value){
                           $temDate = substr($value['created_at'], 0, $rightPeriodForGraf);
                           $date1[$temDate]['quotes'] = $date1[$temDate]['quotes']+$value['count'];
                       }
                       $date2 = $date;
                       foreach($totalQuotes as $key => $value){
                            $temDate = substr($value['created_at'], 0, $rightPeriodForGraf);
                            $date2[$temDate]['quotes']++;
                       }
                       foreach($date1 as $key=>$salesInformation){
                            if($date2[$key]['quotes'] != 0){
                                $date[$key]['quotes'] = $date1[$key]['quotes']/$date2[$key]['quotes'];
                            }
                       }
                   }
              }
          }
      }
      return $date;
    }

    private function _changePirchartAmountGraficProduct($typeOfPierchartGrafic, $period, $includeTax = 0, $includeShipping = 0){
        $totalProducts = array($this->_translator->translate('Nothing Found')=>'1');
        $toasterstatsDbTable = new Toasterstats_Models_Dbtables_ToasterstatsDbtable();
        if($typeOfPierchartGrafic) {
            $productAmountByPeriod = $toasterstatsDbTable->salesamountByProductProduct($period, $includeTax);
            if(!empty($productAmountByPeriod)) {
                $totalProducts = $this->_calculatePierchartsResultProduct($productAmountByPeriod);
            }
        }
        return  $totalProducts;
    }

    
    private function _changePirchartAmountGrafic($typeOfPierchartGrafic, $period, $includeTax = 0, $includeShipping = 0){

        $totalProducts = array($this->_translator->translate('Nothing Found')=>'1');
        $toasterstatsDbTable = new Toasterstats_Models_Dbtables_ToasterstatsDbtable();
        if($typeOfPierchartGrafic == 'product'){
            $productAmountByPeriod = $toasterstatsDbTable->salesamountByProduct($period, $includeTax);
            if(!empty($productAmountByPeriod)){
                $totalProducts = $this->_calculatePierchartsResult($productAmountByPeriod);
            }
        }
        if($typeOfPierchartGrafic == 'brand'){
            $productAmountByPeriod = $toasterstatsDbTable->salesamountByBrand($period, $includeTax);
            if(!empty($productAmountByPeriod)){
                   $totalProducts = $this->_calculatePierchartsResult($productAmountByPeriod);
            }
        }
        if($typeOfPierchartGrafic == 'tag'){
            $productAmountByPeriod = $toasterstatsDbTable->salesamountByTag($period, $includeTax);
            if(!empty($productAmountByPeriod)){
                   $totalProducts = $this->_calculatePierchartsResult($productAmountByPeriod);
            }
        }
        if($typeOfPierchartGrafic == 'customer'){
            $totalCustomersFromPeriod = $toasterstatsDbTable->salesamountByCustomer($period, $includeTax, $includeShipping);
            if(!empty($totalCustomersFromPeriod)){
                $customerResult = array();
                foreach($totalCustomersFromPeriod as $information){
                    if(!isset($customerResult[$information['name']])){
                        $customerResult[$information['name']] = $information['count'];
                    }
                    else{
                        $customerResult[$information['name']] = $customerResult[$information['name']] + $information['count'];
                    }
                }
                $totalProducts = $customerResult;
            }
        }
        if($typeOfPierchartGrafic == 'type'){
            $totalQuotesCart = array();
            $totalQuotesFromPeriod = $toasterstatsDbTable->salesamountByTypeSalesQuotes($period, $includeTax, $includeShipping);
            if(!empty($totalQuotesFromPeriod) && $totalQuotesFromPeriod[0]['count'] != null){
                $totalQuotesCart['quote'] = $totalQuotesFromPeriod[0]['count'];
                $totalProducts = $totalQuotesCart;
            }
            $totalCartFromPeriod = $toasterstatsDbTable->salesamountByTypeSalesCart($period, $includeTax, $includeShipping);
            if(!empty($totalCartFromPeriod) && $totalCartFromPeriod[0]['count'] != null){
                $totalQuotesCart['cart'] = $totalCartFromPeriod[0]['count'];
                $totalProducts = $totalQuotesCart;
            }
        }

        if ($typeOfPierchartGrafic === self::TYPE_COUPON_SALES) {
            $productAmountByPeriod = $toasterstatsDbTable->getSalesAmountByCoupon($period, $includeTax);
            if(!empty($productAmountByPeriod)){
                $totalProducts = $this->_calculatePierchartsResult($productAmountByPeriod);
            }
        }

        if ($typeOfPierchartGrafic === self::TYPE_EMAIL_REMARKETING) {
            $abandonment = array_search('abandonment', $this->_options);
            if ($abandonment) {
                $salesAmountByTriggerName = $toasterstatsDbTable->getRestoredCartInfo($period,
                    Models_Model_CartSession::CART_STATUS_NEW, $includeTax, $includeShipping);
                if (!empty($salesAmountByTriggerName)) {
                    $currency = Zend_Registry::get('Zend_Currency');
                    if (!empty($salesAmountByTriggerName['total'])) {
                        $totalProducts = array(
                            'Abandoned cart emails sent (' . $currency->toCurrency($salesAmountByTriggerName['total'] - $salesAmountByTriggerName['restoredTotal']) . ')' => $salesAmountByTriggerName['countAll'] - $salesAmountByTriggerName['countPaid'],
                            'Recovered cart purchases (' . $currency->toCurrency($salesAmountByTriggerName['restoredTotal']) . ')' => $salesAmountByTriggerName['countPaid']
                        );
                    }
                }
            }
        }
        return $totalProducts;
    }

    private function _calculatePierchartsResultProduct($resultsArray){
        $brandsResult = array();
        $unicRes = array();
        foreach($resultsArray as $information){
            if(!isset($brandsResult[$information['Product Name']])){
                $brandsResult[$information['Product Name']] = $information['Units Sold']*$information['Amount'];
                $brandsResultTax[$information['Product Name']] = $information['Units Sold']*$information['Amount with Tax'];
                $unicRes[$information['Product Name']] = $information;
            }
            else{
                $brandsResult[$information['Product Name']] = $brandsResult[$information['Product Name']] + $information['Units Sold']*$information['Amount'];
                $brandsResultTax[$information['Product Name']] = $brandsResultTax[$information['Product Name']] + $information['Units Sold']*$information['Amount with Tax'];
                $unicRes[$information['Product Name']]['Units Sold'] += $information['Units Sold'];

            }
        }

        return array('products' => $brandsResult,'product_tax' => $brandsResultTax, 'unicData' => $unicRes);
    }
        
    private function _calculatePierchartsResult($resultsArray){
        $brandsResult = array();
        foreach($resultsArray as $information){
              if(!isset($brandsResult[$information['name']])){
                  $brandsResult[$information['name']] = $information['count']*$information['tax_price'];
              }
              else{
                  $brandsResult[$information['name']] = $brandsResult[$information['name']] + $information['count']*$information['tax_price'];
              }
         }
         return $brandsResult;
    }
          
    /**
     * Count sales by coupon code for period of time
     *
     * @param string $period period of time
     * @return array
     */
    private function _createCountCouponGraf($period)
    {
        $totalCoupons = array($this->_translator->translate('Nothing Found') => '1');
        $toasterstatsDbTable = new Toasterstats_Models_Dbtables_ToasterstatsDbtable();
        $couponsByPeriod = $toasterstatsDbTable->salesCountByCoupon($period);
        if (!empty($couponsByPeriod)) {
            $couponResult = array();
            foreach ($couponsByPeriod as $information) {
                if (!isset($couponResult[$information['name']])) {
                    $couponResult[$information['name']] = 1;
                } else {
                    $couponResult[$information['name']] = $couponResult[$information['name']] + 1;
                }
            }
            $totalCoupons = $couponResult;
        }

        return $totalCoupons;
    }
    
    /**
     * Count amount of emails sent by trigger
     *
     * @param string $period period of time
     * @param array $triggerStatuses (new, completed, shipped, delivered etc...)
     * @return array
     */
    private function _createCountEmailRemarketingGraf($period, array $triggerStatuses)
    {
        $toasterstatsDbTable = new Toasterstats_Models_Dbtables_ToasterstatsDbtable();
        $totalEmailByTrigger = array($this->_translator->translate('Nothing Found') => '1');
        $amountOfSentEmails = $toasterstatsDbTable->findEmailAmountByTriggerNames($triggerStatuses, $period);
        if (!empty($amountOfSentEmails)) {
            $totalEmailByTrigger = $amountOfSentEmails;
        }

        return $totalEmailByTrigger;
    }
    
    private function _createCountTagGraf($period){
        $totalTags = array($this->_translator->translate('Nothing Found')=>'1');
        $toasterstatsDbTable = new Toasterstats_Models_Dbtables_ToasterstatsDbtable();
        $productTagsByPeriod = $toasterstatsDbTable->salesamountByTag($period);
        if(!empty($productTagsByPeriod)){
            $tagResult = array();
            foreach($productTagsByPeriod as $information){
              if(!isset($tagResult[$information['name']])){
                  $tagResult[$information['name']] = 1;
              }
              else{
                  $tagResult[$information['name']] = $tagResult[$information['name']] + 1;
              }
            }
         $totalTags = $tagResult;

        }
        return $totalTags;

    }

    private function _createCountTagGrafTag($period){
        $totalTags = array($this->_translator->translate('Nothing Found')=>'1');
        $toasterstatsDbTable = new Toasterstats_Models_Dbtables_ToasterstatsDbtable();
        $productTagsByPeriod = $toasterstatsDbTable->salesamountByTagTag($period);
        if(!empty($productTagsByPeriod)){
            $tagResult = array();
            $tagResultData = array();
            $tagResultDataTax = array();
            foreach($productTagsByPeriod as $information){
                if(!isset($tagResult[$information['name']])){
                    $tagResult[$information['name']] = $information['count'];
                    $tagResultData[$information['name']] = $information['tax_price']*$information['count'];
                    $tagResultDataTax[$information['name']] = $information['tax']*$information['count'];
                }
                else{
                    $tagResult[$information['name']] = $tagResult[$information['name']] + $information['count'];
                    $tagResultData[$information['name']] = $tagResultData[$information['name']] + $information['tax_price']*$information['count'];
                    $tagResultDataTax[$information['name']] = $tagResultDataTax[$information['name']] + $information['tax']*$information['count'];
                }
            }
            $totalTags = $tagResult;

        }
        if(empty($productTagsByPeriod)){
            return $totalTags;
        }else {
            return array('tags' => $totalTags, 'data' => $tagResultData, 'tax' => $tagResultDataTax);
        }
    }
    
    public function periodAction(){
        $arr = array();
        $total = array();
        $pluginPath = Tools_Plugins_Tools::getPluginsPath();
        $filePath = $pluginPath . self::SAMPLE_FILE_IMPORT_DIR . self::SAMPLE_FILE_IMPORT_NAME;

        if($this->_request->getParam('date') !== null){
            $timePeriod = $this->_request->getParam('date');
            if(preg_match('/\|/',$timePeriod)) {
                $datePikerPeriod = explode('|', $timePeriod);
                $dateFromRight = date("Y-m-d", strtotime($datePikerPeriod[0]));
                $dateToRight = date("Y-m-d", strtotime($datePikerPeriod[1]));
                $period = "between '".$dateFromRight.' 00:00:00'."' AND '".$dateToRight.' 23:59:59'."'";
                if(($this->_request->getParam('type') !== null) && ($this->_request->getParam('type') == 'product' || $this->_request->getParam('type') == 'type' || $this->_request->getParam('type') == 'customer' || $this->_request->getParam('type') == 'brand')) {
                    $typeSales = $this->_request->getParam('type');

                    if($this->_request->getParam('type') == 'product') {
                        $total = $this->_changePirchartAmountGraficProduct($typeSales, $period, 0, 1);
                        if(isset($total['products'])) {
                            foreach ($total['products'] as $key => $value) {
                                foreach ($total['unicData'] as $k => $v) {
                                    if (in_array($key, $v)) {
                                        $arr[$k] = $v;
                                        $arr[$k]['Amount'] = $value;
                                        $arr[$k]['Amount with Tax'] = $total['product_tax'][$key];
                                    }
                                }
                            }
                            $colName = array_keys($arr[$key]);
                            array_unshift($arr, $colName);
                        }else{
                            foreach($total as $key => $val){
                                $arr[$key]['name'] = $key;
                                $arr[$key]['count'] = $val;
                            }
                        }
                    }else{
                        $total = $this->_changePirchartAmountGrafic($typeSales, $period, 0, 1);

                        foreach($total as $key => $val){
                            $arr[$key]['name'] = $key;
                            $arr[$key]['count'] = $val;
                        }
                    }
                    $fileWrite = fopen($filePath, 'w');
                    foreach($arr as $value){
                        fputcsv($fileWrite, $value);
                    }
                    fclose($fileWrite);
                }else{
                    if(($this->_request->getParam('type') !== null) && ($this->_request->getParam('type') == 'tag')) {
                        $total = $this->_createCountTagGrafTag($period);
                        if(isset($total['tags'])) {
                            foreach ($total['tags'] as $key => $value) {

                                if (array_key_exists($key, $total['data'])) {
                                    $arr[$key]['Tag Name'] = $key;
                                    $arr[$key]['Sales Count'] = $value;
                                    $arr[$key]['Sales Amount'] = $total['data'][$key];
                                    $arr[$key]['Sales Amount with Tax'] = $total['tax'][$key];
                                }
                            }
                            $colName = array_keys($arr[$key]);
                            array_unshift($arr, $colName);
                        }else{
                            foreach($total as $key => $val){
                                $arr[$key]['name'] = $key;
                                $arr[$key]['count'] = $val;
                            }
                        }

                        $fileWrite = fopen($filePath, 'w');
                        foreach($arr as $value){
                            fputcsv($fileWrite, $value);
                        }
                        fclose($fileWrite);

                        $response = Zend_Controller_Front::getInstance()->getResponse();
                        $response->setHeader(
                            'Content-Disposition',
                            'attachment; filename=' . self::SAMPLE_FILE_IMPORT_NAME
                        )
                            ->setHeader('Content-type', 'application/force-download');
                        readfile($filePath);
                        $response->sendResponse();
                        exit;

                    }
                    if(($this->_request->getParam('type') !== null) && ($this->_request->getParam('type') == 'coupon')) {
                        $total = $this->_createCountCouponGraf($period);
                    }
                    if(($this->_request->getParam('type') !== null) && ($this->_request->getParam('type') == 'remarketing')) {
                        $total = $this->_createCountEmailRemarketingGraf($period, $this->_remarketingTriggerNames);
                    }
                    foreach($total as $key => $val){
                        $arr[$key]['name'] = $key;
                        $arr[$key]['count'] = $val;
                    }

                    $fileWrite = fopen($filePath, 'w');
                    foreach($arr as $value){
                        fputcsv($fileWrite, $value);
                    }
                    fclose($fileWrite);
                }
            }
        }else {
            if($this->_request->getParam('paramsTag') !== null){
                $period = $this->_request->getParam('paramsTag');
                $total = $this->_createCountTagGrafTag($period);

                if(isset($total['tags'])) {
                    foreach ($total['tags'] as $key => $value) {

                        if (array_key_exists($key, $total['data'])) {
                            $arr[$key]['Tag Name'] = $key;
                            $arr[$key]['Sales Count'] = $value;
                            $arr[$key]['Sales Amount'] = $total['data'][$key];
                            $arr[$key]['Sales Amount with Tax'] = $total['tax'][$key];
                        }
                    }
                    $colName = array_keys($arr[$key]);
                    array_unshift($arr, $colName);
                }else{
                    foreach($total as $key => $val){
                        $arr[$key]['name'] = $key;
                        $arr[$key]['count'] = $val;
                    }
                }
                $fileWrite = fopen($filePath, 'w');
                foreach($arr as $value){
                    fputcsv($fileWrite, $value);
                }
                fclose($fileWrite);

                $response = Zend_Controller_Front::getInstance()->getResponse();
                $response->setHeader(
                    'Content-Disposition',
                    'attachment; filename=' . self::SAMPLE_FILE_IMPORT_NAME
                )
                    ->setHeader('Content-type', 'application/force-download');
                readfile($filePath);
                $response->sendResponse();
                exit;

            }
            if($this->_request->getParam('paramsCoupon') !== null){
                $period = $this->_request->getParam('paramsCoupon');
                $total = $this->_createCountCouponGraf($period);
            }
            if($this->_request->getParam('paramsRemarketing') !== null){
                $period = $this->_request->getParam('paramsRemarketing');
                $triggerNames = $this->_request->getParam('trigger');
                $total = $this->_createCountEmailRemarketingGraf($period, $triggerNames);
            }

            if($this->_request->getParam('paramscorechart') !== null){
                $period = $this->_request->getParam('paramscorechart');
                $typeSales = $this->_request->getParam('typesales');
                if($this->_request->getParam('typesales') === 'product') {
                    $total = $this->_changePirchartAmountGraficProduct($typeSales, $period, 0, 1);

                    if (isset($total['products'])) {
                        foreach ($total['products'] as $key => $value) {
                            foreach ($total['unicData'] as $k => $v) {
                                if (in_array($key, $v)) {
                                    $arr[$k] = $v;
                                    $arr[$k]['Amount'] = $value;
                                    $arr[$k]['Amount with Tax'] = $total['product_tax'][$key];
                                }
                            }
                        }
                        $colName = array_keys($arr[$key]);
                        array_unshift($arr, $colName);

                        $fileWrite = fopen($filePath, 'w');
                        foreach ($arr as $value) {
                            fputcsv($fileWrite, $value);
                        }
                        fclose($fileWrite);

                        $response = Zend_Controller_Front::getInstance()->getResponse();
                        $response->setHeader(
                            'Content-Disposition',
                            'attachment; filename=' . self::SAMPLE_FILE_IMPORT_NAME
                        )
                            ->setHeader('Content-type', 'application/force-download');
                        readfile($filePath);
                        $response->sendResponse();
                        exit;
                    }
                }else {
                    $total = $this->_changePirchartAmountGrafic($typeSales, $period, 0, 1);
                }
            }
            foreach($total as $key => $val){
                $arr[$key]['name'] = $key;
                $arr[$key]['count'] = $val;
            }
            $fileWrite = fopen($filePath, 'w');
            foreach($arr as $value){
                fputcsv($fileWrite, $value);
            }
            fclose($fileWrite);
        }

        $response = Zend_Controller_Front::getInstance()->getResponse();
        $response->setHeader(
            'Content-Disposition',
            'attachment; filename=' . self::SAMPLE_FILE_IMPORT_NAME
        )
            ->setHeader('Content-type', 'application/force-download');
        readfile($filePath);
        $response->sendResponse();
        exit;
    }
        
    //controls Block//

   public function _makeOptionProducts(){
       if(Tools_Security_Acl::isAllowed(self::RESOURCE_TOASTER_STATS)){
        if(isset($this->_options[1]) && isset($this->_options[2])){
            $toasterstatsDbTable = new Toasterstats_Models_Dbtables_ToasterstatsDbtable();
            $this->_view->translator = $this->_translator;
            $totalProducts = array(0=>array('name'=>$this->_translator->translate('Nothing found')));
            if($this->_options[1] == 'topsellers'){
                if($this->_options[2] == 'today'){
                    $period = $this->_createTimePeriod(1, 'days');
                    isset($this->_options[3]) ? $limit = $this->_options[3] : $limit = 5;
                    $productTop = $toasterstatsDbTable->mostSaledProducts($period, $limit);
                    if(!empty($productTop)){
                        $totalProducts = $productTop;
                    }
                    $this->_view->todayProducts = '';
                }
                else{
                    isset($this->_options[4]) ? $limit = $this->_options[4] : $limit = 5;
                    if(!isset($this->_options[2])){
                        $period = $this->_createTimePeriod(1, $this->_options[2]);
                    }
                    else{
                        $period = $this->_createTimePeriod($this->_options[3], $this->_options[2]);
                    }
                    $productTop = $toasterstatsDbTable->mostSaledProducts($period, $limit);
                    $totalProducts = $this->_prepareProducts($productTop);
                    $this->_view->productsFromPeriod = '';
                    $this->_view->limitForBestsselers = $limit;
                }
                $this->_view->totalProducts = $totalProducts;
                return $this->_view->render('products.phtml');
           }
        }
      }
   }
    
   private function _prepareProducts($productArray){
       $totalProducts = array(0=>array('name'=>$this->_translator->translate('Nothing found')));
       if(!empty($productArray)){
           $totalProducts = $productArray;
       }
       $this->_view->translator = $this->_translator;
       $this->_view->productsData = $totalProducts;
       return $this->_view->render('prepareProducts.phtml');

   }
    //controls block end//
    
   public function _makeOptionCustomer(){
        if(Tools_Security_Acl::isAllowed(self::RESOURCE_TOASTER_STATS)){
            if(isset($this->_options[1])){
                $toasterstatsDbTable = new Toasterstats_Models_Dbtables_ToasterstatsDbtable();
                $this->_view->translator = $this->_translator;
                if($this->_options[1] == 'new'){
                    isset($this->_options[2]) ? $limit = $this->_options[2] : $limit = 5;
                    $lastCustomers = $toasterstatsDbTable->lastNewCustomers($limit);
                    $customerDataArray = array();
                    if(!empty($lastCustomers)){
                        foreach($lastCustomers as $key=>$custInfo){
                            if($custInfo['state'] == null){$custInfo['state'] = 0;}
                            $state = Tools_Geo::getStateById(intval($custInfo['state']));
                            if($state == ''){
                               $customerDataArray[$key]['state'] = $state;
                            }else{
                               $customerDataArray[$key]['state'] = $state['name'];
                            }
                            $customerDataArray[$key]['fullName'] = $custInfo['full_name'];
                            $customerDataArray[$key]['city'] = $custInfo['city'];
                            $customerDataArray[$key]['total'] = $custInfo['total'];
                            $customerDataArray[$key]['userId'] = $custInfo['user_id'];
                        }
                        $this->_view->customerData = $customerDataArray;
                    }else{
                      $this->_view->noNewCusomers = $this->_translator->translate('There are any customers exist');
                    }
                    return $this->_view->render('customers.phtml');
                }
                if($this->_options[1] == 'orders'){
                     isset($this->_options[2]) ? $limit = $this->_options[2] : $limit = 5;
                     $lastCustomersOrders = $toasterstatsDbTable->lastNewCustomersOrders($limit);
                     $customerOrdersDataArray = array();
                     if(!empty($lastCustomersOrders)){
                        foreach($lastCustomersOrders as $key=>$custInfo){
                            if($custInfo['state'] == null){$custInfo['state'] = 0;}
                                $state = Tools_Geo::getStateById(intval($custInfo['state']));
                            if($state == ''){
                               $customerOrdersDataArray[$key]['state'] = $state;
                            }else{
                               $customerOrdersDataArray[$key]['state'] = $state['name'];
                            }
                            $customerOrdersDataArray[$key]['fullName'] = $custInfo['full_name'];
                            $customerOrdersDataArray[$key]['city'] = $custInfo['city'];
                            $customerOrdersDataArray[$key]['total'] = $custInfo['total'];
                            $customerOrdersDataArray[$key]['userId'] = $custInfo['user_id'];
                        }
                        $this->_view->customerOrdersData = $customerOrdersDataArray;
                    }else{
                      $this->_view->noNewCustomersOrders = $this->_translator->translate('There are any orders exist');
                    }
                     return $this->_view->render('latestCustomersOrders.phtml');
                }
            }
        }

    }
    
    public function _makeOptionSales(){
       if(Tools_Security_Acl::isAllowed(self::RESOURCE_TOASTER_STATS)){
           if(isset($this->_options[1])){
               $todaySale = 0;
               $salesFromPeriod = 0;
               $today = 0;
               $toasterstatsDbTable = new Toasterstats_Models_Dbtables_ToasterstatsDbtable();
               if($this->_options[1]== 'today'){
                    $period = $this->_createTimePeriod(1, 'days');
                    $todaySales = $toasterstatsDbTable->selectSalesFromPeriod($period);
                    if(isset($todaySales) && $todaySales != null && !empty($todaySales)){
                        $todaySale = count($todaySales);
                    }
                    $this->_view->todaySales = $todaySale;
               }
               if($this->_options[1]== 'total'){
                    $totalSales = $toasterstatsDbTable->selectAllSales();
                    if(isset($totalSales) && $totalSales != null && !empty($totalSales)){
                        $totalSale = count($totalSales);
                    }
                    $this->_view->totalSales = $totalSale;
               }
               if(in_array($this->_options[1], $this->_periodArray)){
                   if(!isset($this->_options[2])){
                       $period = $this->_createTimePeriod(1, $this->_options[1]);
                   }
                   else{
                       $period = $this->_createTimePeriod($this->_options[2], $this->_options[1]);
                   }
                   $totalSalesPeriod = $toasterstatsDbTable->selectSalesFromPeriod($period);
                   if(isset($totalSalesPeriod) && $totalSalesPeriod != null && !empty($totalSalesPeriod)){
                        $salesFromPeriod = count($totalSalesPeriod);

                   }
                   $this->_view->salesFromPeriod = $salesFromPeriod;
                }
                return $this->_view->render('sales.phtml');
           }
       }
    }

    public function _makeOptionMoney(){
        if(Tools_Security_Acl::isAllowed(self::RESOURCE_TOASTER_STATS)){
           if(isset($this->_options[1])){
               $totalMoney = 0;
               $moneyFromPeriod = 0;
               $today = 0;
               $toasterstatsDbTable = new Toasterstats_Models_Dbtables_ToasterstatsDbtable();
               if($this->_options[1]== 'today'){
                    $period = $this->_createTimePeriod(1, 'days');
                    $moneyFromToday = $toasterstatsDbTable->selectAllMoneyFromPeriod($period);
                    if(isset($moneyFromToday) && $moneyFromToday != null && $moneyFromToday[0]['count'] != null){
                        $today = $moneyFromToday[0]['count'];
                    }
                    $this->_view->moneyToday= $today;
               }
               if($this->_options[1]== 'total'){
                   $totalMoneys = $toasterstatsDbTable->selectAllMoney();
                   if(isset($totalMoneys) && $totalMoneys != null && $totalMoneys[0]['count'] != null){
                       $totalMoney =  $totalMoneys[0]['count'];
                   }
                   $this->_view->totalMoney = $totalMoney;
               }
               if(in_array($this->_options[1], $this->_periodArray)){
                   if(!isset($this->_options[2])){
                        $period = $this->_createTimePeriod(1, $this->_options[1]);
                   }
                   else{
                        $period = $this->_createTimePeriod($this->_options[2], $this->_options[1]);
                   }
                        $moneyFromPeriods = $toasterstatsDbTable->selectAllMoneyFromPeriod($period);
                        if(isset($moneyFromPeriods) && $moneyFromPeriods !=null && $moneyFromPeriods[0]['count'] != null){
                            $moneyFromPeriod =  $moneyFromPeriods[0]['count'];
                        }
                        $this->_view->moneyFromPeriod = $moneyFromPeriod;
                }
                return $this->_view->render('money.phtml');

            }

        }

    }
        
    public function _makeOptionQuotes(){
       if(Tools_Security_Acl::isAllowed(self::RESOURCE_TOASTER_STATS)){
           if(isset($this->_options[1])){
               $totalQuote = 0;
               $quotesFromPeriod = 0;
               $today = 0;
               $toasterstatsDbTable = new Toasterstats_Models_Dbtables_ToasterstatsDbtable();
               if($this->_options[1]== 'today'){
                    $period = $this->_createTimePeriod(1, 'days');
                    $quotesFromToday = $toasterstatsDbTable->selectQuotesFromPeriod($period);
                    if(isset($quotesFromToday) && $quotesFromToday != null && $quotesFromToday[0]['count'] != null){
                        $today = $quotesFromToday[0]['count'];
                    }
                    $this->_view->quotesToday= $today;
               }
               if($this->_options[1]== 'new'){
                    $totalQuotes = $toasterstatsDbTable->selectAllQuotes();
                    if(isset($totalQuotes) && $totalQuotes != null && $totalQuotes[0]['count'] != null){
                        $totalQuote = $totalQuotes[0]['count'];
                    }
                    $this->_view->totalQuotes = $totalQuote;
               }
               if(in_array($this->_options[1], $this->_periodArray)){
                   if(!isset($this->_options[2])){
                       $period = $this->_createTimePeriod(1, $this->_options[1]);
                   }
                   else{
                       $period = $this->_createTimePeriod($this->_options[2], $this->_options[1]);
                   }
                   $quotesFromPeriods = $toasterstatsDbTable->selectQuotesFromPeriod($period);
                   if(isset($quotesFromPeriods) && $quotesFromPeriods != null && $quotesFromPeriods[0]['count'] != null){
                        $quotesFromPeriod = $quotesFromPeriods[0]['count'];
                   }
                   $this->_view->quotesFromPeriod = $quotesFromPeriod;
                }
                return $this->_view->render('quotes.phtml');
           }
       }
    }
        
    public function _makeOptionOrders(){
       if(Tools_Security_Acl::isAllowed(self::RESOURCE_TOASTER_STATS)){
           if(isset($this->_options[1])){
               $totalOrder = 0;
               $todayOrder = 0;
               $lastOrder = 0;
               $orderFromPeriod = 0;
               $toasterstatsDbTable = new Toasterstats_Models_Dbtables_ToasterstatsDbtable();
               if($this->_options[1]== 'today'){
                    $period = $this->_createTimePeriod(1, 'days');
                    $todayOrders = $toasterstatsDbTable->selectAverageOrdersFromPeriod($period);
                    if(isset($todayOrders) && $todayOrders != null && $todayOrders['0']['count'] != null){
                        $todayOrder = $todayOrders['0']['count'];
                    }
                    $this->_view->todayOrders = $todayOrder;
               }
               if($this->_options[1]== 'last'){
                    $lastOrders = $toasterstatsDbTable->selectLastOrder();
                    if(isset($lastOrders) && $lastOrders != null && $lastOrders['0']['count'] != null){
                        $lastOrder = $lastOrders['0']['count'];
                    }
                    $this->_view->lastOrders = $lastOrder;

               }
               if($this->_options[1]== 'total'){
                    $evarageOrders = $toasterstatsDbTable->selectAverageTotalOrder();
                    if(isset($evarageOrders) && $evarageOrders != null && $evarageOrders[0]['count'] != null){
                        $totalOrder =  $evarageOrders[0]['count'];
                    }
                    $this->_view->totalOrder = $totalOrder;
               }
               if(in_array($this->_options[1], $this->_periodArray)){
                   if(!isset($this->_options[2])){
                       $period = $this->_createTimePeriod(1, $this->_options[1]);
                   }
                   else{
                       $period = $this->_createTimePeriod($this->_options[2], $this->_options[1]);
                   }
                   $evarageOrdersPeriod = $toasterstatsDbTable->selectAverageOrdersFromPeriod($period);
                   if(isset($evarageOrdersPeriod) && $evarageOrdersPeriod != null && $evarageOrdersPeriod[0]['count'] != null){
                        $orderFromPeriod =  $evarageOrdersPeriod[0]['count'];
                   }
                   $this->_view->orderFromPeriod = $orderFromPeriod;
                }
                return $this->_view->render('orders.phtml');
           }
       }
    }
        
    public function _makeOptionLabel(){
        if(Tools_Security_Acl::isAllowed(self::RESOURCE_TOASTER_STATS)){
            if(isset($this->_options[1])){
                if(isset($this->_options[2])){
                    $periodLabel = $this->_createLabelForPeriod($this->_options[1], $this->_options[2]);
                }
                else{
                    $periodLabel = $this->_createLabelForPeriod($this->_options[1], 1);
                }
                $this->_view->periodLabel = $periodLabel;
                return $this->_view->render('label.phtml');
          }
        }
   }
               
    private function _createLabelForPeriod($unitsPeriod, $period){
        if($unitsPeriod == 'days' && $period == 1){
            $periodLabel = 'Today';
        }
        if($unitsPeriod != 'days' && $period == 1){
            $periodLabel = 'This '.$unitsPeriod;
        }
        if($unitsPeriod != 'days' && $period != 1){
            $periodLabel = 'Past '.$period.' '.$unitsPeriod.'s';
        }
        if($unitsPeriod == 'days' && $period != 1){
            $periodLabel = 'Past '.$period.' '.$unitsPeriod;
        }
        return $periodLabel;
    }
        
    public function _makeOptionControl(){
        if(Tools_Security_Acl::isAllowed(self::RESOURCE_TOASTER_STATS)){
            $this->_view->translator = $this->_translator;
            $currencyHandler = Zend_Registry::get('Zend_Currency');
            $currencySymbol = $currencyHandler->getSymbol();
            $this->_view->currencySymbol = $currencySymbol;
            return $this->_view->render('controlStats.phtml');
        }
    }
       
    public function _makeOptionSalescontrol(){
         if(Tools_Security_Acl::isAllowed(self::RESOURCE_TOASTER_STATS)){
            $this->_view->translator = $this->_translator;
            $currencyHandler = Zend_Registry::get('Zend_Currency');
            $currencySymbol = $currencyHandler->getSymbol();
            $this->_view->currencySymbol = $currencySymbol;
            return $this->_view->render('salesControl.phtml');
         }
    }
    
    public function changeDashboardDataSalesAction(){
            if(Tools_Security_Acl::isAllowed(self::RESOURCE_TOASTER_STATS)){
                if ($this->_request->isPost()) {
                    $timePeriod =  $this->_request->getParam('timePeriod');
                    $averageAmountGrafic =  $this->_request->getParam('averageAmountGrafic');
                    $countGrafic =  $this->_request->getParam('countGrafic');
                    $typeOfGraficCount = $this->_request->getParam('typeOfGraficCount');
                    $typeOfGraficAverageAmount = $this->_request->getParam('typeOfGraficAverageAmount');
                    $pierchartAmountGrafics  = $this->_request->getParam('pierchartAmountGrafics');
                    $countTagGrafic  = $this->_request->getParam('countTagGrafic');
                    $countCouponGraf  = $this->_request->getParam('countCouponGraf');
                    $countEmailRemarketingGraf = $this->_request->getParam('countEmailRemarketingGraf');
                    $restoredEmailRemarketingGraf = $this->_request->getParam('restoredEmailRemarketingGraf');
                    $geoGraf = $this->_request->getParam('geoGraf');
                    $usingTax = $this->_request->getParam('taxesState');
                    $usingShipping = $this->_request->getParam('shippingState');
                    $geoGrafData = '';
                    $pierchartGraficDataResult = '';
                    $statisticArray = array();
                    $countTagGraficTag = $this->_request->getParam('countTagGraficTag');
                    $countCouponGrafCoupon = $this->_request->getParam('countCouponGraficCoupon');
                    $countEmailRemarketingGrafRemarketing = $this->_request->getParam('countEmailRemarketingGrafRemarketing');

                    if(preg_match('/\|/',$timePeriod)){
                        $datePikerPeriod = explode('|', $timePeriod);
                        $dateFromRight = date("Y-m-d", strtotime($datePikerPeriod[0]));
                        $dateToRight = date("Y-m-d", strtotime($datePikerPeriod[1]));
                        $dateFrom = new DateTime($dateFromRight);
                        $dateTo = new DateTime($dateToRight);
                        $preparePeriod = $dateFrom->diff($dateTo);
                        $differencePeriod = $preparePeriod->days;
                        $unitsPeriod = '';
                        $quntityPeriod = '';
                        if($differencePeriod<31 && $differencePeriod != 31){
                            $unitsPeriod = 'days';
                            $quntityPeriod = $differencePeriod;
                        }
                        if($differencePeriod>30 && $differencePeriod < 365){
                            $unitsPeriod = 'month';
                            $quntityPeriod = round($differencePeriod/30);
                        }
                        if($differencePeriod > 365 ){
                            $unitsPeriod = 'year';
                            $quntityPeriod = round($differencePeriod/365);
                        }
                        $period = "between '".$dateFromRight.' 00:00:00'."' AND '".$dateToRight.' 23:59:59'."'";
                        $pierchartExcist = 0;
                        $pierchartExcistProduct = 0;
                        $pierchartExcistType = 0;
                        $pierchartExcistCustomer = 0;
                        $pierchartExcistBrand = 0;
                        if(!empty($pierchartAmountGrafics)){
                            foreach($pierchartAmountGrafics as $value){
                                if($value === 'productTable' || $value === 'typeTable' || $value === 'customerTable' || $value === 'brandTable') {
                                    if ($value === 'productTable') {
                                        $value = 'product';
                                        $pierchartAmountGraficsData = $this->_changePirchartAmountGrafic($value,
                                            $period, $usingTax, $usingShipping);
                                        $pierchartGraficDataResult['productTable'] = $pierchartAmountGraficsData;
                                        $pierchartExcistProduct = 1;
                                    }
                                    if ($value === 'typeTable') {
                                        $value = 'type';
                                        $pierchartAmountGraficsData = $this->_changePirchartAmountGrafic($value,
                                            $period, $usingTax, $usingShipping);
                                        $pierchartGraficDataResult['typeTable'] = $pierchartAmountGraficsData;
                                        $pierchartExcistType = 1;
                                    }
                                    if ($value === 'customerTable') {
                                        $value = 'customer';
                                        $pierchartAmountGraficsData = $this->_changePirchartAmountGrafic($value,
                                            $period, $usingTax, $usingShipping);
                                        $pierchartGraficDataResult['customerTable'] = $pierchartAmountGraficsData;
                                        $pierchartExcistCustomer = 1;
                                    }
                                    if ($value === 'brandTable') {
                                        $value = 'brand';
                                        $pierchartAmountGraficsData = $this->_changePirchartAmountGrafic($value,
                                            $period, $usingTax, $usingShipping);
                                        $pierchartGraficDataResult['brandTable'] = $pierchartAmountGraficsData;
                                        $pierchartExcistBrand = 1;
                                    }
                                }else {
                                    $pierchartAmountGraficsData = $this->_changePirchartAmountGrafic($value,$period, $usingTax, $usingShipping);
                                    $pierchartGraficDataResult[$value] = $pierchartAmountGraficsData;
                                    $pierchartExcist = 1;
                                }
                            }

                        }
                        $countTagExcist = 0;
                        $countTagGraficsData = array();
                        if($countTagGrafic == '1'){
                            $countTagGraficsData = $this->_createCountTagGraf($period);
                            $countTagExcist = 1;
                        }

                        $countTagExcistTag = 0;
                        $countTagGraficsDataTag = array();
                        if($countTagGraficTag == '1'){
                            $countTagGraficsDataTag = $this->_createCountTagGraf($period);
                            $countTagExcistTag = 1;
                        }

                        $countCouponExists = 0;
                        $countCouponGrafData = array();
                        if ($countCouponGraf == '1'){
                            $countCouponGrafData = $this->_createCountCouponGraf($period);
                            $countCouponExists = 1;
                        }

                        $countCouponExistsCoupon = 0;
                        $countCouponGrafDataCoupon = array();
                        if ($countCouponGrafCoupon == '1'){
                            $countCouponGrafDataCoupon = $this->_createCountCouponGraf($period);
                            $countCouponExistsCoupon = 1;
                        }

                        $countEmailRemarketingExists = 0;
                        $countEmailRemarketingGrafData = array();
                        if ($countEmailRemarketingGraf == '1'){
                            $countEmailRemarketingGrafData = $this->_createCountEmailRemarketingGraf($period, $this->_remarketingTriggerNames);
                            $countEmailRemarketingExists = 1;
                        }
                        $countEmailRemarketingExistsRemarketing = 0;
                        $countEmailRemarketingGrafDataRemarketing = array();
                        if ($countEmailRemarketingGrafRemarketing == '1'){
                            $countEmailRemarketingGrafDataRemarketing = $this->_createCountEmailRemarketingGraf($period, $this->_remarketingTriggerNames);
                            $countEmailRemarketingExistsRemarketing = 1;
                        }

                        if($geoGraf == '1'){
                            $geoGrafData = $this->_geoMapHandler($period);
                        }

                        $restoredEmailRemarketingExists = 0;
                        $restoredEmailRemarketingGrafData = array();
                        if ($restoredEmailRemarketingGraf == '1'){
                            $restoredEmailRemarketingGrafData = $this->_createRestoredEmailRemarketingGraf($period, Models_Model_CartSession::CART_STATUS_NEW, $usingTax, $usingShipping);
                            $restoredEmailRemarketingExists = 1;
                        }

                        $rightPeriodForGraf = $this->_rigthGrafhPeriod($unitsPeriod);
                        $date = $this->_checkDynamicPeriod($unitsPeriod, $quntityPeriod, $dateToRight);
                        $grafsData = $this->_changeDashboardDataSalesTabGrafh($averageAmountGrafic, $countGrafic,
                            $unitsPeriod, $typeOfGraficCount, $typeOfGraficAverageAmount, $rightPeriodForGraf, $date,
                            $period, $usingTax, $usingShipping);
                        $statisticArray = array(
                            'grafData' => $grafsData,
                            'typeOfGraficCount' => ucfirst($typeOfGraficCount),
                            'typeOfGraficAvarageAmount' => ucfirst($typeOfGraficAverageAmount),
                            'pierchartExcist' => $pierchartExcist,
                            'pierChartData' => $pierchartGraficDataResult,
                            'countTagExcist' => $countTagExcist,
                            'countTagGraficsData' => $countTagGraficsData,
                            'countCouponExists' => $countCouponExists,
                            'countCouponGrafData' => $countCouponGrafData,
                            'countEmailRemarketingGrafData' => $countEmailRemarketingGrafData,
                            'countEmailRemarketingExists' => $countEmailRemarketingExists,
                            'restoredEmailRemarketingGrafData' => $restoredEmailRemarketingGrafData,
                            'restoredEmailRemarketingGrafExists' => $restoredEmailRemarketingExists,
                            'geoGraf' => $geoGraf,
                            'geoGrafData' => $geoGrafData,
                            'countTagExcistTag' => $countTagExcistTag,
                            'countTagGraficsDataTag' => $countTagGraficsDataTag,
                            'countCouponExistsCoupon' => $countCouponExistsCoupon,
                            'countCouponGrafDataCoupon' => $countCouponGrafDataCoupon,
                            'countEmailRemarketingGrafDataRemarketing' => $countEmailRemarketingGrafDataRemarketing,
                            'countEmailRemarketingExistsRemarketing' => $countEmailRemarketingExistsRemarketing,
                            'pierchartExcistProduct' => $pierchartExcistProduct,
                            'pierchartExcistType' => $pierchartExcistType,
                            'pierchartExcistCustomer' => $pierchartExcistCustomer,
                            'pierchartExcistBrand' => $pierchartExcistBrand
                        );
                    }
                    echo  json_encode($statisticArray);

                }
            }

        }
    
    /**
     * @param string $period period of time
     * @param array $trigger (new, completed, shipped, delivered etc...)
     * @param int $includeTax flag for prices with or without tax
     * @param int $includeShipping with or without shipping
     * @return array
     * @throws Zend_Exception
     */
    private function _createRestoredEmailRemarketingGraf($period, $trigger = Models_Model_CartSession::CART_STATUS_NEW, $includeTax = 0, $includeShipping = 0)
    {
        $toasterstatsDbTable = new Toasterstats_Models_Dbtables_ToasterstatsDbtable();
        $salesAmountByTriggerName = $toasterstatsDbTable->getRestoredCartInfo($period,
            $trigger, $includeTax, $includeShipping);
        $totalProducts = array($this->_translator->translate('Nothing Found') => '1');
        if (!empty($salesAmountByTriggerName)) {
            $currency = Zend_Registry::get('Zend_Currency');
            if (!empty($salesAmountByTriggerName['total'])) {
                $totalProducts = array(
                    'Abandoned cart emails sent (' . $currency->toCurrency($salesAmountByTriggerName['total'] - $salesAmountByTriggerName['restoredTotal']) . ')' => $salesAmountByTriggerName['countAll'] - $salesAmountByTriggerName['countPaid'],
                    'Recovered cart purchases (' . $currency->toCurrency($salesAmountByTriggerName['restoredTotal']) . ')' => $salesAmountByTriggerName['countPaid']
                );
            }
        }
        return $totalProducts;
    }
    
    private function _checkDynamicPeriod($periodUnits, $period, $endDate){
        if($period == 0){
            $period = $period+1;
        }
        if($period == 1 || $period>1){
            $period = $period+1;
        }
        switch ($periodUnits) {
                case 'days':
                    for($i = 0; $i<$period;$i++){
                        $date[date('Y-m-d', strtotime(date("Y-m-d", strtotime($endDate)) . " -$i day"))] = array('sales' => 0, 'quotes' => 0);
                    }
                    break;
                case 'month':
                    for($i = 0; $i<$period;$i++){
                        $date[date('Y-m', strtotime(date("Y-m-1", strtotime($endDate)) . " -$i month"))] = array('sales' => 0, 'quotes' => 0);
                    }
                    break;
                case 'year':
                    if($period == '1'){
                        $date[date('Y', strtotime("first day of January"))] = array('sales' => 0, 'quotes' => 0);
                    }
                    else{
                        for($i = 0; $i<$period;$i++){
                            $date[date('Y', strtotime(date("Y-m-d", strtotime($endDate)) . " -$i year"))] = array('sales' => 0, 'quotes' => 0);
                        }
                    }
                    break;

            }
        return $date;

    }
      
        private function _changeDashboardDataSalesTabGrafh($averageAmountGrafic, $countGrafic, $unitsPeriod, $typeOfGraficCount, $typeOfGraficAverageAmount, $rightPeriodForGraf, $date, $period, $usingTax = 0, $usingShipping = 0){
             $grafDatas = array('countGraficExcist'=>0, 'averageAmountGraficExcist'=>0, 'countGraficData' => '', 'averageAmountGraficData' => '');
             if($averageAmountGrafic == 1){
                $averageAmountGraficData =  $this->_createGraficsData('averageamount', $typeOfGraficAverageAmount, $period, $rightPeriodForGraf, $date, $usingTax, $usingShipping);
                if(count($averageAmountGraficData)>1){
                    if($unitsPeriod != 'year'){
                        $averageAmountGraficData = array_reverse($averageAmountGraficData);
                    }
                }
                $grafDatas['averageAmountGraficExcist'] = 1;
                $grafDatas['averageAmountGraficData'] = $averageAmountGraficData;
             }
             if($countGrafic == 1){
                $countGraficData =  $this->_createGraficsData('count', $typeOfGraficCount, $period, $rightPeriodForGraf, $date, $usingTax, $usingShipping);
                if(count($countGraficData)>1){
                    if($unitsPeriod != 'year'){
                        $countGraficData = array_reverse($countGraficData);
                    }
                }
                $grafDatas['countGraficExcist'] = 1;
                $grafDatas['countGraficData'] = $countGraficData;
             }
             return $grafDatas;

        }
    
        public function changeDashboardDataAction(){
            if(Tools_Security_Acl::isAllowed(self::RESOURCE_TOASTER_STATS)){
                if ($this->_request->isPost()) {
                    $timePeriod =  $this->_request->getParam('timePeriod');
                    $amountGrafic =  $this->_request->getParam('amountGrafic');
                    $countGrafic =  $this->_request->getParam('countGrafic');
                    $typeOfGraficCount = $this->_request->getParam('typeOfGraficCount');
                    $typeOfGraficAmount = $this->_request->getParam('typeOfGraficAmount');
                    $productTable = $this->_request->getParam('productTable');
                    $periodLabel = $this->_request->getParam('periodLabel');
                    $limitForBestsselers = $this->_request->getParam('limitForBestsselers');
                    $amountGraficData = array('0'=>array('created_at'=>''));
                    $countGraficData = array('0'=>array('created_at'=>''));
                    $countGraficResult = array('excist'=>'0', 'data'=>$countGraficData);
                    $amountGraficResult = array('excist'=>'0', 'data'=>$amountGraficData);
                    $productTableData = array(0=>array('name'=>$this->_translator->translate('Nothing found')));
                    if(preg_match('/month|days|year|week|totalPeriod/', $timePeriod)){
                        $quntityPeriod = preg_replace('/month|days|year|week|totalPeriod/', '', $timePeriod);
                        $unitsPeriod = preg_replace('/\d/', '', $timePeriod);
                        if(in_array($unitsPeriod, $this->_periodArray)){
                            $toasterstatsDbTable = new Toasterstats_Models_Dbtables_ToasterstatsDbtable();
                            $salesFromPeriod = 0;
                            $moneyFromPeriod = 0;
                            $orderFromPeriod = 0;
                            $currencyHandler = Zend_Registry::get('Zend_Currency');
                            $grafsData = $this->_changeDashboardDataGrafh($amountGrafic, $countGrafic, $unitsPeriod, $quntityPeriod, $typeOfGraficCount, $typeOfGraficAmount);
                            if($unitsPeriod == 'totalPeriod'){
                                $totalSales = $toasterstatsDbTable->selectAllSales();

                                if(isset($totalSales) && $totalSales != null && !empty($totalSales)){
                                    $salesFromPeriod = count($totalSales);
                                }
                                $totalMoneys = $toasterstatsDbTable->selectAllMoney();
                                if(isset($totalMoneys) && $totalMoneys != null && $totalMoneys[0]['count'] != null){
                                    $moneyFromPeriod =  $totalMoneys[0]['count'];
                                }
                                $evarageOrders = $toasterstatsDbTable->selectAverageTotalOrder();
                                if(isset($evarageOrders) && $evarageOrders != null && $evarageOrders[0]['count'] != null){
                                    $orderFromPeriod =  $evarageOrders[0]['count'];
                                }
                                if($productTable ==1){
                                    $productTop = $toasterstatsDbTable->mostSaledProductsAllTime($limitForBestsselers);
                                    $productTableData = $this->_prepareProducts($productTop);
                                }

                            }
                            else{
                                $period = $this->_createTimePeriod($quntityPeriod, $unitsPeriod);
                                $totalSalesPeriod = $toasterstatsDbTable->selectSalesFromPeriod($period);
                                if(isset($totalSalesPeriod) && $totalSalesPeriod != null && !empty($totalSalesPeriod)){
                                    $salesFromPeriod = count($totalSalesPeriod);
                                }
                                $moneyFromPeriods = $toasterstatsDbTable->selectAllMoneyFromPeriod($period);
                                if(isset($moneyFromPeriods) && $moneyFromPeriods !=null && $moneyFromPeriods[0]['count'] != null){
                                    $moneyFromPeriod =  $moneyFromPeriods[0]['count'];
                                }
                                $evarageOrdersPeriod = $toasterstatsDbTable->selectAverageOrdersFromPeriod($period);
                                if(isset($evarageOrdersPeriod) && $evarageOrdersPeriod != null && $evarageOrdersPeriod[0]['count'] != null){
                                    $orderFromPeriod =  $evarageOrdersPeriod[0]['count'];
                                }
                                if($productTable ==1){
                                    $productTop = $toasterstatsDbTable->mostSaledProducts($period,$limitForBestsselers);
                                    $productTableData = $this->_prepareProducts($productTop);
                                }
                            }
                            $statisticArray = array('salesFromPeriod' =>$salesFromPeriod, 'moneyFromPeriod' => $currencyHandler->toCurrency($moneyFromPeriod),
                                'orderFromPeriod' =>$currencyHandler->toCurrency($orderFromPeriod), 'grafData'=>$grafsData, 'typeOfGraficCount'=>ucfirst($typeOfGraficCount),
                                'typeOfGraficAmount'=> ucfirst($typeOfGraficAmount), 'periodLabel'=>$periodLabel, 'productTable'=>$productTable, 'productTableData'=>$productTableData);
                            echo  json_encode($statisticArray);

                        }

                    }

                }
            }
        }
    
    private function _changeDashboardDataGrafh($amountGrafic, $countGrafic, $unitsPeriod, $quntityPeriod, $typeOfGraficCount, $typeOfGraficAmount, $usingTax = 0){
         if($unitsPeriod != 'totalPeriod'){
             $rightPeriodForGraf = $this->_rigthGrafhPeriod($unitsPeriod);
             $date = $this->_checkPeriod($unitsPeriod, $quntityPeriod);
             $period = $this->_createTimePeriod($quntityPeriod, $unitsPeriod);
             $grafDatas = array('countGraficExcist'=>0, 'amountGraficExcist'=>0, 'countGraficData' => '', 'amountGraficData' => '');
             if($amountGrafic == 1){
                $amountGraficData =  $this->_createGraficsData('amount', $typeOfGraficAmount, $period, $rightPeriodForGraf, $date, $usingTax);
                if(count($amountGraficData)>1){
                    if($unitsPeriod != 'year'){
                        $amountGraficData = array_reverse($amountGraficData);
                    }
                }
                $grafDatas['amountGraficExcist'] = 1;
                $grafDatas['amountGraficData'] = $amountGraficData;
             }
             if($countGrafic == 1){
                $countGraficData =  $this->_createGraficsData('count', $typeOfGraficCount, $period, $rightPeriodForGraf, $date, $usingTax);
                if(count($countGraficData)>1){
                    if($unitsPeriod != 'year'){
                        $countGraficData = array_reverse($countGraficData);
                    }
                }
                $grafDatas['countGraficExcist'] = 1;
                $grafDatas['countGraficData'] = $countGraficData;
             }
             return $grafDatas;
         }
         else{
             $unitsPeriod = 'year';
             $rightPeriodForGraf = $this->_rigthGrafhPeriod($unitsPeriod);
             $toasterstatsDbTable = new Toasterstats_Models_Dbtables_ToasterstatsDbtable();
             $totalSales = $toasterstatsDbTable->selectAllSales();
             $totalQuotes = $toasterstatsDbTable->selectAllQuotes();
             $periodsArray = array();
             foreach($totalSales as $sale){
                 $periodsArray[$sale['created_at']] = '';
             }
             foreach($totalQuotes as $quote){
                 $periodsArray[$quote['created_at']] = '';
             }
             $minPeriodDate = array_search(min($periodsArray), $periodsArray);
             $totalPeriod = substr($minPeriodDate, 0, $rightPeriodForGraf);
             $now = date('Y');
             if($now == $totalPeriod){
                $totalPeriod = $totalPeriod - 1;
             }
             $quntityPeriod = $now - $totalPeriod;
             $period = $this->_createTimePeriod($quntityPeriod, $unitsPeriod);
             for($i = 0, $j=$totalPeriod; $j<$now; $i++, $j++){
                   $date[date('Y', strtotime('-'.$i.' year'))] = array('sales' => 0, 'quotes' => 0);

             }
             if($amountGrafic == 1){
                $amountGraficData =  $this->_createGraficsData('amount', $typeOfGraficAmount, $period, $rightPeriodForGraf, $date);
                if(count($amountGraficData)>1){
                     $amountGraficData = array_reverse($amountGraficData);
                }
                $grafDatas['amountGraficExcist'] = 1;
                $grafDatas['amountGraficData'] = $amountGraficData;
             }
             if($countGrafic == 1){
                $countGraficData =  $this->_createGraficsData('count', $typeOfGraficCount, $period, $rightPeriodForGraf, $date);
                if(count($countGraficData)>1){
                     $countGraficData = array_reverse($countGraficData);
                }
                $grafDatas['countGraficExcist'] = 1;
                $grafDatas['countGraficData'] = $countGraficData;
             }
             return $grafDatas;
       }

    }
    
    ////////Site Statistic Block////////
    
    public function _makeOptionSitestatistic(){
        if(Tools_Security_Acl::isAllowed(self::RESOURCE_TOASTER_STATS)){
              $quantityPages = $this->_quantityOfPages();
              $quantityBrands = $this->_quantityBrands();
              $quantityProducts = $this->_quantityProducts();
              $quantityPlugins = $this->_pluginInformation();
              $quantityUsers = $this->_quantityUsers();
              $quantityTemplates = $this->_quantityTemplates();
              $quantityQuotes = $this->_quantityQuotes();
              $quantitySalesWithoutDividingByGateway = $this->_quantitySales();
              $quantitySalesCanceled = $this->_quantitySalesByStatusGateway(Models_Model_CartSession::CART_STATUS_CANCELED);
              $quantitySalesLostOpportunity = $this->_quantitySalesByStatusGateway(Models_Model_CartSession::CART_STATUS_CANCELED, true);
              $quantitySalesNewQuote = $this->_quantitySalesByStatusGateway(Models_Model_CartSession::CART_STATUS_PENDING, true);
              $quantitySalesMerchantActionRequired = $this->_quantitySalesByStatusGateway(Models_Model_CartSession::CART_STATUS_PENDING);
              $quantitySalesQuoteSent = $this->_quantitySalesByStatusGateway(Models_Model_CartSession::CART_STATUS_PROCESSING, true);
              $quantitySalesTechnicalProcessing = $this->_quantitySalesByStatusGateway(Models_Model_CartSession::CART_STATUS_PROCESSING);
              $this->_view->quantityPages = $quantityPages['quantityPages'];
              $this->_view->quantityDraftPages = $quantityPages['quantityDraftPages'];
              $this->_view->quantityBrands = $quantityBrands;
              $this->_view->quantityProducts = $quantityProducts;
              $this->_view->quantityPlugins = $quantityPlugins['quantityPlugins'];
              $this->_view->quantityEnabledPlugins = $quantityPlugins['quantityEnabledPlugins'];
              $this->_view->quantityUsers = $quantityUsers['quantityUsers'];
              $this->_view->quantityAdmins = $quantityUsers['quantityAdmins'];
              $this->_view->quantityTemplates = $quantityTemplates;
              $this->_view->quantityQuotes = $quantityQuotes;
              $this->_view->quantitySales = $quantitySalesWithoutDividingByGateway;
              $this->_view->quantityCanceled = $quantitySalesCanceled;
              $this->_view->quantitySalesLostOportunity = $quantitySalesLostOpportunity;
              $this->_view->quantitySalesMerchantActionRequired = $quantitySalesMerchantActionRequired;
              $this->_view->quantitySalesNewQuote = $quantitySalesNewQuote;
              $this->_view->quantitySalesQuoteSent = $quantitySalesQuoteSent;
              $this->_view->quantitySalesTechnicalProcessing = $quantitySalesTechnicalProcessing;
              $this->_view->translator = $this->_translator;
              return $this->_view->render('table.phtml');
        }
    }
    
    private function _quantityOfPages(){
         $pageMapper = Application_Model_Mappers_PageMapper::getInstance();
         $tosterStatsDbTable = new Toasterstats_Models_Dbtables_ToasterstatsDbtable();
         $staticMenuPages = $tosterStatsDbTable->quantityOfStaticMenuPages();
         $allNomenuPages = $tosterStatsDbTable->quantityOfNomenuPages();
         $allDraftPages = $pageMapper->fetchAllDraftPages();
         $quantityPages  = 0;
         $quantityDraftPages = 0;
         if(isset($staticMenuPages[0]['id']) && !empty($staticMenuPages) && $staticMenuPages != null){
            $quantityPages = $quantityPages + $staticMenuPages[0]['id'];
         }
         if(isset($allNomenuPages[0]['id']) && !empty($allNomenuPages) && $allNomenuPages != null){
            $quantityPages = $quantityPages + $allNomenuPages[0]['id'];
         }
         if(isset($allDraftPages) && !empty($allDraftPages) && $allDraftPages != null){
             $quantityDraftPages = count($allDraftPages);
         }
         return array('quantityPages'=>$quantityPages, 'quantityDraftPages'=>$quantityDraftPages);

    }
    
    private function _quantityBrands(){
        $quantityBrands  = 0;
        $brandMapper = Models_Mapper_Brand::getInstance();
        $brands = $brandMapper->fetchAll();
        if(isset($brands) && !empty($brands) && $brands != null){
            $quantityBrands = count($brands);
        }
        return $quantityBrands;
    }
    
    private function _quantityProducts(){
        $quantityProducts  = 0;
        $tosterStatsDbTable = new Toasterstats_Models_Dbtables_ToasterstatsDbtable();
        $product = $tosterStatsDbTable->quantityOfProducts();
        if(isset($product[0]['id']) && !empty($product) && $product != null){
              $quantityProducts = $product[0]['id'];
        }
        return $quantityProducts;
    }

    private function _pluginInformation(){
        $pluginMapper = Application_Model_Mappers_PluginMapper::getInstance();
        $quantityPlugins  = 0;
        $quantityEnabledPlugins  = 0;
        $allPlugins = $pluginMapper->fetchAll();
        $enabledPlugins = $pluginMapper->findEnabled();
        if(isset($allPlugins) && !empty($allPlugins) && $allPlugins != null){
            $quantityPlugins = count($allPlugins);
        }
        if(isset($enabledPlugins) && !empty($enabledPlugins) && $enabledPlugins != null){
            $quantityEnabledPlugins = count($enabledPlugins);
        }
        return array('quantityPlugins'=>$quantityPlugins, 'quantityEnabledPlugins'=>$quantityEnabledPlugins);

    }
    
    /**
     * system users quantity
     *
     * @return array
     */
    private function _quantityUsers()
    {
        $toasterStatsDbTable = new Toasterstats_Models_Dbtables_ToasterstatsDbtable();
        $usersQuantityInfo = $toasterStatsDbTable->getUserQuantity();
        $quantityUsers = 0;
        $quantityAdmins = 0;
        if (!empty($usersQuantityInfo)) {
            foreach ($usersQuantityInfo as $usersRole => $userQuantityData) {
                if ($usersRole !== Tools_Security_Acl::ROLE_SUPERADMIN && $usersRole !== Tools_Security_Acl::ROLE_ADMIN) {
                    $quantityUsers +=  $userQuantityData['userQuantity'];
                }
            }
        }

        if (!empty($usersQuantityInfo['admin'])) {
            $quantityAdmins = $usersQuantityInfo['admin']['userQuantity'];
        }

        return array('quantityUsers' => $quantityUsers, 'quantityAdmins' => $quantityAdmins);
    }
    
    private function _quantityTemplates(){
        $templateMapper = Application_Model_Mappers_TemplateMapper::getInstance();
        $allTemplates = $templateMapper->fetchAll();
        $quantityTemplates = 0;
        if(isset($allTemplates) && !empty($allTemplates) && $allTemplates != null){
            $quantityTemplates = count($allTemplates);
        }
        return $quantityTemplates;
    }
    
    private function _quantityQuotes(){
        $quoteMapper  = Quote_Models_Mapper_QuoteMapper::getInstance();
        $allQuotes = $quoteMapper->fetchAll();
        $quantityQuotes = 0;
        if(isset($allQuotes) && !empty($allQuotes) && $allQuotes != null){
            $quantityQuotes = count($allQuotes);
        }
        return $quantityQuotes;
    }

    private function _quantitySales(){
        $this->_toasterStatsDbTable = new Toasterstats_Models_Dbtables_ToasterstatsDbtable();
        $allStatusesCount = $this->_toasterStatsDbTable->selectAllSalesCountNotDependentFromQuote();
        return $allStatusesCount;
    }

    private function _quantitySalesByStatusGateway($status, $quote = false){
        if($quote){
            $statusesCount = $this->_toasterStatsDbTable->getSalesByStatusExcludeOrIncludeQuoteGateway($status);
        }else{
            $statusesCount = $this->_toasterStatsDbTable->getSalesByStatusExcludeOrIncludeQuoteGateway($status, true);
        }
        return $statusesCount;
    }
    
    ////////Site Statistic Block End////////
}
