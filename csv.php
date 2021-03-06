<?php
use Xmf\Request;
use XoopsModules\Ancen_signup\Ancen_signup_actions;
use XoopsModules\Ancen_signup\Ancen_signup_data;
use XoopsModules\Tadtools\TadDataCenter;

require_once __DIR__ . '/header.php';

if (!$_SESSION['can_add']) {
    redirect_header($_SERVER['PHP_SELF'], 3, _TAD_PERMISSION_DENIED);
}

$id = Request::getInt('id');
$type = Request::getString('type');

$action = Ancen_signup_actions::get($id);

if ($action['uid'] != $xoopsUser->uid()) {
    redirect_header($_SERVER['PHP_SELF'], 3, _TAD_PERMISSION_DENIED);
}

$csv = [];
$head = Ancen_signup_data::get_head($action);
$csv[] = implode(',', $head);

if ($type == 'signup') {
    $signup = Ancen_signup_data::get_all($action['id']);

    foreach ($signup as $signup_data) {
        $item = [];
        foreach ($signup_data['tdc'] as $user_data) {
            $item[] = implode('|', $user_data);
        }

        if ($signup_data['accept'] === '1') {
            $item[] = _MD_ANCEN_SIGNUP_ACCEPT;
        } elseif ($signup_data['accept'] === '0') {
            $item[] = _MD_ANCEN_SIGNUP_NOT_ACCEPT;
        } else {
            $item[] = _MD_ANCEN_SIGNUP_ACCEPT_NOT_YET;
        }

        $item[] = $signup_data['signup_date'];
        $item[] = $signup_data['tag'];

        $csv[] = implode(',', $item);

    }

}

$content = implode("\n", $csv);
$content = mb_convert_encoding($content, 'Big5');

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename={$action['title']}" . _MD_ANCEN_SIGNUP_APPLY_LIST . ".csv");

echo $content;

exit;
