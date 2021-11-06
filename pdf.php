<?php
use Xmf\Request;
use XoopsModules\Ancen_signup\Ancen_signup_actions;
use XoopsModules\Ancen_signup\Ancen_signup_data;

/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';

if (!$_SESSION['can_add']) {
    redirect_header($_SERVER['PHP_SELF'], 3, "您沒有權限使用此功能");
}

$id = Request::getInt('id');

$action = Ancen_signup_actions::get($id);

if ($action['uid'] != $xoopsUser->uid()) {
    redirect_header($_SERVER['PHP_SELF'], 3, "您沒有權限使用此功能");
}

$title = $action['title'];
$html[] = "<h1>{$title}報名名單</h1>";
$html[] = '<table border="1" cellpadding="3">';

$head_row = explode("\n", $action['setup']);
$head = [];
foreach ($head_row as $head_data) {
    $cols = explode(',', $head_data);
    if (strpos($cols[0], '#') === false) {
        $head[] = str_replace('*', '', trim($cols[0]));
    }
}

$head[] = '錄取';
$head[] = '報名日期';
$head[] = '身份';

$html[] = "<tr><th>" . implode("</th><th>", $head) . "</th></tr>";

$signup = Ancen_signup_data::get_all($action['id']);

foreach ($signup as $signup_data) {
    $item = [];
    foreach ($signup_data['tdc'] as $user_data) {
        $item[] = implode('|', $user_data);
    }

    if ($signup_data['accept'] === '1') {
        $item[] = '錄取';
    } elseif ($signup_data['accept'] === '0') {
        $item[] = '未錄取';
    } else {
        $item[] = '尚未設定';
    }

    $item[] = $signup_data['signup_date'];
    $item[] = $signup_data['tag'];

    $html[] = "<tr><td>" . implode("</td><td>", $item) . "</td></tr>";

}

$html[] = "</table>";

$html_content = implode('', $html);

require_once XOOPS_ROOT_PATH . '/modules/tadtools/tcpdf/tcpdf.php';
$pdf = new TCPDF("L", "mm", "A4", true, 'UTF-8', false);
$pdf->setPrintHeader(false); //不要頁首
$pdf->setPrintFooter(false); //不要頁尾
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM); //設定自動分頁
$pdf->setFontSubsetting(true); //產生字型子集（有用到的字才放到文件中）
$pdf->SetFont('droidsansfallback', '', 11, '', true); //設定字型
$pdf->SetMargins(15, 15); //設定頁面邊界，
$pdf->AddPage(); //新增頁面，一定要有，否則內容出不來

$pdf->writeHTML($html_content);
//PDF內容設定
$pdf->Output("{$title}.pdf", 'D');
