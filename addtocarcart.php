<?php

/**
 * @author cart
 * @copyright 2016
 */

//ini_set('display_errors','On');
//error_reporting(E_ALL);

$qty = $_POST['qty'];
$productId=$_POST['prodid'];
$customer=$_POST['custid'];
//if(isset($_POST['options']))
//$session=$_POST['session'];



require_once ($_SERVER['DOCUMENT_ROOT'] .'/app/Mage.php');
Mage::app();
Mage::app()->getTranslator()->init('frontend');
Mage::getSingleton('core/session', array('name' => 'frontend'));

$error='false';
$quote = Mage::getModel('sales/quote')->loadByCustomer($customer);
$quote->addProduct($productId, $qty);
try {
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