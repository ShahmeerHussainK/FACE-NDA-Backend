<?php
require_once('../wp-load.php'); 
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST,OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    die;
}
error_reporting(-1);
ini_set('display_errors', 'On');
$arr1 = array("0" => "11.40","1" => "53","2" => "99", "3" => "99" , "4" => "99", "5" => "99","6" => "6.95");
$arr2 = array("0" => "11","1" => "53","2" => "99", "3" => "99" , "4" => "99", "5" => "99","6" => "6");
$arr3 = array("0" => "40","1" => "00","2" => "00", "3" => "00" , "4" => "00", "5" => "00","6" => "95");
$arr = array('prices' => $arr1, 'pricesround' => $arr2, 'pricescents' => $arr3);

echo json_encode($arr);
?>
