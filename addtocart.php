<?php

/**
 * 
 * @copyright 2016
 */

ini_set('display_errors','On');
error_reporting(E_ALL);

$qty = $_REQUEST['qty'];
$productId=$_REQUEST['prodid'];
$customer=$_REQUEST['custid'];
//if(isset($_POST['options']))
//$session=$_POST['session'];



require_once ($_SERVER['DOCUMENT_ROOT'] .'/app/Mage.php');
Mage::app();
Mage::app()->getTranslator()->init('frontend');
Mage::getSingleton('core/session', array('name' => 'frontend'));

$error='false';
try {
$product = Mage::getModel('catalog/product')->load($productId);
$cust = Mage::getModel('customer/customer')->load($customer);


$quote = Mage::getModel('sales/quote')->loadByCustomer($cust);
$quote->addProduct($product, $qty);

$quote->collectTotals()->save();

Mage::getSingleton('checkout/session')->setCartWasUpdated(true);
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


//$product = Mage::getModel('catalog/product')->load($productId);
//$cart = Mage::getModel('checkout/cart');
//$cart->init();
//$cart->addProduct($product, array('qty' => $qty));
//$cart->save();
//Mage::getSingleton('checkout/session')->setCartWasUpdated(true);



?>