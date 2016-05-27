<?php

/**
 * @author kem parson
 * @copyright 2016
 */
ini_set('display_errors','On');
error_reporting(E_ALL); 
$customer=$_REQUEST['custid'];
require_once ($_SERVER['DOCUMENT_ROOT'] .'/app/Mage.php');
require_once ('config.php');
Mage::app();
Mage::app()->getTranslator()->init('frontend');
Mage::getSingleton('core/session', array('name' => 'frontend'));
//$product = Mage::getModel('catalog/product')->load($productId);

$cust = Mage::getModel('customer/customer')->load($customer);
$quote = Mage::getModel('sales/quote')->loadByCustomer($cust);
$qid=$quote->getId();

function cartadress($qid, $customer ){
 
$proxy = new SoapClient(Mage::getBaseUrl().'/api/v2_soap/?wsdl'); 
$sessionId = $proxy->login(APIUSER, APIKEY);  
$firstname=$_REQUEST['firstname'];
$lastname=$_REQUEST['lastname'];
$street=$_REQUEST['street'];
$city=$_REQUEST['city'];
$region=$_REQUEST['region'];
$postcode=$_REQUEST['postcode'];
$country_id=$_REQUEST['country_id'];
$telephone=$_REQUEST['telephone'];
$shiping=$_REQUEST['shiping'];
$region_id=$_REQUEST['region_id'];

  try{
  $result = $proxy->shoppingCartCustomerAddresses($sessionId, $qid, array(array(
'mode' => $mode,
'firstname' =>$firstname ,
'lastname' => $lastname,
'street' => $street,
'city' => $city,
'region' => $region,
'region_id'=>$region_id,
'postcode' => $postcode,
'country_id' => $country_id,
'telephone' =>$telephone ,
'is_default_shipping ' => $shiping,
))); 
 $reter['sucess']="true";
 return $reter;

}

catch (Exception $e) {
$message = $e->getMessage();
    return  $reter['error']=$message ;   
    return $reter;
}
}



function checkouadress($qid, $customer )
{
    
$proxy = new SoapClient(Mage::getBaseUrl().'/api/v2_soap/?wsdl'); 
$sessionId = $proxy->login(APIUSER, APIKEY); 
try
{
$result = $proxy->customerAddressList($sessionId,$customer );
//$result=(array)$result;
$i=0;
foreach ($result as $address):
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
$i++;
endforeach;
//var_dump($ret);


$ret=array_values($ret);
return $ret;
}



catch (Exception $e) {
$message = $e->getMessage();
     return  $ret['error']=$message ;


//var_dump($result);
    
    
}

}

if ($_REQUEST['action']=="setaddress")
 echo json_encode(cartadress($qid, $customer ));
 else
 
echo json_encode (checkouadress($qid, $customer ));
?>