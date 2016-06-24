<?php

/**
 * 
 * @copyright 2016
 */
//ini_set('display_errors','On');
//error_reporting(E_ALL); 
require_once ($_SERVER['DOCUMENT_ROOT'] .'/app/Mage.php');
require_once ('config.php');
$proxy = new SoapClient(Mage::getBaseUrl().'/api/v2_soap/?wsdl'); 
$sessionId = $proxy->login(APIUSER, APIKEY);
$result = $proxy->directoryRegionList($sessionId,US);
//var_dump($result);
$i=0;
foreach ($result as $region):

$ret[$i]['region_id' ]=$region->region_id;
$ret[$i]['name ' ]=$region->name;
$i++;
endforeach;


$ret=array_values($ret);
echo json_encode($ret);

?>