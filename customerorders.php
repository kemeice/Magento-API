<?php

/**
 * @author kem parson
 * @copyright 2016
 */

//ini_set('display_errors','On');
//error_reporting(E_ALL); 
$customer=$_REQUEST['custid'];
require_once ($_SERVER['DOCUMENT_ROOT'] .'/app/Mage.php');
require_once ('config.php');
Mage::app();
Mage::app()->getTranslator()->init('frontend');
Mage::getSingleton('core/session', array('name' => 'frontend'));
$proxy = new SoapClient(Mage::getBaseUrl().'/api/v2_soap/?wsdl'); 
$sessionId = $proxy->login(APIUSER, APIKEY);

$filter = array('filter' => array(array('key' => 'customer_id', 'value' =>$customer )));
try{
$result = $proxy->salesOrderList($sessionId, $filter);
$i=0;
foreach ($result as $order):
$ret['$i']['orderid']=$order->increment_id;
$ret['$i']['orderdate']=$order->created_at;
$ret['$i']['ordertotal']=$order->grand_total;
//$ret[$i]['orderid']=$order->order_id;





endforeach;
}
catch (Exception $e) {
$message = $e->getMessage();
    return  $ret['error']=$message ;   
   
}

$ret=array_values($ret);       
$result=json_encode($ret);
echo $result;



?>