<h2 class="my">

    <{if $action.enable}>
    <i class="fa fa-check text-success" aria-hidden="true"></i>
    <{else}>
    <i class="fa fa-times text-danger" aria-hidden="true"></i>
    <{/if}>
    <{$action.title}>
    <small><i class="fa fa-calculator" aria-hidden="true"></i> <{$smarty.const._MD_ANCEN_SIGNUP_ACTION_DATE}> <{$smarty.const._TAD_FOR}><{$action.action_date}></small>
</h2>

<div class="alert alert-info">
    <{$action.detail}>
</div>

<h3 class="my">
    <{$smarty.const._MD_ANCEN_SIGNUP_APPLY_FORM}>
    <small>
        <i class="fa fa-calendar-check-o" aria-hidden="true"></i> <{$smarty.const._MD_ANCEN_SIGNUP_END_DATE_FULL}> <{$smarty.const._TAD_FOR}><{$action.end_date}>
        <i class="fa fa-users" aria-hidden="true"></i> <{$smarty.const._MD_ANCEN_SIGNUP_APPLY_MAX}> <{$smarty.const._TAD_FOR}><{$action.number}>
        <{if $action.candidate}><span data-toggle="tooltip" title="<{$smarty.const._MD_ANCEN_SIGNUP_CANDIDATE_QUOTA}>">(<{$action.candidate}>)</span><{/if}>

    </small>
</h3>

<table class="table">
    <{foreach from=$tdc key=title item=signup name=tdc}>
        <tr>
            <th><{$title}></th>
            <td>
                <{foreach from=$signup key=i item=val name=signup}>
                    <div><{$val}></div>
                <{/foreach}>
            </td>
        </tr>
    <{/foreach}>
</table>

<{if $can_add || $uid == $now_uid}>
<div class="bar">
    <a href="javascript:del_data('<{$id}>')" class="btn btn-danger"><i class="fa fa-times" aria-hidden="true"></i> <{$smarty.const._MD_ANCEN_SIGNUP_CANCELLATION}></a>
    <a href="<{$xoops_url}>/modules/ancen_signup/index.php?op=ancen_signup_data_edit&id=<{$id}>&action_id=<{$action_id}>" class="btn btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i> <{$smarty.const._MD_ANCEN_SIGNUP_MODIFY}></a>
</div>
<{/if}>