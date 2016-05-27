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
$cust = Mage::getModel('customer/customer')->load($customer);
$quote = Mage::getModel('sales/quote')->loadByCustomer($cust);
$qid=$quote->getId();

function getpayment($qid)
{
$proxy = new SoapClient(Mage::getBaseUrl().'/api/v2_soap/?wsdl'); 
$sessionId = $proxy->login(APIUSER, APIKEY);
try{
$result = $proxy->shoppingCartPaymentList($sessionId, $qid);
//var_dump($result);
$i=0;
foreach ($result as $method):

$ret[$i]['method']=$method->code;
$ret[$i]['title']=$method->title ;
if($method->cc_types ):
foreach ($method->cc_types as $cc):
$ret[$i]['cc'].=$cc ;
endforeach;
endif;
$i++;
endforeach;
return array_values($ret);
}

catch (Exception $e) {
$message = $e->getMessage();
    return  $reter['error']=$message ;   
    return $reter;
}
}

function setpayment($qid)
{
    
   $po_number=$_REQUEST['po_number']; 
   $method=$_REQUEST['method'];
   $cc_cid=$_REQUEST['cc_cid'];
   $cc_owner=$_REQUEST['cc_owner'];
   $cc_type=$_REQUEST['cc_type'];
   $cc_exp_year=$_REQUEST['cc_exp_year'];
  $proxy = new SoapClient(Mage::getBaseUrl().'/api/v2_soap/?wsdl'); 
$sessionId = $proxy->login(APIUSER, APIKEY);
 
 try
 {
   $result = $proxy->shoppingCartPaymentMethod($sessionId, $qid, array(
    'po_number' => $po_number,
    'method' => $method,
    'cc_cid' => $cc_cid,
    'cc_owner' => $cc_owner,
    'cc_number' =>$cc_number ,
    'cc_type' =>$cc_type ,
    'cc_exp_year' => $cc_exp_year,
   'cc_exp_month' => $cc_exp_month
));  
   
   
   
   
   $ret['sucess']="true";
    return $ret;
 }
 
 
 catch (Exception $e) {
$message = $e->getMessage();
    return  $reter['error']=$message ;   
    return $reter;
}   
}

if ($_REQUEST['action']=="setpayment")
 echo json_encode(setpayment($qid ));
 else
 
echo json_encode (getpayment($qid));



?>