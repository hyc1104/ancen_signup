<?php
use Xmf\Request;
use XoopsModules\Ancen_signup\Ancen_signup_api;

require_once dirname(dirname(__DIR__)) . '/mainfile.php';

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$token = Request::getString('token');

$api = new Ancen_signup_api($token);

switch ($op) {

    default:
        echo $api->user();
        break;
}
