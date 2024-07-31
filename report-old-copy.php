<?php
function setValue($value) {
    $str = str_replace("B", "D", $value, $count);
        if($count > 0) {
            $sheet->setCellValue($str, $_GET[$value]);
        } else { 
            $str = str_replace("D", "B", $value, $count);
            if($count > 0) {
                $sheet->setCellValue($str, $_GET[$value]);
            } else { 
                $str = str_replace("32", "33", $value, $count);
                if($count > 0) {
                    $sheet->setCellValue($str, $_GET[$value]);
                } else { 
                    $str = str_replace("33", "32", $value, $count);
                    $sheet->setCellValue($str, $_GET[$value]);
                }
            }
        }

}
require_once('../wp-load.php'); 
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST,OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
/*$files = glob(__DIR__ . '/tmp/*.xlsm'); // get all file names
foreach($files as $file){ // iterate files
    if((time() -  filemtime($file)) > 3600) {
        unlink($file);
    }
}
*/
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    die;
}
error_reporting(-1);
ini_set('display_errors', 'On');
$inputFileType = 'Xlsx';
$type="Basic";
if(isset($_POST['D2'])) {
    $type = $_POST['D2'];
} else if(isset($_GET['D2'])) {
    $type = $_GET['D2'];
}
$inputFileName = __DIR__ . '/report_'.$type.'.xlsm';
require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
$reader = IOFactory::createReader($inputFileType);
$spreadsheet = $reader->load($inputFileName);
$sheet = $spreadsheet->getSheetByName('Edit This Sheet');
$arr = array('B19','B20','B21','B22','B23','B24','B25','B26','B27','D19','D20','D21','D22','D23','D24','D25','D26','D27','D2','A32','A33');
foreach ($arr as $value) {
    if(isset($_GET[$value]))
    {           
        setValue($value);
    }
}                               
foreach ($arr as $value) {
    if(isset($_POST[$value]))
    {           
        setValue($value);
    }
}                               
$name = $sheet->getCell('D1')->getCalculatedValue();
$writer = new Xlsx($spreadsheet);
$fullname = __DIR__ . '/reports/'. $name . '.xlsm';
$writer->save($fullname);
echo $name;
?>
