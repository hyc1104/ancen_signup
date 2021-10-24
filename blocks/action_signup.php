<?php
use XoopsModules\Ancen_signup\Ancen_signup_actions;
use XoopsModules\Tadtools\Utility;
//活動報名焦點
function action_signup($options)
{
    $block = Ancen_signup_actions::get_all($options[0], true);
    return $block;
}

//活動報名焦點的編輯函數
function action_signup_edit($options)
{
    $actions = Ancen_signup_actions::get_all(true);
    $opt = '';
    foreach ($actions as $action) {
        $selected = Utility::chk($options[0], $action['id'], '', "selected");
        $opt .= "<option value='{$action['id']}' $selected>{$action['action_date']} {$action['title']}</option>";
    }
    $form = "
    <ol class='my-form'>
        <li class='my-row'>
            <lable class='my-label'>請選擇一個活動</lable>
            <div class='my-content'>
                <select name='options[0]' class='my-input'>
                    $opt
                </select>
            </div>
        </li>
    </ol>
    ";
    return $form;
}
