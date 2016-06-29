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
 
    $totals=$quote->getTotals();
   
   
   
   
   $ret['cart']['itemscount']=(int) $amount;
   $ret['cart']['subtotal'] =$quote->getSubtotal();
   $ret['cart']['total'] =$quote->getGrandTotal();
   $ret['cart']['shipping'] =$quote->getShippingAddress()->getShippingAmount();;
   
   if(isset($totals['discount']))
     $ret['cart']['discount']= round($totals['discount']->getValue()); 
 else {
     $ret['cart']['discount']='';
}
  
 
   if(isset($totals['tax'])){
     $ret['cart']['tax']= round($totals['tax']->getValue()); 
} else {
      $ret['cart']['tax']= '';
}


$ret=array_values($ret);       
$result=json_encode($ret);
echo $result;





?>