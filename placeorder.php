<?php

/**
 * @author kem parson
 * @copyright 2016
 */
$customer=$_REQUEST['custid'];
require_once ($_SERVER['DOCUMENT_ROOT'] .'/app/Mage.php');
require_once ('config.php');
Mage::app();
Mage::app()->getTranslator()->init('frontend');
Mage::getSingleton('core/session', array('name' => 'frontend'));
$cust = Mage::getModel('customer/customer')->load($customer);
$quote = Mage::getModel('sales/quote')->loadByCustomer($cust);
$qid=$quote->getId();


$proxy = new SoapClient(Mage::getBaseUrl().'/api/v2_soap/?wsdl'); 
$sessionId = $proxy->login(APIUSER, APIKEY);
try{
 $orderId = $proxy->shoppingCartOrder($sessionId, $qid, null, null);
   $ret['order']= $orderId;
   
    }

catch (Exception $e) {
$message = $e->getMessage();
    return  $ret['error']=$message ;   
   
}

echo json_encode($ret);
?>