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

$cat_id=$_REQUEST['id'];
$category = Mage::getModel('catalog/category')->load($cat_id);

$products = Mage::getModel('catalog/product')
->getCollection()
->addAttributeToSelect('*')
->addCategoryFilter($category)
->load();

foreach($products as $product){
	$img= Mage::helper('catalog/image')->init($product, 'small_image');
    
    $id=$product->getId();
    $ret[$id]['id']=$id;
    $ret[$id]['name']=strip_tags($product->getName());
    $ret[$id] ['price']= number_format($product->getFinalPrice(),2) ;  
    $ret[$id]['regular_price']=number_format($product->getPrice(),2);
    $ret[$id] ['shortdescription']=strip_tags($product->getShortDescription());
    if($product->getRatingSummary()):
    $ret[$id] ['reviews']=$product->getRatingSummary();
    else:
    $ret[$id] ['reviews']='None';
    endif;
    $ret[$id]['img']=(string) $img;
    
    
    
    
    
    }
    
 $ret=array_values($ret);
 $result=json_encode($ret,JSON_PRETTY_PRINT) ;   

echo $result;
?>