<?php

/**
 * @author kem parson
 * @copyright 2016
 */
ini_set('display_errors','On');
error_reporting(E_ALL);
require_once ($_SERVER['DOCUMENT_ROOT'] .'/app/Mage.php');
Mage::app();
Mage::app()->getTranslator()->init('frontend');
Mage::getSingleton('core/session', array('name' => 'frontend'));
$searchstring=$_REQUEST['q'];


$product_collection = Mage::getResourceModel('catalog/product_collection')
                  ->addAttributeToSelect('*')
                  ->addAttributeToFilter('name', array('like' => '%'.$searchstring.'%'))
                  ->load();
foreach($product_collection as $product) {
 $id=$product->getId();
    $ret[$id]['id']=$id;
    $ret [$id]['name']=strip_tags($product->getName());
    $ret[$id] ['price']= number_format($product->getFinalPrice(),2) ;  
    $ret[$id]['regular_price']=number_format($product->getPrice(),2);
    $ret[$id] ['shortdescription']=strip_tags($product->getShortDescription());
    $ret[$id] ['description']=strip_tags($product->getDescription());
    $productimage=Mage::helper('catalog/image')->init($product, 'image');
    $ret[$id] ['image']=(string)$productimage;
}
$ret=array_values($ret);
$result=json_encode($ret,JSON_PRETTY_PRINT) ;   
echo $result;
?>