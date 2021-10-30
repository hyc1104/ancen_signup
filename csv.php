<?php
use Xmf\Request;
use XoopsModules\Ancen_signup\Ancen_signup_actions;
use XoopsModules\Tadtools\TadDataCenter;

require_once __DIR__ . '/header.php';

if (!$_SESSION['can_add']) {
    redirect_header($_SERVER['PHP_SELF'], 3, "您沒有權限使用此功能");
}

$id = Request::getInt('id');
$action = Ancen_signup_actions::get($id);

if ($action['uid'] != $xoopsUser->uid()) {
    redirect_header($_SERVER['PHP_SELF'], 3, "您沒有權限使用此功能");
}

$csv = [];

$TadDataCenter = new TadDataCenter('ancen_signup');
$head = $TadDataCenter->getAllColItems($action['setup']);

$head[] = '錄取';
$head[] = '報名日期';
$head[] = '身份';

$csv[] = implode(',', $head);
$content = implode("\n", $csv);
$content = mb_convert_encoding($content, 'Big5');

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename={$action['title']}報名名單.csv");

echo $content;

exit;
