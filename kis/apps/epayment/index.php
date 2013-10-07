<?
$PATH_WRT_ROOT = "../../../";
include_once($PATH_WRT_ROOT.'kis/init.php');

$libkis_epayment = $libkis->loadApp('epayment');
$unpaid_count = $libkis_epayment->getPaymentUnpaidCount();
$account_stat = $libkis_epayment->getAccountStat();

$kis_data['page']   = $page?   $page: 1;
$kis_data['amount'] = $amount? $amount: 10;

switch ($q[0]){
    
    case 'paymentrecords':
	
	$kis_data['categories'] = $libkis_epayment->getPaymentCategories();
	list($kis_data['total'], $kis_data['records']) = $libkis_epayment->getPaymentRecords(array('category'=>$category,'status'=>$status,'keyword'=>$search), $sortby, $order, $kis_data['amount'], $kis_data['page']);
	$kis_data['main_template'] = 'paymentrecords';
	
    break;

    case 'addvaluerecords':
	
	list($kis_data['total'], $kis_data['records']) = $libkis_epayment->getAddValueRecords(array('keyword'=>$search), $sortby, $order, $kis_data['amount'], $kis_data['page']);
	$kis_data['main_template'] = 'addvaluerecords';
	
    break;
    
    default:
    
	$recent_days = $recent_days=='custom'? '': $recent_days;
	
	$params = array('keyword'=>$search,'recent_days'=>$recent_days,'from'=>$from_date,'to'=>$to_date);
	
	list($kis_data['total'], $kis_data['records']) = $libkis_epayment->getTransactionRecords($params, $sortby, $order, $kis_data['amount'], $kis_data['page']);
	$kis_data['main_template'] = 'transactionrecords';
	
    break;
    
}
kis_ui::loadTemplate('main', $kis_data, $format);
?>