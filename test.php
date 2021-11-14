<?php
require_once __DIR__ . '/header.php';
require_once XOOPS_ROOT_PATH . '/header.php';

$url = "http://localhost/modules/ancen_signup/app_api.php?op=ancen_signup_actions_index&action_id=11";
$json = file_get_contents($url);
$arr = json_decode($json, true);
// var_dump($arr);
// var_export($arr);

$content = "<ol>";
foreach ($arr as $action) {
    $content .= "<li>{$action['action_date']} {$action['title']}</li>";
}
$content .= "</ol>";

echo $content;
require_once XOOPS_ROOT_PATH . '/footer.php';
