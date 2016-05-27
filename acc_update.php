<?php

/**
 * @author kem parson 
 * @copyright 2016
 */
$id=$_POST['id'];
$email=$_POST['email'];
require_once ($_SERVER['DOCUMENT_ROOT'] .'/app/Mage.php');
Mage::app('default');

umask(0);
Mage::init();
Mage::getSingleton('core/session', array('name' =>'frontend'));
$customer = Mage::getModel("customer/customer");

$customer->setWebsiteId(Mage::app()->getStore()->getWebsiteId());
$customer->loadByEmail($email);
$mage_id=$customer->getId();


if($mage_id==$id){
    // create each post as a function
$errors ='false';
 $file=$_FILES['info_image'];
 $ending = '.' . strtolower(end(explode(".", $file['name'])));
 $allowedtypes = array(
            'tiff' => 'image/tiff',
            'jpg' => 'image/jpg',
            'png' => 'image/png',
            'gif' => 'image/gif',
 
 
 );
 
  $ext = in_array(str_replace('.','',$ending), array_keys($allowedtypes));
   if ($ext === false):
      $ret['error'] = 'Only .tiff, .jpg, .png  .gif  files are supported.';
      $errors="true";
      endif;
    
    
  if (in_array($ending,array_keys($allowedtypes))) {
    $ret['error'] = 'Only .tiff, .jpg, .png  .gif  files are supported.';
    $errors="true";
    
    }
    
     if (($file["size"] > 200000)) {
            
            $ret['error'] = 'maximum fize size exceeded ';
            $errors="true";
        }

        if (!is_numeric($file["size"])) {
            $ret['error'] = 'Size of file is not a number.';
            $errors="true";
            
        }

        if ($file["error"] > 0) {
            $ret['error'] = $file["error"];
             $errors="true";
        }
if ($errors=='false'):
    $sourcePath = $file['tmp_name'];
    $filename="$id.jpg";
    $targetPath = "customerpic/".$filename; 
    move_uploaded_file($sourcePath, $targetPath);
    $ret['sucess']='true';
    $ret['path']= $targetPath;
    endif;
    
    }//id mach email 
    
    
else{
    
$ret['error']="Id and Email do not match please to login customer";   
    
}


 $result=json_encode($ret) ;
 echo $result;

?>