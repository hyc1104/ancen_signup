<?php
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';

require_once XOOPS_ROOT_PATH . '/modules/tadtools/vendor/phpoffice/phpexcel/Classes/PHPExcel.php'; //引入 PHPExcel 物件庫
require_once XOOPS_ROOT_PATH . '/modules/tadtools/vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php'; //引入PHPExcel_IOFactory 物件庫
$objPHPExcel = new PHPExcel(); //實體化Excel
//----------內容-----------//

$title = (_CHARSET === 'UTF-8') ? iconv('UTF-8', 'Big5', $title) : $title;
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename={$title}.xlsx");
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

// 避免excel下載錯誤訊息
for ($i = 0; $i < ob_get_level(); $i++) {
    ob_end_flush();
}
ob_implicit_flush(1);
ob_clean();
$objWriter->setPreCalculateFormulas(false);
$objWriter->save('php://output');
exit;
