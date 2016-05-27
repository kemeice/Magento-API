<?php

/**
 * @author kem parson 
 * @copyright 2016
 */
 ini_set('display_errors','On');
error_reporting(E_ALL); 
$order=$_REQUEST['orderid'];
require_once ($_SERVER['DOCUMENT_ROOT'] .'/app/Mage.php');
require_once ('config.php');
Mage::app();
Mage::app()->getTranslator()->init('frontend');
Mage::getSingleton('core/session', array('name' => 'frontend'));
$proxy = new SoapClient(Mage::getBaseUrl().'/api/v2_soap/?wsdl'); 
$sessionId = $proxy->login(APIUSER, APIKEY);
$errors='false';
$ret=array();
try{
$result = $proxy->salesOrderInfo($sessionId, $order); 
//var_dump ($result);
$ret['orderid']=$result->increment_id;

$ret['order']['tax_amount']=$result->tax_amount;
$ret['order']['shipping_amount']=$result->shipping_amount;
$ret['order']['discount_amount']=$result->discount_amount;
$ret['order']['subtotal']=$result->subtotal;
$ret['order']['date']=$result->created_at;
$ret['order']['status']=$result->status;

$pay['order']['method']=$result->payment->method;
$pay['order']['po_number']=$result->payment->po_number;
$pay['order']['cc_type']=$result->payment->cc_type;
$pay['order']['cclast4']=$result->payment->cc_last4 ;

$ship['order']['firstname']=$result->shipping_address->firstname;
$ship['order']['lastname']=$result->shipping_address->lastname;
$ship['order']['company']=$result->shipping_address->company;
$ship['order']['street']=$result->shipping_address->street;
$ship['order']['city']=$result->shipping_address->city;
$ship['order']['region']=$result->shipping_address->region;
$ship['order']['postcode']=$result->shipping_address->postcode;
$ship['order']['country_id']=$result->shipping_address->country_id;
$ship['order']['telephone']=$result->shipping_address->telephone;
$ordership = Mage::getModel('sales/order')->loadByIncrementId($order);
$shipment = $ordership->getShipmentsCollection()->getFirstItem();
$shipmentIncrementId = $shipment->getIncrementId();
$ship['order']['shipmentid']=$shipmentIncrementId;

$bill['order']['firstname']=$result->billing_address->firstname;
$bill['order']['lastname']=$result->billing_address->lastname;
$bill['company']=$result->billing_address->company;
$bill['order']['street']=$result->billing_address->street;
$bill['order']['city']=$result->billing_address->city;
$bill['order']['region']=$result->billing_address->region;
$bill['order']['postcode']=$result->billing_address->postcode;
$bill['order']['country_id']=$result->billing_address->country_id;
$bill['order']['telephone ']=$result->billing_address->telephone;

foreach($result->items as $item):
$id= $item->product_id;
$items[$id]['id']=$id;
$items[$id]['name']=$item->name;
$items[$id]['sku']=$item->sku;
$items[$id]['price']=$item->price;
$items[$id]['qty']=(int)$item->qty_ordered;
//$items['qty']=$item->qty_ordered;



endforeach;













}

catch (Exception $e) {
$message = $e->getMessage();
$errors='true';
      $ret['error']=$message ;   
   
}
$ret=array_values($ret);
$pay=array_values($pay);
$ship=array_values($ship);
$bill=array_values($bill);
$items=array_values($items);


if($errors=='false'):

$retfinal=array("Order"=>$ret ,"payment"=>$pay,"shipment"=>$ship , 'billing'=>$bill,'items'=>$items);


echo json_encode($retfinal);
else:
json_encode($ret);

endif;

?>