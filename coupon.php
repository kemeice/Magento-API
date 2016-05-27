<?php

/**
 * @author kem parson
 * @copyright 2016
 */
 
ini_set('display_errors','On');
error_reporting(E_ALL); 
$error='false'; 
$customer=$_REQUEST['custid'];
$coupon_code=$_REQUEST['code'];
if(empty($coupon_code))
{
 $ret['error'] = 'Need a code for this page' ;
    
    $error='true';
    }
//ini_set('display_errors','On');
//error_reporting(E_ALL);
require_once ($_SERVER['DOCUMENT_ROOT'] .'/app/Mage.php');
require_once ('config.php');
$error='false';
Mage::app();
Mage::app()->getTranslator()->init('frontend');
Mage::app()->loadArea('frontend');
Mage::getSingleton('core/session', array('name' => 'frontend'));
$cust = Mage::getModel('customer/customer')->load($customer);
$quote = Mage::getModel('sales/quote')->loadByCustomer($cust);
$qid=$quote->getId();
$proxy = new SoapClient(Mage::getBaseUrl().'/api/v2_soap/?wsdl'); 
$sessionId = $proxy->login(APIUSER, APIKEY); 
try
{
$result = $proxy->shoppingCartCouponAdd($sessionId, $qid, $coupon_code);
//var_dump($result);
}

catch (Exception $e) {
$message = $e->getMessage();
    $ret['error'] = $message ;
    
    $error='true';
}


 if($error=='false')
{     
    
    
    $ret['sucess']="true";
       
        
        
    }
    

$result=json_encode($ret);
echo $result;

?>