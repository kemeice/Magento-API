<?php
$email = $_POST['email'];
$password = $_POST[pass];



function getOauthAccessKeyAndSecret($oauthConsumerKey,$oauthConsumerSecret,$username,$password,$baseurl){

	//initiate
	$realm = $baseurl;
	$endpointUrl = $realm."oauth/initiate";
	$oauthCallback = $baseurl;
	$oauthNonce = uniqid(mt_rand(1, 1000));
	$oauthSignatureMethod = "HMAC-SHA1";
	$oauthTimestamp = time();
	$oauthVersion = "1.0";
	$oauthMethod = "POST";
	
	
	$params = array(
		"oauth_callback" => $oauthCallback,
		"oauth_consumer_key" => $oauthConsumerKey,
		"oauth_nonce" => $oauthNonce,
		"oauth_signature_method" => $oauthSignatureMethod,
		"oauth_timestamp" => $oauthTimestamp,
		"oauth_version" => $oauthVersion,
	);
	$data = http_build_query($params);

	$encodedData = $oauthMethod."&".urlencode($endpointUrl)."&".urlencode($data);
	$key = $oauthConsumerSecret."&"; 
	$signature = hash_hmac("sha1",$encodedData, $key, 1); 
	$oauthSignature = base64_encode($signature);

	$header = "Authorization: OAuth realm=\"$realm\",";
	foreach ($params as $key=>$value){
		$header .=  $key.'="'.$value."\", ";
	}
	$header .= "oauth_signature=\"".$oauthSignature.'"';
	
	var_dump($header);

	$curl = curl_init();

	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array($header));
	curl_setopt($curl, CURLOPT_URL, $endpointUrl);

	$response = curl_exec($curl);
	curl_close($curl);

	$response = explode('&',$response);
	$key = explode('=',$response[0]);
	$secret = explode('=',$response[1]);
	$oauthkey = $key[1];
	$oauthsecret = $secret[1];

	echo $oauthkey.' '.$oauthsecret."\n";

	//authorize 

	$url = $baseurl.'oauth/authorize?oauth_token='.$oauthkey.'&username='.$username.'&password='.$password;

	$curl = curl_init();
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Must be set to true so that PHP follows any "Location:" header
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$a = curl_exec($ch); // $a will contain all headers

	$url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
	curl_close($ch);
	
	$url = explode('&',$url);
	$url = explode('=',$url[1]);
	$verifier = $url[1];
	

	//oauth access
	$endpointUrl = $realm."oauth/token";
	$params2 = array(
		'oauth_consumer_key' => $oauthConsumerKey,
		'oauth_nonce' => uniqid(mt_rand(1, 1000)),
		'oauth_signature_method' => 'HMAC-SHA1',
		'oauth_timestamp' => time(),
		'oauth_version' => '1.0',
        //'oauth_token' => 'token',
        //'oauth_verifier' => 'verifier',
		'oauth_token' => $oauthkey,
		'oauth_verifier' => $verifier,
	);

	$method = 'POST';
	// this is the url to get Request Token according to Magento doc
	$url = $endpointUrl;

	// start making the signature
	ksort($params2); // @see Zend_Oauth_Signature_SignatureAbstract::_toByteValueOrderedQueryString() for more accurate sorting, including array params 
	$sortedParamsByKeyEncodedForm = array();
	foreach ($params2 as $key => $value) {
		$sortedParamsByKeyEncodedForm[] = rawurlencode($key) . '=' . rawurlencode($value);
	}
	$strParams = implode('&', $sortedParamsByKeyEncodedForm);
	$signatureData = strtoupper($method) // HTTP method (POST/GET/PUT/...)
			. '&'
			. rawurlencode($url) // base resource url - without port & query params & anchors, @see how Zend extracts it in Zend_Oauth_Signature_SignatureAbstract::normaliseBaseSignatureUrl()
			. '&'
			. rawurlencode($strParams);

	$key = rawurlencode($oauthConsumerSecret) . '&' . rawurlencode($oauthsecret); 
	$oauthSignature = base64_encode(hash_hmac('SHA1', $signatureData, $key, 1));

	$header = "Authorization: OAuth realm=\"$realm\",";
	foreach ($params2 as $key=>$value){
		$header .=  $key.'="'.$value."\", ";
	}
	$header .= "oauth_signature=\"".$oauthSignature.'"';
	
	var_dump($header);

	$curl = curl_init();

	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array($header));
	curl_setopt($curl, CURLOPT_URL, $endpointUrl);

	$response = curl_exec($curl);
    curl_close($curl);


	$response = explode('&',$response);
	$access_key = explode('=',$response[0]);
	$access_key = $access_key[1];
	$access_secret = explode('=',$response[1]);
	$access_secret = $access_secret[1];


	echo $access_key.','.$access_secret;

}


getOauthAccessKeyAndSecret($key,$secrect ,$email, $password,'http://domain.com');

