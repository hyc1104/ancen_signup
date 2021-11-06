<?php
use Xmf\Request;
use XoopsModules\Ancen_signup\Ancen_signup_actions;
use XoopsModules\Ancen_signup\Ancen_signup_data;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';

if (!$_SESSION['can_add']) {
    redirect_header($_SERVER['PHP_SELF'], 3, "您沒有權限使用此功能");
}

$id = Request::getInt('id');

$action = Ancen_signup_actions::get($id);

require_once XOOPS_ROOT_PATH . '/modules/tadtools/tcpdf/tcpdf.php';
$pdf = new TCPDF("P", "mm", "A4", true, 'UTF-8', false);
$pdf->setPrintHeader(false); //不要頁首
$pdf->setPrintFooter(false); //不要頁尾
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM); //設定自動分頁
$pdf->setFontSubsetting(true); //產生字型子集（有用到的字才放到文件中）
$pdf->SetFont('droidsansfallback', '', 11, '', true); //設定字型
$pdf->SetMargins(15, 15); //設定頁面邊界，
$pdf->AddPage(); //新增頁面，一定要有，否則內容出不來

$title = "{$action['title']}簽到表";
$pdf->SetFont('droidsansfallback', 'B', 24, '', true); //設定字型
$pdf->MultiCell(190, 0, $title, 0, "C");

$pdf->SetFont('droidsansfallback', '', 16, '', true); //設定字型
$pdf->Cell(40, 20, '活動日期', 0, 0);
$pdf->Cell(150, 20, $action['action_date'], 0, 1);

//$pdf->Cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = 0, $link = nil, $stretch = 0, $ignore_min_height = false, $calign = 'T', $valign = 'M');

$h = 15;
$maxh = 15;
$pdf->Cell(15, $h, '編號', 1, 0, 'C');
$pdf->Cell(40, $h, '姓名', 1, 0, 'C');
$pdf->Cell(35, $h, '飲食', 1, 0, 'C');
$pdf->Cell(90, $h, '簽名', 1, 1, 'C');

$signup = Ancen_signup_data::get_all($action['id'], null, true, true);
$i = 1;
foreach ($signup as $signup_data) {
    $pdf->MultiCell(15, $h, $i, 1, 'C', false, 0, '', '', true, 0, false, true, $maxh, 'M');
    $pdf->MultiCell(40, $h, implode($signup_data['tdc']['姓名']), 1, 'C', false, 0, '', '', true, 0, false, true, $maxh, 'M');
    $pdf->MultiCell(35, $h, implode($signup_data['tdc']['飲食']), 1, 'C', false, 0, '', '', true, 0, false, true, $maxh, 'M');
    $pdf->MultiCell(90, $h, '', 1, 'C', false, 1, '', '', true, 0, false, true, $maxh, 'M');
    $i++;
}

//PDF內容設定
$pdf->Output("{$title}.pdf", 'D');
