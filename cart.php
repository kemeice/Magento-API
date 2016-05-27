<?php

/**
 * @author cart
 * @copyright 2016
 */

//ini_set('display_errors','On');
//error_reporting(E_ALL);


$customer=$_REQUEST['custid'];
//if(isset($_POST['options']))
$session=$_POST['session'];





require_once ($_SERVER['DOCUMENT_ROOT'] .'/app/Mage.php');
Mage::app();
Mage::app()->getTranslator()->init('frontend');
Mage::getSingleton('core/session', array('name' => 'frontend'));
$items=0;
$error='false';
$cust = Mage::getModel('customer/customer')->load($customer);
$quote = Mage::getModel('sales/quote')->loadByCustomer($cust);
if(isset($_POST['session']))
$session =$_POST['session'];

else
$session = Mage::getSingleton("core/session")->getEncryptedSessionId();

$ret['checkout']['sid'] =$session;
 if ($quote) {
    
        $collection = $quote->getItemsCollection();
        if ($collection->count() > 0) {
            foreach( $collection as $item ) {
                $items++;
                $id=$item->getId();
                $ret[$id]['id']=$id;
                $ret[$id]['name']=$item->getName();
                $ret[$id]['qty']=$item->getQty();
                $amount +=$item->getQty();
                $ret[$id]['price']=$item->getPrice();
                
                

            }
        }
    }
    ///$quote->($quote->getData());
    $totals=$quote->getTotals();
   
   
   
   
   $ret['cart']['itemscount']=(int) $amount;
   $ret['cart']['subtotal'] =$quote->getSubtotal();
   $ret['cart']['total'] =$quote->getGrandTotal();
   $ret['cart']['shipping'] =$quote->getShippingAddress()->getShippingAmount();;
   //$ret['cart']['tax'] =$quote->getSubtotal();
   //$discount=$totals['discount']->getValue();
   if(isset($totals['discount']))
     $ret['cart']['discount']= round($totals['discount']->getValue()); 
 else {
     $ret['cart']['discount']='';
}
  
 // $tax=$totals['tax']->getValue();
   if(isset($totals['tax'])){
     $ret['cart']['tax']= round($totals['tax']->getValue()); 
} else {
      $ret['cart']['tax']= '';
}

//if(Mage::helper('checkout')->canOnepageCheckout() ){
 //$ret['cart']['Error']= 'You are not certified to purchase this product. Please take and pass your free certification';
//}
$ret=array_values($ret);       
$result=json_encode($ret);
echo $result;


//$product = Mage::getModel('catalog/product')->load($productId);
//$cart = Mage::getModel('checkout/cart');
//$cart->init();
//$cart->addProduct($product, array('qty' => $qty));
//$cart->save();
//Mage::getSingleton('checkout/session')->setCartWasUpdated(true);



?>