<?php
  require_once('../wp-load.php'); // add wordpress functionality
    $url = "https://api-3t.paypal.com/nvp";
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST,OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        die;
    }
    if(!isset($_POST['METHOD'])) {
        $inputJSON = file_get_contents('php://input');
        $post = json_decode($inputJSON, TRUE);
    } else {
        $post = $_POST;
    }
    $fields = array(
    'VERSION' => '84.0',
	'IPADDRESS' => urlencode($post['IPADDRESS']),
	'BUTTONSOURCE' => urlencode($post['BUTTONSOURCE']),
	'INVNUM' => urlencode($post['INVNUM']),
	'CURRENCYCODE' => urlencode($post['CURRENCYCODE']),
	'COUNTRYCODE' => urlencode($post['COUNTRYCODE']),
	'STATE' => urlencode($post['STATE']),
	'CITY' => urlencode($post['CITY']),
	'ZIP' => urlencode($post['ZIP']),
	'CREDITCARDTYPE' => urlencode($post['CREDITCARDTYPE']),
	'LASTNAME' => urlencode($post['LASTNAME']),
	'STREET' => urlencode($post['STREET']),
	'FIRSTNAME' => urlencode($post['FIRSTNAME']),
	'CVV2' => urlencode($post['CVV2']),
	'EXPDATE' => urlencode($post['EXPDATE']),
        "ACCT" => urlencode($post['ACCT']),
        "METHOD" => urlencode($post['METHOD']),
        "PAYMENTACTION" => urlencode($post['PAYMENTACTION']),
        "AMT" => urlencode($post['AMT']),
        "PWD" => "GGFVQ9RA96Q5BW58",
        "USER" => "faceitdna_api1.gmail.com",
        "SIGNATURE" => "ArSJn3A3bWm.P0RiPfs618kKyRSxAUtE5J8jPzNGJEov2f1VmeJk5tQk"
);
        foreach($fields as $key=>$value) { 
            $fields_string .= $key.'='.$value.'&'; 
        }
        rtrim($fields_string, '&');
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $result = curl_exec($ch);
        curl_close($ch);
        echo $result;
?>
