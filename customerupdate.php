<?php

/**
 * @author kem parson
 * @copyright 2016
 */
$firstname=$_REQUEST['firstname'];
$lastname=$_REQUEST['lastname'];
$customer=$_REQUEST['custid'];
$telephone=$_REQUEST['mobile'];
$lisence=$_REQUEST['lis'];
$email=$_REQUEST['email'];
require_once ($_SERVER['DOCUMENT_ROOT'] .'/app/Mage.php');
require_once ('config.php');
Mage::app();
Mage::app()->getTranslator()->init('frontend');
Mage::getSingleton('core/session', array('name' => 'frontend'));
$proxy = new SoapClient(Mage::getBaseUrl().'/api/v2_soap/?wsdl'); 
$sessionId = $proxy->login(APIUSER, APIKEY);
try
{
    
$result =$proxy->customerCustomerUpdate($sessionId, $customer, array('email' => $email, 'firstname' => $firstname, 'lastname' => $lastname,  'le_valid_license'=>$lisence , 'le_phone_number' => $telephone));  
 $ret['sucess']=$result ;
}
catch (Exception $e) {
$message = $e->getMessage();
$errors='true';
      $ret['error']=$message ;   
   
}
echo json_encode($ret);


?>