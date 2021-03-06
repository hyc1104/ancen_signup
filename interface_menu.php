<?php
use XoopsModules\Tadtools\Utility;
//判斷是否對該模組有管理權限
$is_admin = basename(__DIR__) . '_adm';
if (!isset($_SESSION[$is_admin])) {
    $_SESSION[$is_admin] = ($xoopsUser) ? $xoopsUser->isAdmin() : false;
}

//判斷有無開設活動的權限
if (!isset($_SESSION['can_add'])) {
    $_SESSION['can_add'] = Utility::power_chk('ancen_signup', '1');
}

//回模組首頁
$interface_menu[_TAD_TO_MOD] = "index.php";
$interface_icon[_TAD_TO_MOD] = "fa-chevron-right";

if ($xoopsUser) {
    $interface_menu[_MD_ANCEN_SIGNUP_MY_RECORD] = "my_signup.php";
    $interface_icon[_MD_ANCEN_SIGNUP_MY_RECORD] = "fa-chevron-right";
}

//模組後台
if ($_SESSION[$is_admin]) {
    $interface_menu[_TAD_TO_ADMIN] = "admin/main.php";
    $interface_icon[_TAD_TO_ADMIN] = "fa-chevron-right";
}
