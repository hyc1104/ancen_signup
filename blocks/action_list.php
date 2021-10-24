<?php
use XoopsModules\Ancen_signup\Ancen_signup_actions;

//可報名活動一覽
function action_list()
{
    $block = Ancen_signup_actions::get_all(true);
    return $block;
}

//可報名活動一覽的編輯函數
function action_list_edit()
{

}
