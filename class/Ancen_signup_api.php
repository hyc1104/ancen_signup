<?php
namespace XoopsModules\Ancen_signup;

use XoopsModules\Ancen_signup\Ancen_signup_actions;
use XoopsModules\Ancen_signup\Ancen_signup_data;
use XoopsModules\Tadtools\SimpleRest;

require dirname(dirname(dirname(__DIR__))) . '/mainfile.php';

class Ancen_signup_api extends SimpleRest
{
    public $uid = '';
    public $user = [];
    public $groups = [];
    private $token = '';

    public function __construct($token = '')
    {
        $this->token = $token;
        if (!isset($_SESSION['api_mode'])) {
            $_SESSION['api_mode'] = true;
        }

        if ($this->token) {
            $User = $this->getXoopsSUser($this->token);
            $this->uid = (int) $User['uid'];
            $this->groups = $User['groups'];
            $this->user = $User['user'];

            //判斷是否對該模組有管理權限 $_SESSION['模組目錄_adm']
            if (!isset($this->user['ancen_signup_adm'])) {
                $this->user['ancen_signup_adm'] = $_SESSION['ancen_signup_adm'] = ($this->uid) ? $this->isAdmin('ancen_signup') : false;
            }

            // 判斷有無XXX的權限
            // if (!isset($this->user['權限名'])) {
            //     $_SESSION['權限名'] = $this->user['權限名'] = $this->powerChk('模組目錄', 權限編號);
            // }

        }
    }

    // 傳回目前使用者資訊
    public function user()
    {
        $data = ['uid' => (int) $this->uid, 'groups' => $this->groups, 'user' => $this->user];
        return $this->encodeJson($data);
    }

    // 轉成 json
    private function encodeJson($responseData)
    {
        if (empty($responseData)) {
            $statusCode = 404;
            $responseData = array('error' => '無資料');
        } else {
            $statusCode = 200;
        }
        $this->setHttpHeaders($statusCode);

        $jsonResponse = json_encode($responseData, 256);
        return $jsonResponse;
    }

    // 取得活動資料
    public function ancen_signup_actions_index($only_enable = true)
    {
        $actions = Ancen_signup_actions::get_all($only_enable);
        return $this->encodeJson($actions);
    }

    // 取得活動所有報名資料
    public function ancen_signup_data_index($action_id)
    {
        $data = $this->token ? Ancen_signup_data::get_all($action_id) : [];
        return $this->encodeJson($data);
    }

}
