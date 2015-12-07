<?
    $this->layout = 'chat';
    $this->js     = 'pages/support.js';
    $this->widget->enable('ui');
    $this->widget->enable('toast');
    $this->widget->enable('tipsy');
    $this->widget->enable('switch');
?>

<div class="support">

    <div class="floating-button-group">

        <? if( $this->accessMaintainUser ){ ?>

            <button class="form-button users-button">
                <img src="<?=URL::baseUrl();?>/public/images/user-group.png" alt="" />
                Users
            </button>

        <? } ?>

        <button class="form-button quit-button">Quit</button>
    </div>

    <div class="info-bar">
        List of clients requesting support
    </div>

    <div class="status">

        <table>

            <? if( $this->accessSupportStatus ){ ?>

                <tr>
                    <td>Support Status:</td>
                    <td>
                        <input<?=($this->supportStatus ? ' checked="checked"' : '');?> type="checkbox" id="support_status" value="1" />
                    </td>
                </tr>

            <? } else { ?>

                <tr>
                    <td>Support Status:</td>
                    <td>
                        <span class="description"><?=($this->supportStatus ? 'Online' : 'Offline');?></span>
                        <input value="<?=($this->supportStatus ? '1' : '0');?>" type="hidden" id="hdn_support_status" />
                    </td>
                </tr>

            <? } ?>

            <tr>
                <td>User Status:</td>
                <td>
                    <input<?=($this->userStatus ? ' checked="checked"' : '');?> type="checkbox" id="user_status" value="1" />
                </td>
            </tr>
        </table>

    </div>

    <div class="grid">

        <table>
            <thead>
                <tr>
                    <th colspan="2">Name</th>
                    <th>E-mail</th>
                    <th>Subject</th>
                    <th>Waiting Time</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody id="clients_list"></tbody>
        </table>

    </div>

</div>