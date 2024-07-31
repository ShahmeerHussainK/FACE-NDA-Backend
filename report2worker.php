<?php
$date = new DateTime();
$logStr = $date->format('Y-m-d H:i:s');
function setValue($value, $sheet) {
    global $logStr;
    if($value == 'D2') {
        $str = 'D2';
        $sheet->setCellValue($value, $_GET[$value]);    
    } else {
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
    $logStr = $logStr . $str . ' = ' . $_GET[$value] . ' ';
}
function setPostValue($value, $sheet) {
    global $logStr;
    if($value == 'D2') {
        $str = 'D2';
        $sheet->setCellValue($value, $_POST[$value]);
    } else {
        $str = str_replace("B", "D", $value, $count);
        if($count > 0) {
            $sheet->setCellValue($str, $_POST[$value]);
        } else { 
            $str = str_replace("D", "B", $value, $count);
            if($count > 0) {
                $sheet->setCellValue($str, $_POST[$value]);
            } else { 
                $str = str_replace("32", "33", $value, $count);
                if($count > 0) {
                    $sheet->setCellValue($str, $_POST[$value]);
                } else { 
                    $str = str_replace("33", "32", $value, $count);
                    $sheet->setCellValue($str, $_POST[$value]);
                }
            }
        }
    }
    $logStr = $logStr . $str . ' = ' . $_POST[$value] . ' ';
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
require __DIR__ . '/detect/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
$reader = IOFactory::createReader($inputFileType);
$spreadsheet = $reader->load($inputFileName);
$sheet = $spreadsheet->getSheetByName('Edit This Sheet');
$sheet2 = $spreadsheet->getSheetByName('Summary');
$arr = array('B19','B20','B21','B22','B23','B24','B25','B26','B27','D19','D20','D21','D22','D23','D24','D25','D26','D27','D2','A32','A33');
foreach ($arr as $value) {
    if(isset($_GET[$value]))
    {           
        setValue($value,$sheet);
    }
}                               
foreach ($arr as $value) {
    if(isset($_POST[$value]))
    {           
        setPostValue($value,$sheet);
    }
}                               
$name = $sheet->getCell('D1')->getCalculatedValue();
$name1 = $sheet->getCell('A32')->getValue();
$name2 = $sheet->getCell('A33')->getValue();
$name1 = str_replace("https://facednatest.com/", "../../", $name1);
$name1 = str_replace("http://facednatest.com/", "../../", $name1);
$name2 = str_replace("https://facednatest.com/", "../../", $name2);
$name2 = str_replace("http://facednatest.com/", "../../", $name2);
#echo "name1 =" .$name1."\n";
#echo "name2 =" .$name2."\n";
$output = shell_exec('./detect/detect.sh '.$name1.' '.$name2);
#echo $output ."\n";
$oparray = preg_split('/\s+/', trim($output));
if(!empty($oparray) && sizeof($oparray) >= 95) {
   for($i=3; $i<98; $i++) {
      $sheet2->setCellValue('A'.$i, $oparray[$i-3]); 
   }
}
else {
   for($i=3; $i<98; $i++) {
      $sheet2->setCellValue('A'.$i, 'NA'); 
   }
   $sheet->setCellValue('A34','Facedetect script failed!');
}
if(!empty($oparray) && sizeof($oparray) >= 190) {
   for($i=3; $i<98; $i++) {
      $sheet2->setCellValue('B'.$i, $oparray[95+$i-3]); 
   }
} 
else {
   for($i=3; $i<98; $i++) {
      $sheet2->setCellValue('B'.$i, 'NA'); 
   }
   $sheet->setCellValue('A34','Facedetect script failed!');
}
$writer = new Xlsx($spreadsheet);
$pdfWriter = IOFactory::createWriter($spreadsheet,'Mpdf');
$fullname = __DIR__ . '/reports/'. $name . '.xlsm';
$fullname2 = __DIR__ . '/reports/'. $name . '_1.pdf';
$fullname3 = __DIR__ . '/reports/'. $name . '_2.pdf';
$fullname4 = __DIR__ . '/reports/'. $name . '.pdf';
$writer->save($fullname);
$pdfWriter->setSheetIndex(0);
$pdfWriter->save($fullname2);
$pdfWriter->setSheetIndex(1);
$pdfWriter->save($fullname3);
$fileArray = array($fullname2, $fullname3);
$cmd = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=$fullname4 ";
foreach($fileArray as $file) {
	$cmd .= $file." ";
}
$result = shell_exec($cmd);
echo $name;
$fp = fopen('reportlogger1.txt', 'a');
$logStr = $logStr . "\n";
fwrite($fp,$logStr);
?>
