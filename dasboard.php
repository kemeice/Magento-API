<?php

/**
 * @author kem parson
 * @copyright 2016
 */
 
//ini_set('display_errors','On');
//error_reporting(E_ALL);  
$id=$_POST['id'];
$email=$_POST['email'];
require_once ($_SERVER['DOCUMENT_ROOT'] .'/app/Mage.php');
Mage::app('default');

umask(0);
Mage::init();
Mage::getSingleton('core/session', array('name' =>'frontend'));
$customer = Mage::getModel("customer/customer");

$customer->setWebsiteId(Mage::app()->getStore()->getWebsiteId());
$customer->loadByEmail($email);
$mage_id=$customer->getId();


if($mage_id==$id){
    $img="customerpic/$mage_id.jpg";
    if(file_exists($img)):
    $imagelocaton=Mage::getBaseUrl()."/mob_api/$img";
    else:
    $imagelocaton='no image found';
    endif;
$ret['info']['firstname'] = $customer->getFirstname();
$ret['info']['lastname'] = $customer->getFirstname();
$ret['info']['email'] = $customer->getEmail();
$ret['info']['image'] = $imagelocaton;
$billing = $customer->getDefaultBillingAddress();

if (!empty($billing)) {
$ret['billing']['prefix'] =$billing->getFirstname() ;
$ret['billing']['firstname'] =$billing->getFirstname() ;
$ret['billing']['middlename'] = $billing->getMiddlename();
$ret['billing']['company'] = $billing->getCompany();
$ret['billing']['street'] = $billing->getStreet();
$ret['billing']['city'] = $billing->getCity();
;
$ret['billing']['country'] = $billing->getCountry();
$ret['billing']['postcode'] = $billing->getPostcode();
$ret['billing']['telephone'] = $billing->getTelephone(); 
$ret['billing']['fax'] =$billing->getFax() ;
}

if (!empty($shipping)) {
$shipping = $customer->getDefaultShipping();
$ret['shipping']['prefix'] =$shipping->getFirstname() ;
$ret['shipping']['firstname'] =$shipping->getFirstname() ;
$ret['shipping']['middlename'] = $shipping->getMiddlename();
$ret['shipping']['company'] = $shipping->getCompany();
$ret['shipping']['street'] = $shipping->getStreet();
$ret['shipping']['city'] = $shipping->getCity();
;
$ret['shipping']['country'] = $shipping->getCountry();
$ret['shipping']['postcode'] = $shipping->getPostcode();
$ret['shipping']['telephone'] = $shipping->getTelephone(); 
$ret['shipping']['fax'] =$shipping->getFax() ;

}
   
   
    
    
    
    
    
}

else{
    
$ret['error']="Id and Email do not match please to login customer";   
    
}
 $result=json_encode($ret) ;
 echo $result;
?>