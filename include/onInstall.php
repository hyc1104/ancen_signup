<?php
use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

// 安裝前
function xoops_module_pre_install_ancen_signup(XoopsModule $module)
{
}

// 安裝後
function xoops_module_install_ancen_signup(XoopsModule $module)
{
    // 有上傳功能才需要
    Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/ancen_signup");
    // 若有用到CKEditor編輯器才需要
    Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/ancen_signup/file");
    Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/ancen_signup/image");
    Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/ancen_signup/image/.thumbs");
    $groupid = mk_group("活動報名管理");

    return true;
}
