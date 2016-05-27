<?php

/**
 * @author Extalionez
 * @copyright 2016
 */
 
$customer=$_REQUEST['custid']; 
require_once ($_SERVER['DOCUMENT_ROOT'] .'/app/Mage.php');
require_once ('config.php');
Mage::app();
Mage::app()->getTranslator()->init('frontend');
Mage::getSingleton('core/session', array('name' => 'frontend'));
$proxy = new SoapClient(Mage::getBaseUrl().'/api/v2_soap/?wsdl'); 
$sessionId = $proxy->login(APIUSER, APIKEY);
try
{
$result = $proxy->customerAddressList($sessionId, $customer);
$i=0;
foreach ($result as $address):
$ret[$i]['id']=$address->customer_address_id; 
$ret[$i]['firstname']= $address->firstname;
$ret[$i] ['lastname']=$address->lastname;
$ret[$i] ['company']=$address->company;
$ret [$i]['street']=$address->street;
$ret[$i]['city']=$address->city;
$ret[$i]['region_id ']=$address->region_id ;
$ret[$i]['region']=$address->region;
$ret[$i]['postcode']=$address->postcode;
$ret[$i]['country_id']=$address->country_id;
$ret[$i]['telephone']=$address->telephone;
$ret[$i]['fax']=$address->fax;
$ret[$i]['defaultbilling']=$address->is_default_billing ;
$ret[$i]['defaulshiping']=$address->is_default_shipping;


$i++;
endforeach;
$ret=array_values($ret);
}
catch (Exception $e) {
$message = $e->getMessage();
$errors="true" ;    
$ret['error']=$message ;
}
echo json_encode($ret);

?>