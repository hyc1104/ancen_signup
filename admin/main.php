<?php
use Xmf\Request;
use XoopsModules\Ancen_signup\Ancen_signup_actions;
/*-----------引入檔案區--------------*/
$GLOBALS['xoopsOption']['template_main'] = 'ancen_signup_admin.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';
$_SESSION['ancen_signup_adm'] = true;

/*-----------變數過濾----------*/
$op = Request::getString('op');
$id = Request::getInt('id');

/*-----------執行動作判斷區----------*/
switch ($op) {

    //新增活動表單
    case 'ancen_signup_actions_create':
        Ancen_signup_actions::create();
        break;

    //新增活動資料
    case 'ancen_signup_actions_store':
        $id = Ancen_signup_actions::store();
        header("location: {$_SERVER['PHP_SELF']}?id=$id");
        exit;

    //修改用表單
    case 'ancen_signup_actions_edit':
        Ancen_signup_actions::create($id);
        $op = 'ancen_signup_actions_create';
        break;

    //更新資料
    case 'ancen_signup_actions_update':
        Ancen_signup_actions::update($id);
        header("location: {$_SERVER['PHP_SELF']}?id=$id");
        exit;

    //刪除資料
    case 'ancen_signup_actions_destroy':
        Ancen_signup_actions::destroy($id);
        //header("location: {$_SERVER['PHP_SELF']}");
        redirect_header($_SERVER['PHP_SELF'], 3, "成功刪除活動!");
        exit;

    default:
        if (empty($id)) {
            Ancen_signup_actions::index();
            $op = 'ancen_signup_actions_index';
        } else {
            Ancen_signup_actions::show($id);
            $op = 'ancen_signup_actions_show';
        }
        break;
}

/*-----------功能函數區----------*/

/*-----------秀出結果區--------------*/
$xoopsTpl->assign('now_op', $op);
$xoTheme->addStylesheet('/modules/tadtools/css/font-awesome/css/font-awesome.css');
$xoTheme->addStylesheet(XOOPS_URL . '/modules/tadtools/css/xoops_adm4.css');
$xoTheme->addStylesheet(XOOPS_URL . '/modules/ancen_signup/css/module.css');
require_once __DIR__ . '/footer.php';
