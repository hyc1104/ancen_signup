<?php
// 如「模組目錄」= signup，則「首字大寫模組目錄」= Signup
// 如「ancen_signup_data」= actions，則「模組物件」= Actions

namespace XoopsModules\Ancen_signup;

use XoopsModules\Ancen_signup\Ancen_signup_actions;
use XoopsModules\Tadtools\BootstrapTable;
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\TadDataCenter;
use XoopsModules\Tadtools\Tmt;
use XoopsModules\Tadtools\Utility;

class Ancen_signup_data
{
    //列出所有資料
    public static function index($action_id)
    {
        global $xoopsTpl;

        $all_data = self::get_all($action_id);
        $xoopsTpl->assign('all_data', $all_data);
    }

    //編輯表單
    public static function create($action_id, $id = '')
    {
        global $xoopsTpl, $xoopsUser;
        $uid = $_SESSION['can_add'] ? null : $xoopsUser->uid();
        //抓取預設值
        $db_values = empty($id) ? [] : self::get($id, $uid);

        foreach ($db_values as $col_name => $col_val) {
            $$col_name = $col_val;
            $xoopsTpl->assign($col_name, $col_val);
        }

        $op = empty($id) ? "ancen_signup_data_store" : "ancen_signup_data_update";
        $xoopsTpl->assign('next_op', $op);

        //套用formValidator驗證機制
        $formValidator = new FormValidator("#myForm", true);
        $formValidator->render();

        //加入Token安全機制
        include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
        $token = new \XoopsFormHiddenToken();
        $token_form = $token->render();
        $xoopsTpl->assign("token_form", $token_form);

        $action = Ancen_signup_actions::get($action_id, true);
        $action['signup'] = Ancen_signup_data::get_all($action_id);
        if (time() > strtotime($action['end_date'])) {
            redirect_header($_SERVER['PHP_SELF'], 3, "已報名截止，無法再進行報名或修改報名!");
        } elseif (!$action['enable']) {
            redirect_header($_SERVER['PHP_SELF'], 3, "該報名已關閉，無法再進行報名或修改報名!");
        } elseif ($action['signup_count'] >= ($action['number'] + $action['candidate'])) {
            redirect_header($_SERVER['PHP_SELF'], 3, "人數已滿，無法再進行報名!");
        }

        $xoopsTpl->assign("action", $action);
        $uid = $xoopsUser ? $xoopsUser->uid() : 0;
        $xoopsTpl->assign("uid", $uid);
        //Utility::dd($actions);

        $TadDataCenter = new TadDataCenter('ancen_signup');
        $TadDataCenter->set_col('id', $id);
        $signup_form = $TadDataCenter->strToForm($action['setup']);
        $xoopsTpl->assign("signup_form", $signup_form);
    }

    //新增資料
    public static function store()
    {
        global $xoopsDB;

        //XOOPS表單安全檢查
        Utility::xoops_security_check();

        $myts = \MyTextSanitizer::getInstance();

        foreach ($_POST as $var_name => $var_val) {
            $$var_name = $myts->addSlashes($var_val);
        }

        $action_id = (int) $action_id;
        $uid = (int) $uid;

        $sql = "insert into `" . $xoopsDB->prefix("ancen_signup_data") . "` (
        `action_id`,
        `uid`,
        `signup_date`
        ) values(
        '{$action_id}',
        '{$uid}',
        now()
        )";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        //取得最後新增資料的流水編號
        $id = $xoopsDB->getInsertId();

        $TadDataCenter = new TadDataCenter('ancen_signup');
        $TadDataCenter->set_col('id', $id);
        $TadDataCenter->saveData();

        $action = Ancen_signup_actions::get($action_id);
        $action['signup'] = self::get_all($action_id);

        if (count($action['signup']) > $action['number']) {
            $TadDataCenter->set_col('data_id', $id);
            $TadDataCenter->saveCustomData(['tag' => ['候補']]);
        }
        return $id;
    }

    //以流水號秀出某筆資料內容
    public static function show($id = '')
    {
        global $xoopsTpl, $xoopsUser;

        if (empty($id)) {
            return;
        }
        $uid = $_SESSION['can_add'] ? null : $xoopsUser->uid();

        $id = (int) $id;
        $data = self::get($id, $uid);

        if (empty($data)) {
            redirect_header($_SERVER['PHP_SELF'], 3, "查無報名資料，無法觀看");
        }

        $myts = \MyTextSanitizer::getInstance();
        foreach ($data as $col_name => $col_val) {
            $col_val = $myts->htmlSpecialChars($col_val);
            $xoopsTpl->assign($col_name, $col_val);
            $$col_name = $col_val;
        }
        $TadDataCenter = new TadDataCenter('ancen_signup');
        $TadDataCenter->set_col('id', $id);
        $tdc = $TadDataCenter->getData();
        $xoopsTpl->assign('tdc', $tdc);

        $action = Ancen_signup_actions::get($action_id, true);
        $xoopsTpl->assign("action", $action);

        $now_uid = $xoopsUser ? $xoopsUser->uid() : 0;
        $xoopsTpl->assign("now_uid", $now_uid);
        $SweetAlert = new SweetAlert();
        $SweetAlert->render("del_data", "index.php?op=ancen_signup_data_destroy&action_id={$action_id}&id=", 'id');
    }

    //更新某一筆資料
    public static function update($id = '')
    {
        global $xoopsDB, $xoopsUser;

        //XOOPS表單安全檢查
        Utility::xoops_security_check();

        $myts = \MyTextSanitizer::getInstance();

        foreach ($_POST as $var_name => $var_val) {
            $$var_name = $myts->addSlashes($var_val);
        }

        $action_id = (int) $action_id;
        $uid = (int) $uid;

        $now_uid = $xoopsUser ? $xoopsUser->uid() : 0;

        $sql = "update `" . $xoopsDB->prefix("ancen_signup_data") . "` set
        `signup_date` = now()
        where `id` = '$id' and `uid` = '$now_uid'";
        if ($xoopsDB->queryF($sql)) {
            $TadDataCenter = new TadDataCenter('ancen_signup');
            $TadDataCenter->set_col('id', $id);
            $TadDataCenter->saveData();
        } else {
            Utility::web_error($sql, __FILE__, __LINE__);
        }

        return $id;
    }

    //刪除某筆資料資料
    public static function destroy($id = '')
    {
        global $xoopsDB, $xoopsUser;

        if (empty($id)) {
            return;
        }
        $now_uid = $xoopsUser ? $xoopsUser->uid() : 0;
        $sql = "delete from `" . $xoopsDB->prefix("ancen_signup_data") . "`
        where `id` = '{$id}' and `uid` = '{$now_uid}'";
        if ($xoopsDB->queryF($sql)) {
            $TadDataCenter = new TadDataCenter('ancen_signup');
            $TadDataCenter->set_col(id, $id);
            $TadDataCenter->delData();

        } else {

            Utility::web_error($sql, __FILE__, __LINE__);
        }
    }

    //以流水號取得某筆資料
    public static function get($id = '', $uid = '')
    {
        global $xoopsDB;

        if (empty($id)) {
            return;
        }

        $and_uid = $uid ? "and `uid`='$uid'" : '';

        $sql = "select * from `" . $xoopsDB->prefix("ancen_signup_data") . "`
        where `id` = '{$id}' $and_uid";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data = $xoopsDB->fetchArray($result);
        return $data;
    }

    //取得所有資料陣列
    public static function get_all($action_id = '', $uid = '', $auto_key = false, $only_accept = false)
    {
        global $xoopsDB, $xoopsUser;
        $myts = \MyTextSanitizer::getInstance();

        $and_accept = $only_accept ? "and `accept`='1'" : '';

        if ($action_id) {
            $sql = "select * from `" . $xoopsDB->prefix("ancen_signup_data") . "` where `action_id` = '$action_id' $and_accept order by `signup_date`";
        } else {
            if (!$_SESSION['can_add'] or !$uid) {
                $uid = $xoopsUser ? $xoopsUser->uid() : 0;
            }
            $sql = "select * from `" . $xoopsDB->prefix("ancen_signup_data") . "` where `uid` = '$uid' $and_accept order by `signup_date`";
        }

        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data_arr = [];
        $TadDataCenter = new TadDataCenter('ancen_signup');
        while ($data = $xoopsDB->fetchArray($result)) {

            $TadDataCenter->set_col('id', $data['id']);
            $data['tdc'] = $TadDataCenter->getData();
            $data['action'] = Ancen_signup_actions::get($data['action_id'], true);
            $TadDataCenter->set_col('data_id', $data['id']);
            $data['tag'] = $TadDataCenter->getData('tag', 0);

            if ($_SESSION['api_mode'] or $auto_key) {
                $data_arr[] = $data;
            } else {
                $data_arr[$data['id']] = $data;
            }
        }
        return $data_arr;
    }

    //查詢某人報名紀錄
    public static function my($uid)
    {
        global $xoopsTpl, $xoopsUser;
        $my_signup = self::get_all(null, $uid);
        //Utility::dd($my_signup);
        $xoopsTpl->assign('my_signup', $my_signup);
        BootstrapTable::render();
    }

    //更改錄取狀態
    public static function accept($id, $accept)
    {

        global $xoopsDB;
        if (!$_SESSION['can_add']) {
            redirect_header($_SERVER['PHP_SELF'], 3, "您沒有權限使用此功能");
        }
        $id = (int) $id;
        $accept = (int) $accept;
        $sql = "update `" . $xoopsDB->prefix("ancen_signup_data") . "` set
        `accept` = '$accept'
        where `id` = '$id'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    }

    //立即寄出
    public static function send($title = "無標題", $content = "無內容", $email = "")
    {
        global $xoopsUser;
        if (empty($email)) {
            $email = $xoopsUser->email();
        }
        $xoopsMailer = xoops_getMailer();
        $xoopsMailer->multimailer->ContentType = "text/html";
        $xoopsMailer->addHeaders("MIME-Version: 1.0");
        $header = '';
        return $xoopsMailer->sendMail($email, $title, $content, $header);
    }

    //產生通知信
    public static function mail($id, $type, $signup = [])
    {
        global $xoopsUser;
        $id = (int) $id;

        if (empty($id)) {
            redirect_header($_SERVER['PHP_SELF'], 3, "無編號，無法寄送通知信");
        }

        $signup = $signup ? $signup : self::get($id);
        $now = date("Y-m-d H:i:s");
        $name = $xoopsUser->name();
        $name = $name ? $name : $xoopsUser->uname();

        $action = Ancen_signup_actions::get($signup['action_id']);

        $member_handler = xoops_getHandler('member');
        $admUser = $member_handler->getUser($action['uid']);
        $adm_email = $admUser->email();

        if ($type == 'destroy') {
            $title = "【{$action['title']}】取消報名通知";
            $head = "<p>您於【{$signup['signup_date']}】報名了【{$action['title']}】活動已於{$now} 由 {$name}取消報名。</p>";
            $foot = "欲重新報名，請連至 " . XOOPS_URL . "/modules/ancen_signup/index.php?op=ancen_signup_data_create&action_id={$action['id']}";
        } elseif ($type == 'store') {
            $title = "【{$action['title']}】報名完成通知";
            $head = "<p>您於【{$signup['signup_date']}】報名【{$action['title']}】活動已於{$now} 由 {$name}報名完成。</p>";
            $foot = "完整詳請，請連至 " . XOOPS_URL . "/modules/ancen_signup/index.php?id={$signup['action_id']}";
        } elseif ($type == 'update') {
            $title = "【{$action['title']}】修改報名資料通知";
            $head = "<p>您於【{$signup['signup_date']}】報名【{$action['title']}】活動已於{$now} 由 {$name}修改報名資料如下：</p>";
            $foot = "完整詳請，請連至 " . XOOPS_URL . "/modules/ancen_signup/index.php?id={$signup['action_id']}";
        } elseif ($type == 'accept') {
            $title = "【{$action['title']}】報名錄取狀態通知";
            if ($signup['accept'] == 1) {
                $head = "<p>您於【{$signup['signup_date']}】報名【{$action['title']}】活動經審核為【<h2 style='color:blue'>錄取</h2>】，您的報名資料如下：</p>";
            } else {
                $head = "<p>您於【{$signup['signup_date']}】報名【{$action['title']}】活動經審核為【<span style='color:red;'>未錄取</span>】，您的報名資料如下：</p>";
            }
            $foot = "完整詳請，請連至 " . XOOPS_URL . "/modules/ancen_signup/index.php?id={$signup['action_id']}";

            $signupUser = $member_handler->getUser($signup['uid']);
            $email = $signupUser->email();
        }

        $content = self::mk_content($id, $head, $foot, $action);
        if (!self::send($title, $content, $email)) {
            redirect_header($_SERVER['PHP_SELF'], 3, "通知信寄送失敗!");
        }
        self::send($title, $content, $adm_email);
    }

    //產生通知信內容
    public static function mk_content($id, $head = '', $foot = '', $action = [])
    {

        if ($id) {
            $TadDataCenter = new TadDataCenter('ancen_signup');
            $TadDataCenter->set_col('id', $id);
            $tdc = $TadDataCenter->getData();

            $table = '<table class="table">';

            foreach ($tdc as $title => $signup) {

                $table .= "
                <tr>
                    <th>{$title}</th>
                    <td>";

                foreach ($signup as $i => $val) {
                    $table .= "<div>{$val}</div>";
                }

                $table .= "</td>
                </tr>";
            }

            $table .= '</table>';

        }

        $content = "
        <html>
            <head>
                <style>

                    .table{
                        border:1px solid #000;
                        border-collapse: collapse;
                        margin:10px 0px;
                    }
                    .table th, .table td{
                        border:1px solid #000;
                        padding:4px 10px;
                    }

                    .table th{
                        background: 00ff00;
                    }

                    .well{
                        border-radius: 10px;
                        background: #fcfcfc;
                        boredr: 2px solid #cfcfcf;
                        padding:14px 16px;
                        margin:10px 0px;
                    }
                </style>
            </head>
            <body>
            $head
            <h2>{$action['title']}</h2>
            <div>活動日期：{$action['action_date']}</div>
            <div class='well'>{$action['detail']}</div>
            $table
            $foot
            </body>
        </html>
        ";
        return $content;
    }

    //預覽CSV資料內容
    public static function preview_csv($action_id)
    {
        global $xoopsTpl;
        if (!$_SESSION['can_add']) {
            redirect_header($_SERVER['PHP_SELF'], 3, "您沒有權限使用此功能");
        }

        $action = Ancen_signup_actions::get($action_id);
        $xoopsTpl->assign('action', $action);

        //製作標題
        list($head, $type) = self::get_head($action, true, true);
        $xoopsTpl->assign('head', $head);
        $xoopsTpl->assign('type', $type);

        //抓取內容
        $preview_data = [];

        $handle = fopen($_FILES['csv']['tmp_name'], "r") or die("無法開啟");
        while (($val = fgetcsv($handle, 1000)) !== false) {
            $preview_data[] = mb_convert_encoding($val, 'UTF-8', 'Big5');
        }
        fclose($handle);
        $xoopsTpl->assign('preview_data', $preview_data);

        //加入Token安全機制
        include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
        $token = new \XoopsFormHiddenToken();
        $token_form = $token->render();
        $xoopsTpl->assign("token_form", $token_form);
    }

    //批次匯入CSV資料
    public static function import_csv($action_id)
    {
        global $xoopsDB, $xoopsUser;

        //XOOPS表單安全檢查
        Utility::xoops_security_check();

        if (!$_SESSION['can_add']) {
            redirect_header($_SERVER['PHP_SELF'], 3, "您沒有權限使用此功能");
        }

        $action_id = (int) $action_id;
        $uid = (int) $xoopsUser->uid();

        $action = Ancen_signup_actions::get($action_id);
        $TadDataCenter = new TadDataCenter('ancen_signup');
        foreach ($_POST['tdc'] as $tdc) {
            $sql = "insert into `" . $xoopsDB->prefix("ancen_signup_data") . "` (
            `action_id`,
            `uid`,
            `signup_date`,
            `accept`
            ) values(
            '{$action_id}',
            '{$uid}',
            now(),
            '1'
            )";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

            //取得最後新增資料的流水編號
            $id = $xoopsDB->getInsertId();
            $TadDataCenter->set_col('id', $id);
            $TadDataCenter->saveCustomData($tdc);

            $action['signup'] = self::get_all($action_id);

            if (count($action['signup']) > $action['number']) {
                $TadDataCenter->set_col('data_id', $id);
                $TadDataCenter->saveCustomData(['tag' => ['候補']]);
            }
        }
    }

    //預覽Excel資料內容
    public static function preview_excel($action_id)
    {
        global $xoopsTpl;
        if (!$_SESSION['can_add']) {
            redirect_header($_SERVER['PHP_SELF'], 3, "您沒有權限使用此功能");
        }

        $action = Ancen_signup_actions::get($action_id);
        $xoopsTpl->assign('action', $action);

        //製作標題
        list($head, $type) = self::get_head($action, true, true);

        $xoopsTpl->assign('head', $head);
        $xoopsTpl->assign('type', $type);

        //抓取內容
        $preview_data = [];

        require_once XOOPS_ROOT_PATH . '/modules/tadtools/vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
        $reader = \PHPExcel_IOFactory::createReader('Excel2007');
        $PHPExcel = $reader->load($_FILES['excel']['tmp_name']); // 檔案名稱
        $sheet = $PHPExcel->getSheet(0); // 讀取第一個工作表(編號從 0 開始)
        $maxCell = $PHPExcel->getActiveSheet()->getHighestRowAndColumn();
        $maxColumn = self::getIndex($maxCell['column']);

        // 一次讀一列
        for ($row = 1; $row <= $maxCell['row']; $row++) {
            // 讀出每一格
            for ($column = 0; $column <= $maxColumn; $column++) {
                $preview_data[$row][$column] = $sheet->getCellByColumnAndRow($column, $row)->getCalculatedValue();
            }
        }

        $xoopsTpl->assign('preview_data', $preview_data);

        //加入Token安全機制
        include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
        $token = new \XoopsFormHiddenToken();
        $token_form = $token->render();
        $xoopsTpl->assign("token_form", $token_form);
    }
    // 將文字轉為數字
    private static function getIndex($let)
    {
        // Iterate through each letter, starting at the back to increment the value
        for ($num = 0, $i = 0; $let != ''; $let = substr($let, 0, -1), $i++) {
            $num += (ord(substr($let, -1)) - 65) * pow(26, $i);
        }

        return $num;
    }
    //批次匯入Excel資料
    public static function import_excel($action_id)
    {
        self::import_csv($action_id);
    }

    //取得報名的標題欄
    public static function get_head($action, $return_type = false, $only_tdc = false)
    {
        $head_row = explode("\n", $action['setup']);
        $head = $type = [];
        foreach ($head_row as $head_data) {
            $cols = explode(',', $head_data);
            if (strpos($cols[0], '#') === false) {
                $head[] = str_replace('*', '', trim($cols[0]));
                $type[] = trim($cols[1]);
            }
        }

        if (!$only_tdc) {
            $head[] = '錄取';
            $head[] = '報名日期';
            $head[] = '身份';
        }
        if ($return_type) {
            return [$head, $type];

        } else {
            return $head;
        }
    }

    //進行PDF匯出設定
    public static function pdf_setup($action_id)
    {
        global $xoopsTpl;

        $action = Ancen_signup_actions::get($action_id);
        $xoopsTpl->assign('action', $action);

        $TadDataCenter = new TadDataCenter('ancen_signup');
        $TadDataCenter->set_col('pdf_setup_id', $action_id);
        $pdf_setup_col = $TadDataCenter->getData('pdf_setup_col', 0);
        $to_arr = explode(',', $pdf_setup_col);

        //製作標題
        $head_arr = self::get_head($action);
        $from_arr = array_diff($head_arr, $to_arr);

        $hidden_arr = [];

        $tmt_box = Tmt::render('pdf_setup_col', $from_arr, $to_arr, $hidden_arr, true, false);
        $xoopsTpl->assign('tmt_box', $tmt_box);
    }

    //儲存PDF匯出設定
    public static function pdf_setup_save($action_id, $pdf_setup_col = '')
    {
        $TadDataCenter = new TadDataCenter('ancen_signup');
        $TadDataCenter->set_col('pdf_setup_id', $action_id);
        $TadDataCenter->saveCustomData(['pdf_setup_col' => [$pdf_setup_col]]);
    }
}
