<?php

/**
 * @author kem parson
 * @copyright 2016
 */

$id=$_REQUEST['id']; 
require_once ($_SERVER['DOCUMENT_ROOT'] .'/app/Mage.php');
require_once ('config.php');
Mage::app();
Mage::app()->getTranslator()->init('frontend');
Mage::getSingleton('core/session', array('name' => 'frontend'));
$proxy = new SoapClient(Mage::getBaseUrl().'/api/v2_soap/?wsdl'); 
$sessionId = $proxy->login(APIUSER, APIKEY);
try
{
$result = $proxy->customerAddressDelete($sessionId, $id);  
 $ret['sucess']='true' ; 
}

catch (Exception $e) {
$message = $e->getMessage();
$errors='true';
      $ret['error']=$message ;   
   
}

echo json_encode($ret);
?>