<?php

/**
 * @author kem parson 
 * @copyright 2016
 */
//ini_set('display_errors','On');
//error_reporting(E_ALL);
$email= $_POST['email'];
$password= $_POST['pass'];
$error="false";
require_once ($_SERVER['DOCUMENT_ROOT'] .'/app/Mage.php');
Mage::app('default');
umask(0);
Mage::init();
Mage::getSingleton('core/session', array('name' =>'frontend'));
$customer = Mage::getModel("customer/customer");
$session = Mage::getSingleton('customer/session');
$customer->setWebsiteId(Mage::app()->getStore()->getWebsiteId());
$customer->loadByEmail($email);

        
    try {
                  $session->login($email, $password);
                  
                  
                    
                    
                } catch (Mage_Core_Exception $e) {
                    switch ($e->getCode()) {
                        case Mage_Customer_Model_Customer::EXCEPTION_EMAIL_NOT_CONFIRMED:
                            $ret['error'] = 'This account is not confirmed.';
                            $error="true";
                            break;
                        case Mage_Customer_Model_Customer::EXCEPTION_INVALID_EMAIL_OR_PASSWORD:
                             $ret['error'] = 'Invalid login or password.';
                             $error="true";
                            break;
                        default:
                            $message = $e->getMessage();
                            $ret['error'] = $message ;
                    } 
                    }   
        

if($error=='false')
{       $id=$customer->getId();
        $ret['sucess']="true";
        $ret['id']=$id;
        
        
    }   
    $result=json_encode($ret) ;
     echo $result;
?>