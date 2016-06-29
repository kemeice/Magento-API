<?php

/**
 * @author kem parson
 * @copyright 2016
 */
ini_set('display_errors','On');
error_reporting(E_ALL);
require_once ($_SERVER['DOCUMENT_ROOT'] .'/app/Mage.php');
Mage::app();
//Mage::app()->getTranslator()->init('frontend');
//Mage::getSingleton('core/session', array('name' => 'frontend'));
$product_id=$_REQUEST['id'];
$customer=$_REQUEST['custid'];
require_once ('config.php');


   
   $proxy = new SoapClient(Mage::getBaseUrl().'/api/v2_soap/?wsdl'); 
$sessionId = $proxy->login(APIUSER, APIKEY);
try{
$result = $proxy->customerCustomerInfo($sessionId, $customer);    

$groupId=$result->group_id;

}

 catch (Exception $e) {
$message = $e->getMessage();
    
}  

try{
$result2 = $proxy->catalogProductInf($sessionId,$product_id );    
$attri= $result2->additional_attributes->product_group;
//echo $attri;
//var_dump($attri);
}

 catch (Exception $e) {
$message = $e->getMessage();
    
}  




  $cust = Mage::getModel('customer/customer')->load($customer); 
  Mage::register('current_customer', $cust);
  
  
 
  $group = Mage::getModel('customer/group')->load($groupId);
		  $group_code= $group->getCode();
          //echo $group_code ;
          //var_dump ($group_code);
    $attributeValue= Mage::getModel('catalog/product')
			->load($product_id)
			->getAttributeText('product_group'); 
            
     //var_dump($attributeValue);     
     if(is_array($attributeValue)){
         if (in_array($group_code, $attributeValue)) {
         $canbuy='1';
         }
      else
      
      if($attributeValue==$group_code)
        $canbuy='1';
 
    
     
    }     
  
  
  
$product =Mage::getModel('catalog/product')->getCollection()
        ->addAttributeToFilter('entity_id', $product_id)
        ->addAttributeToSelect('*')
        ->getFirstItem();

       
		


;
//foreach($products as $product){
    $id=$product->getId();
    $ret[$id]['id']=$id;
    $ret [$id]['name']=strip_tags($product->getName());
    $ret[$id] ['price']= number_format($product->getFinalPrice(),2) ;  
    $ret[$id]['regular_price']=number_format($product->getPrice(),2);
    $ret[$id] ['shortdescription']=strip_tags($product->getShortDescription());
    $ret[$id] ['description']=strip_tags($product->getDescription());
    //can buy not necessary for all stores 
	if($product->isSaleable() && $canbuy=='1'):
    $ret[$id]['avalable']='true';
    else:
    $ret[$id]['avalable']='false';
    endif;
    $productimage=Mage::helper('catalog/image')->init($product, 'small_image');
    $ret[$id] ['image']=(string)$productimage;
    //if($product->isConfigurable()) {
        //$ret[$id]['options']='true';
        //$productAttributeOptions = $product->getTypeInstance(true)->getConfigurableAttributesAsArray($product);
        //foreach ($productAttributeOptions as $productAttribute) {
       //foreach ($productAttribute['values'] as $attribute) {
       // $ret['attributes'][$productAttribute['label']][$attribute['value_index']] = $attribute['store_label'];
        
            //}
          //  }
        //}
        
     if( $product->getTypeId() == 'grouped' ): 
     $ret[$id]['grouped']='true';
      //$_helper = Mage::helper('catalog/output'); 
    $_associatedProducts = $associatedProducts = $product->getTypeInstance(true)->getAssociatedProducts($product);
     //var_dump($_associatedProducts);
      foreach ($_associatedProducts as $_item):
      $id=$_item->getId();
      $ass[$id]['id']=$id;
      $ass[$id]['name'] =$_item->getName();
      $ass[$id]['price'] =number_format($_item->getPrice(),2); 
      $ass[$id]['type'] ="grouped";
      
      
      
      
      
      
      endforeach;
     
     
     
     
     
     endif;  
    
    if($product->getRatingSummary()):
    $ret[$id] ['reviews']=$product->getRatingSummary();
    else:
    $ret[$id] ['reviews']='None';
    endif;
    
    $allRelatedProductIds = $product->getRelatedProductIds();
    //var_dump($allRelatedProductIds );
    foreach ($allRelatedProductIds as $ids) {
            $relatedProduct = Mage::getModel('catalog/product')->getCollection()
        ->addAttributeToFilter('entity_id', $ids)
        ->addAttributeToSelect('*')
        ->getFirstItem();
        $rel['related'][$ids]['id']=$ids;
        $rel['related']  [$ids]['type'] ="related";
            // get Product's name
            $ret['related'] [$ids]['name']= $relatedProduct->getName();
            //echo $relatedProduct->getName();

            // get product's short description
            //echo $relatedProduct->getShortDescription();
              $ret['related'][$ids]['getShortDescription']=$relatedProduct->getShortDescription(); 
            // get Product's Long Description
           

            // get Product's Regular Price
            //echo $relatedProduct->getPrice();
            $rel['related'][$ids]['price']= number_format($relatedProduct->getPrice(),2);
            
            $img=Mage::helper('catalog/image')->init($relatedProduct, 'small_image');
            $rel['related'][$ids]['img']=(string)$img;
           

        }
    
    
    
     
     
    //$ret[$id]['img']=$product->getProductUrl();
    
    
    
    
    
    //}







//echo array_to_json($ret);
$ass=array_values($ass);
$rel=array_values($rel);
$ret=array_values($ret);
$retfinal=array("product"=>$ret ,"related"=>$rel,"grouped"=>$ass  );
$result=json_encode($retfinal) ;   
echo $result;
?>