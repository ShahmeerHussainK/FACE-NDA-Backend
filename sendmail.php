<?php
error_reporting(-1);
ini_set('display_errors', 'On');
$date = new DateTime();
$logStr = $date->format('Y-m-d H:i:s');
  require_once('../wp-load.php'); // add wordpress functionality
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST,OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        die;
    }
 $to = "facednatest@gmail.com,faceitdna@gmail.com,support@facednatest.com";
    if(!isset($_POST['title'])) {
        $_POST = json_decode(file_get_contents('php://input'), true);
    }
    $title = $_POST['title']; 
    if (stripos($title,'PRO')) {
        $type = 1;
        $template = file_get_contents('templatepro.html', FILE_USE_INCLUDE_PATH);
    } else {
        $type = 0;
        $template = file_get_contents('templatebasic.html', FILE_USE_INCLUDE_PATH);
    }
    
    $autoresp = file_get_contents('autoresponse.txt', FILE_USE_INCLUDE_PATH);
    $template = str_replace("{{name}}",$_POST['name'],$template);
    $template = str_replace("{{email}}",$_POST['email'],$template);
    $template = str_replace("{{phone}}",$_POST['phone'],$template);
    $template = str_replace("{{img1}}",$_POST['img1'],$template);
    $template = str_replace("{{img2}}",$_POST['img2'],$template);
    $template = str_replace("{{report}}",$_POST['report'],$template);

    $template = str_replace("{{paymentinfo}}",$_POST['paymentinfo'],$template);
    $template = str_replace("{{relationship}}",$_POST['relationship'],$template);
    $template = str_replace("{{relation}}",$_POST['relation'],$template);
    $template = str_replace("{{usedbefore}}",$_POST['usedbefore'],$template);
    $template = str_replace("{{childType}}",$_POST['childType'],$template);
    $template = str_replace("{{fatherType}}",$_POST['fatherType'],$template);
    $template = str_replace("{{childname}}",$_POST['childname'],$template);
    $template = str_replace("{{fathername}}",$_POST['fathername'],$template);
    $template = str_replace("{{childrace}}",$_POST['childrace'] ?? "Unknown",$template);
    $template = str_replace("{{fatherrace}}",$_POST['fatherrace'] ?? "Unknown",$template);
    if( $type == 1 ) {
        $template = str_replace("{{childbloodtype}}",$_POST['childbloodtype'] ?? "Unknown",$template);
        $template = str_replace("{{childcleftchin}}",$_POST['childcleftchin'] ?? "Unknown",$template);
        $template = str_replace("{{childdimples}}",$_POST['childdimples'] ?? "Unknown",$template);
        $template = str_replace("{{childearlobe}}",$_POST['childearlobe'] ?? "Unknown",$template);
        $template = str_replace("{{childeyebrowsconnected}}",$_POST['childeyebrowsconnected'] ?? "Unknown",$template);
        $template = str_replace("{{childeyecolor}}",$_POST['childeyecolor'] ?? "Unknown",$template);
        $template = str_replace("{{childhaircolor}}",$_POST['childhaircolor'] ?? "Unknown",$template);
        $template = str_replace("{{childwidowspeak}}",$_POST['childwidowspeak'] ?? "Unknown",$template);
        $template = str_replace("{{fatherbloodtype}}",$_POST['fatherbloodtype'] ?? "Unknown",$template);
        $template = str_replace("{{fathercleftchin}}",$_POST['fathercleftchin'] ?? "Unknown",$template);
        $template = str_replace("{{fatherdimples}}",$_POST['fatherdimples'] ?? "Unknown",$template);
        $template = str_replace("{{fatherearlobe}}",$_POST['fatherearlobe'] ?? "Unknown",$template);
        $template = str_replace("{{fathereyebrowsconnected}}",$_POST['fathereyebrowsconnected'] ?? "Unknown",$template);
        $template = str_replace("{{fathereyecolor}}",$_POST['fathereyecolor'] ?? "Unknown",$template);
        $template = str_replace("{{fatherhaircolor}}",$_POST['fatherhaircolor'] ?? "Unknown",$template);
        $template = str_replace("{{fatherwidowspeak}}",$_POST['fatherwidowspeak'] ?? "Unknown",$template);
        $template = str_replace("{{fatherfreckles}}",$_POST['fatherfreckles'] ?? "Unknown",$template);
        $template = str_replace("{{childfreckles}}",$_POST['childfreckles'] ?? "Unknown",$template);
    }
    $repnum = basename($_POST['report']);
    $repnum = str_replace(".xlsm","",$repnum);
    $title = $_POST['title'] . 'Report # ' . $repnum;
/*
 $headers = array('From: '.$_POST['email'],'Content-Type: text/html;charset = UTF-8');
*/
    $logStr = $logStr . serialize($_POST)."\n";
    $fp = fopen("maillogger.txt",'a');
    fwrite($fp,$logStr);
    if( !isset($_POST['paymentinfo']) || $_POST['paymentinfo'] == "") {
       echo '{"status" : 2 }';
	fwrite($fp,"ERROR LOG ***\n");
        die;
    }
   $headers = 'From: support@facednatest.com'."\r\n".'Content-Type: text/html;charset = UTF-8';
   $respheaders = 'From: support@facednatest.com'."\r\n".'Content-Type: text/plain;charset = UTF-8';
    $email = mail($to,$title,$template,$headers);
    $obj = '{
    "status" : '.$email.'}';

    $email = mail($_POST['email'],"Your submission has been received.",$autoresp,$respheaders);
    echo $obj;
?>
