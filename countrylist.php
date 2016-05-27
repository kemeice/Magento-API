<?php

/**
 * @author Extalionez
 * @copyright 2016
 */
//ini_set('display_errors','On');
//error_reporting(E_ALL); 
require_once ($_SERVER['DOCUMENT_ROOT'] .'/app/Mage.php');
require_once ('config.php');
$proxy = new SoapClient(Mage::getBaseUrl().'/api/v2_soap/?wsdl'); 
$sessionId = $proxy->login(APIUSER, APIKEY);
$countries = $proxy->directoryCountryList($sessionId);
foreach ($countries as $country):
$ret['country'][$country->country_id]=$country->name;
endforeach;


$ret=array_values($ret);
echo json_encode($ret);

?>