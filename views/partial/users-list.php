<? if( @count($this->users) > 0 ){ ?>

    <? foreach( $this->users as $user ){ ?>

        <tr>
            <td class="<?=$user['sex'];?>-icon td-first">&nbsp;</td>
            <td><?=htmlspecialchars($user['name']);?></td>
            <td><?=htmlspecialchars($user['email']);?></td>
            <td><?=$user['last_activity'];?></td>
            <td<?=($user['online'] ? ' class="check"' : '');?> align="center">
                <?=($user['online'] ? '&nbsp;' : '-');?>
            </td>
            <td<?=($user['active'] ? ' class="check"' : '');?> align="center">
                <?=($user['active'] ? '&nbsp;' : '-');?>
            </td>
            <td class="td-last">
                <button onclick="editUser(<?=$user['id_support_user'];?>, '<?=addslashes(htmlspecialchars($user['name']));?>');" class="form-button action-edit" tip="Edit">&nbsp;</button>
            </td>
        </tr>

    <? } ?>

<? } else { ?>

    <tr>
        <td colspan="7">
            <div class="empty-result">No users found</div>
        </td>
    </tr>

<? } ?>