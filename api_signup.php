<?php

/**
 * @author kem parson 
 * @copyright 2016
 */
//ini_set('display_errors','On');
//error_reporting(E_ALL); 
require_once ($_SERVER['DOCUMENT_ROOT'] .'/app/Mage.php');
Mage::app('default');
umask(0);
Mage::init();
//Mage::getSingleton('core/session', array('name' =>'frontend'));
$error="false";
$firstname = $_POST['first'];
$lasttname = $_POST['last'];
$email = $_POST['email'];
$pass = $_POST['pass'];
$lisence = $_POST['lis'];
$mobile = $_POST['mobile'];

$websiteId = Mage::app()->getWebsite()->getId();
$store = Mage::app()->getStore();

$customer = Mage::getModel("customer/customer");
            $customer->setWebsiteId($websiteId)
             ->setStore($store)
            ->setFirstname($firstname)
            ->setLastname($lasttname)
            ->setEmail($email)
            ->setLe_valid_license($lisence)
            ->setLe_phone_number($mobile)
            ->setPassword($pass);
            
 
try{
    $customer->save();
}
catch (Exception $e) {
    //Zend_Debug::dump($e->getMessage());
    $message = $e->getMessage();
    $ret['error'] = $message ;
    
    $error='true';
}

if($error=='false')
{       $id=$customer->getId();
        $ret['sucess']="true";
        $ret['id']=$id;
        
        
    }   
    $result=json_encode($ret) ;
     echo $result;
?>