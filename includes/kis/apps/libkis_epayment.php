<?
include_once("$intranet_root/includes/libpayment.php");
class kis_epayment extends libdb implements kis_apps {
        
    private $user_id, $user_type, $student_id;
    public static $transaction_types = array(1=>'credit','payment','purchase','transferto','transferfrom','cancelpayment','refund');
    private static $transaction_credittypes = array(1,5,6,11);
    private static $transaction_debittypes = array(2,3,4,7,8,9,10);
    
    public static $addvalue_types = array(1=>'pps','cashdeposit','addvaluemachine');
    
    public static function getAvailability($user_id, $user_type, $student_id){
    
	global $plugin;
	
	if ($plugin['payment'] && $user_type==kis::$user_types['teacher'] && $_SESSION["SSV_USER_ACCESS"]["eAdmin-ePayment"]){
	    return array('epayment', 'btn_epayment', '', '/home/eAdmin/GeneralMgmt/payment/');
	}else if ($plugin['payment'] && $user_type==kis::$user_types['parent']){
	    return array('epayment', 'btn_epayment', '', '');
	}

	
	return array();
    }
            
    public static function getAdminStatus($user_id, $user_type, $student_id){
    
	if ($_SESSION["SSV_USER_ACCESS"]["eAdmin-ePayment"]){
	    return array('/home/eAdmin/GeneralMgmt/payment/');
	}
	return array();
    }

    public static function getNotificationCount($user_id, $user_type, $student_id){
    
	$libkis_epayment = new self($user_id, $user_type, $student_id, array());
	return $libkis_epayment->getPaymentUnpaidCount();
	
    }
    public function __construct($user_id, $user_type, $student_id, $params){

	global $intranet_db;

	$this->db = $intranet_db;
	$this->user_id = $user_id;
	$this->user_type = $user_type;
	$this->student_id = $user_type == kis::$user_types['teacher']? $user_id: $student_id;
		
    }
    public function getTransactionRecords($params, $sortby, $order, $amount, $page){
	
	$credittypes = implode(',', self::$transaction_credittypes);
	$debittypes = implode(',', self::$transaction_debittypes);
	
	extract($params);
	
	$sort = $sortby? "$sortby $order,":"";
	$limit = $amount? " LIMIT ".(($page-1)*$amount).", $amount": "";
	
	$cond = $recent_days? "AND a.TransactionTime > CURDATE() - INTERVAL $recent_days DAY": "";
	$cond .= $from? "AND a.TransactionTime > '$from'": "";
	$cond .= $to? "AND a.TransactionTime < '$to'": "";

	$sql = "SELECT SQL_CALC_FOUND_ROWS
		    a.TransactionTime as time,
		    b.TransactionTime as add_value_time,
		    a.TransactionType as type,
		    IF(a.TransactionType in ($credittypes), a.Amount, '') as credit_amount,
		    IF(a.TransactionType in ($debittypes), a.Amount, '') as debit_amount,
		    a.Details as detail,
		    a.BalanceAfter as balance_after,
		    a.RefCode as ref_code
		FROM PAYMENT_OVERALL_TRANSACTION_LOG as a
		LEFT JOIN PAYMENT_CREDIT_TRANSACTION as b ON a.RelatedTransactionID = b.TransactionID
		WHERE
		    a.StudentID = ".$this->student_id."
		    AND (a.RefCode LIKE '%$keyword%' OR a.Details LIKE '%$keyword%')
		    $cond
		ORDER BY $sort
		    time desc,
		    add_value_time desc,
		    type asc,
		    credit_amount asc,
		    debit_amount asc
		$limit";
		
	$records = $this->returnArray($sql);
	$total   = current($this->returnVector('SELECT FOUND_ROWS();'));
	
	return array($total, $records);
	
    }
    public function getAddValueRecords($params, $sortby, $order, $amount, $page){
	
	extract($params);
	
	$sort = $sortby? "$sortby $order,":"";
    	$limit = $amount? " LIMIT ".(($page-1)*$amount).", $amount": "";
	
	$sql = "SELECT SQL_CALC_FOUND_ROWS
		    a.DateInput as input_date,
		    a.TransactionTime as time,
		    a.Amount as amount,
		    a.RecordType as type,
		    a.RefCode as ref_code
                FROM PAYMENT_CREDIT_TRANSACTION as a
                WHERE
                    a.StudentID = ".$this->student_id."
		    AND (a.RefCode LIKE '%$keyword%')
                ORDER BY $sort
		    input_date desc,
		    time desc,
		    type asc,
		    amount asc
		$limit";
		
	$records = $this->returnArray($sql);
	$total   = current($this->returnVector('SELECT FOUND_ROWS();'));
	
	return array($total, $records);
	
    }
    public function getPaymentRecords($params, $sortby, $order, $amount, $page){
	
	extract($params);
	
	$sort = $sortby? "$sortby $order,":"";
	$limit = $amount? " LIMIT ".(($page-1)*$amount).", $amount": "";
	$cond = $category? "AND c.CatID = $category": "";
	$cond .= $status? "AND a.RecordStatus = ".($status==1?1:0): "";
	
	$sql = "SELECT SQL_CALC_FOUND_ROWS
		    a.PaymentID as id,
		    b.Name as item_name,
		    c.Name as category_name,
		    a.Amount as amount,
		    a.SubsidyAmount as subsidy,
		    d.UnitName as unit_name,
		    b.EndDate as deadline,
		    a.RecordStatus as status,
		    a.PaidTime as time
		FROM PAYMENT_PAYMENT_ITEMSTUDENT as a
		LEFT JOIN PAYMENT_PAYMENT_ITEM as b ON a.ItemID = b.ItemID
		LEFT JOIN PAYMENT_PAYMENT_CATEGORY as c ON b.CatID = c.CatID
		LEFT JOIN PAYMENT_SUBSIDY_UNIT as d  ON a.SubsidyUnitID = d.UnitID
		WHERE
                    a.StudentID = ".$this->student_id."
		    AND (b.Name LIKE '%$keyword%' OR c.Name LIKE '%$keyword%') 
		    $cond
                ORDER BY $sort
		    deadline desc,
		    time desc,
		    item_name asc,
		    category_name asc,
		    unit_name asc,
		    amount asc,
		    subsidy asc
		$limit";
		      
	$records = $this->returnArray($sql);
	$total   = current($this->returnVector('SELECT FOUND_ROWS();'));
	
	return array($total, $records);
    }
    public function getPaymentCategories(){
	
	$sql = "SELECT CatID as id, Name as name
		FROM PAYMENT_PAYMENT_CATEGORY";
		
	return $this->returnArray($sql); 
	
    }
    public function getAccountStat(){
	
	$sql = "SELECT Balance as balance, LastUpdated as updated
		FROM PAYMENT_ACCOUNT WHERE StudentID = ".$this->student_id;
		
	return current($this->returnArray($sql));
	
    }
    public function getPaymentUnpaidCount(){
	
	$sql = "SELECT COUNT(*)
		FROM PAYMENT_PAYMENT_ITEMSTUDENT
		WHERE RecordStatus = 0 AND StudentID = ".$this->student_id;
		
	return current($this->returnVector($sql));
	
    }
}
?>