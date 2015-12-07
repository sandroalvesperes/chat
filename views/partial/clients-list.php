<? if( @count($this->clients) > 0 ){ ?>

    <? foreach( $this->clients as $client ){ ?>

        <tr>
            <td class="<?=$client['client_user_sex'];?>-icon td-first">&nbsp;</td>
            <td><?=htmlspecialchars($client['client_user_name']);?></td>
            <td><?=htmlspecialchars($client['client_user_email']);?></td>
            <td><?=htmlspecialchars($client['subject']);?></td>
            <td class="<?=($client['id_support_user'] ? 'chatting' : 'waiting-time');?><?=($client['waiting_too_much'] && !$client['id_support_user'] ? ' too-much' : '');?>">
                <?=($client['id_support_user'] ? 'Chatting' : $client['waiting_time']);?>
            </td>
            <td class="td-last">

                <button onclick="openChat(<?=$client['id_chat'];?>, this);" class="form-button action-open" tip="Open chat">&nbsp;</button>

                <? if( !$client['id_support_user'] ){ ?>

                    <button onclick="disconnectClient(<?=$client['id_chat'];?>, this);" class="form-button action-disconnect" tip="Disconnect client">&nbsp;</button>

                <? } ?>

            </td>
        </tr>

    <? } ?>

<? } else { ?>

    <tr>
        <td colspan="6">
            <div class="empty-result">No clients on the list</div>
        </td>
    </tr>

<? } ?>

<tr style="display: none">
    <td colspan="6">
        <input type="hidden" id="hdn_support_status_check" value="<?=(int)$this->supportStatus;?>" />
        <input type="hidden" id="hdn_user_occupied" value="<?=(int)$this->userOccupied;?>" />
        <input type="hidden" id="hdn_clients_waiting" value="<?=count($this->clients);?>" />
    </td>
</tr>