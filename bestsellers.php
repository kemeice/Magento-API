<?php

/**
 * @author kem parson
 * @copyright 2016
 */

//ini_set('display_errors','On');
//error_reporting(E_ALL);
require_once ($_SERVER['DOCUMENT_ROOT'] .'/app/Mage.php');
Mage::app();
Mage::app()->getTranslator()->init('frontend');
Mage::getSingleton('core/session', array('name' => 'frontend'));
$storeId    = Mage::app()->getStore()->getId();
 
$productsCollection = Mage::getResourceModel('reports/product_collection')
            ->addOrderedQty()
            ->setStoreId($storeId)
            ->addStoreFilter($storeId)
            ->setOrder('ordered_qty', 'desc')
			->setPageSize(50); 
 
Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($productsCollection);
Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($productsCollection);
 
foreach($productsCollection as $product){
       $img= Mage::helper('catalog/image')->init($product, 'small_image');
    $id=$product->getId();
	$ret[$id]['id']=$id;
    
    $info=Mage::getModel('catalog/product')->getCollection()
        ->addAttributeToFilter('entity_id', $id)
        ->addAttributeToSelect('*')
        ->getFirstItem();
    
    $ret[$id]['id']=$id;
    $ret[$id]['name']=strip_tags($info->getName());
    $ret[$id] ['price']= number_format($info->getFinalPrice(),2) ;  
    $ret[$id]['regular_price']=number_format($info->getPrice(),2);
    $ret[$id] ['shortdescription']=strip_tags($info->getShortDescription());
    
    $ret[$id]['img']=(string) $img;
}


$ret=array_values($ret);       
$result=json_encode($ret);
echo $result;

?>