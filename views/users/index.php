<?
    $this->layout = 'chat';
    $this->js     = 'pages/users.js';
    $this->widget->enable('ui');
    $this->widget->enable('toast');
    $this->widget->enable('radio');
    $this->widget->enable('checkbox');
    $this->widget->enable('switch');
    $this->widget->enable('tipsy');
?>

<div class="users">

    <div class="floating-button-group">
        <button class="form-button new-user-button">
            <img src="<?=URL::baseUrl();?>/public/images/male.png" alt="" />
            New User
        </button>

        <button class="form-button back-button">Back</button>
    </div>

    <div class="info-bar">
        Module to maintain support users
    </div>

    <div class="filter">

        <table>
            <tr>
                <td>Name:</td>
                <td>
                    <input type="text" id="filter_name" maxlength="50" class="form-input" size="50" />
                </td>
            </tr>
            <tr>
                <td>E-mail:</td>
                <td>
                    <input type="text" id="filter_email" maxlength="120" class="form-input" size="50" />
                </td>
            </tr>
            <tr>
                <td>Active:</td>
                <td>
                    <input type="checkbox" id="filter_active_yes" value="1" />
                    <label for="filter_active_yes">Yes</label> &nbsp;

                    <input type="checkbox" id="filter_active_no" value="1" />
                    <label for="filter_active_no">No</label>
                </td>
            </tr>
            <tr>
                <td>Online:</td>
                <td>
                    <input type="checkbox" id="filter_online_yes" value="1" />
                    <label for="filter_online_yes">Yes</label> &nbsp;

                    <input type="checkbox" id="filter_online_no" value="1" />
                    <label for="filter_online_no">No</label>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <button id="filter" class="form-button"> Filter </button>
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
                    <th>Last Activity</th>
                    <th style="text-align: center">Online</th>
                    <th style="text-align: center">Active</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody id="users_list">
                <tr>
                    <td colspan="7">
                        <div class="empty-result">Filter not applied yet</div>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>

</div>