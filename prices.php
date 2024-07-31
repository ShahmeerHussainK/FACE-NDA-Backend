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
$arr = array("0" => "28","1" => "53","2" => "99", "3" => "99" , "4" => "99", "5" => "99");
echo json_encode($arr);
?>
