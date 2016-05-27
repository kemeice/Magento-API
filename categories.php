
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
/* Load category by id*/
$cat = Mage::getModel('catalog/category')->load('2');


/*Returns comma separated ids*/
$subcats = $cat->getChildren();

//Print out categories string
#print_r($subcats);

foreach(explode(',',$subcats) as $subCatid)
{
  $_category = Mage::getModel('catalog/category')->load($subCatid);
  if($_category->getIsActive())
  {
    $catid    = $_category->getId();
    $catname     = $_category->getName();
    if($_category->getImageUrl())
    {
      $catimg     = $_category->getImageUrl();
    }
    $ret[$catid]['id'] =$catid;
    $ret[$catid]['name'] =$catname;
    $ret[$catid]['img'] =$catimg;
  
  $secondlevel=$_category->getChildren();
  if (count($secondlevel) > 0){
  foreach(explode(',',$secondlevel) as $level)
{
    $level2= Mage::getModel('catalog/category')->load($level);
    
    if($level2->getIsActive())
    {
        $id    = $level2->getId();
    $name     = $level2->getName();
    if($level2->getImageUrl())
    {
      $img     = $level2->getImageUrl();
    }
    $ret[$id]['id'] =$id;
    $ret[$id]['name'] =$name;
    $ret[$id] ['img'] =$img; 
    $ret[$id] ['parentcategory'] =$catid;
        
    }
    
    }
  
    
  }
  }
}


 $ret=array_values($ret);
 $result=json_encode($ret,JSON_PRETTY_PRINT) ;

 
 
 
 
 echo $result;
// echo $results2;

?>
