<?php
use Xmf\Request;
use XoopsModules\Ancen_signup\Ancen_signup_data;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'ancen_signup_index.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

/*-----------變數過濾----------*/
$op = Request::getString('op');
$uid = Request::getInt('uid');

/*-----------執行動作判斷區----------*/
switch ($op) {

    default:
        Ancen_signup_data::my($uid);
        $op = 'ancen_signup_data_my';
        break;
}

/*-----------function區--------------*/

/*-----------秀出結果區--------------*/
unset($_SESSION['api_mode']);
$xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu));
$xoopsTpl->assign('now_op', $op);
$xoTheme->addStylesheet(XOOPS_URL . '/modules/ancen_signup/css/module.css');
require_once XOOPS_ROOT_PATH . '/footer.php';
