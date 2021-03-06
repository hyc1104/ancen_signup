<?php
// 如「模組目錄」= signup，則「首字大寫模組目錄」= Signup
// 如「資料表名」= actions，則「模組物件」= Actions
use Xmf\Request;
use XoopsModules\Ancen_signup\Ancen_signup_actions;
use XoopsModules\Ancen_signup\Ancen_signup_data;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'ancen_signup_index.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

/*-----------變數過濾----------*/
$op = Request::getString('op');
$id = Request::getInt('id');
$action_id = Request::getInt('action_id');
$accept = Request::getInt('accept');
$files_sn = Request::getInt('files_sn');
$pdf_setup_col = Request::getString('pdf_setup_col');
$file = Request::getWord('file', 'pdf');
/*-----------執行動作判斷區----------*/
switch ($op) {
    case "tufdl":
        $TadUpFiles = new TadUpFiles('ancen_signup');
        $TadUpFiles->add_file_counter($files_sn);
        exit;

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
        redirect_header($_SERVER['PHP_SELF'], 3, _MD_ANCEN_SIGNUP_DELETE_SUCCESS);
        exit;

    //新增報名表單
    case 'ancen_signup_data_create':
        Ancen_signup_data::create($action_id);
        break;

    //新增報名儲存表單
    case 'ancen_signup_data_store':
        $id = Ancen_signup_data::store();
        Ancen_signup_data::mail($id, 'store');
        redirect_header("{$_SERVER['PHP_SELF']}?op=ancen_signup_data_show&id=$id", 3, _MD_ANCEN_SIGNUP_APPLY_SUCCESS);
        break;

    //顯示報名表
    case 'ancen_signup_data_show':
        Ancen_signup_data::show($id);
        break;

    //修改報名表單
    case 'ancen_signup_data_edit':
        Ancen_signup_data::create($action_id, $id);
        $op = 'ancen_signup_data_create';
        break;

    //更新報名資料
    case 'ancen_signup_data_update':
        Ancen_signup_data::update($id);
        Ancen_signup_data::mail($id, 'update');
        redirect_header("{$_SERVER['PHP_SELF']}?op=ancen_signup_data_show&id=$id", 3, _MD_ANCEN_SIGNUP_APPLY_UPDATE_SUCCESS);
        exit;

    //刪除報名資料
    case 'ancen_signup_data_destroy':
        $uid = $_SESSION['can_add'] ? null : $xoopsUser->uid();
        $signup = Ancen_signup_data::get($id, $uid);
        Ancen_signup_data::destroy($id);
        Ancen_signup_data::mail($id, 'destroy', $signup);
        redirect_header("{$_SERVER['PHP_SELF']}?id=$action_id", 3, _MD_ANCEN_SIGNUP_APPLY_DELETE_SUCCESS);
        exit;

    //更改錄取狀態
    case 'ancen_signup_data_accept':
        Ancen_signup_data::accept($id, $accept);
        Ancen_signup_data::mail($id, 'accept');
        redirect_header("{$_SERVER['PHP_SELF']}?id=$action_id", 3, _MD_ANCEN_SIGNUP_ACCEPT_SUCCESS);
        exit;

    //複製活動
    case 'ancen_signup_actions_copy':
        $new_id = Ancen_signup_actions::copy($id);
        header("location: {$_SERVER['PHP_SELF']}?op=ancen_signup_actions_edit&id=$new_id");
        exit;

    //匯入CSV
    case 'ancen_signup_data_preview_csv':
        Ancen_signup_data::preview_csv($id);
        break;

    //批次匯入CSV資料
    case 'ancen_signup_data_import_csv':
        Ancen_signup_data::import_csv($id);
        redirect_header("{$_SERVER['PHP_SELF']}?id=$id", 3, _MD_ANCEN_SIGNUP_IMPORT_SUCCESS);
        break;

    //匯入Excel
    case 'ancen_signup_data_preview_excel':
        Ancen_signup_data::preview_excel($id);
        break;

    //批次匯入Excel資料
    case 'ancen_signup_data_import_excel':
        Ancen_signup_data::import_excel($id);
        redirect_header("{$_SERVER['PHP_SELF']}?id=$id", 3, _MD_ANCEN_SIGNUP_IMPORT_SUCCESS);
        break;

    //進行PDF匯出設定
    case 'ancen_signup_data_pdf_setup':
        Ancen_signup_data::pdf_setup($id);
        break;

    //儲存PDF匯出設定
    case 'ancen_signup_data_pdf_setup_save':
        Ancen_signup_data::pdf_setup_save($action_id, $pdf_setup_col);
        header("location: {$file}_signup.php?id=$action_id");
        break;

    default:
        if (empty($id)) {
            Ancen_signup_actions::index($xoopsModuleConfig['only_enable']);
            $op = 'ancen_signup_actions_index';
        } else {
            Ancen_signup_actions::show($id);
            $op = 'ancen_signup_actions_show';
        }
        break;
}

/*-----------function區--------------*/

/*-----------秀出結果區--------------*/
unset($_SESSION['api_mode']);
$xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu));
$xoopsTpl->assign('now_op', $op);
$xoTheme->addStylesheet(XOOPS_URL . '/modules/ancen_signup/css/module.css');
require_once XOOPS_ROOT_PATH . '/footer.php';
