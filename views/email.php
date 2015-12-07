<style type="text/css">
    .email-header {
        background-color: #E2E2E2;
        padding: 5px;
        margin-bottom: 20px;
    }
    .email-form * {
        font-family: verdana, tahoma, arial;
        font-size: 11px;
        color: #333;
    }

    .email-form b {
        color: #1d5687;
    }

    .email-title {
        padding: 10px;
        font-size: 22px;
        font-weight: bold;
        font-family: sans-serif, arial;
        color: #2F63A0;
    }

    .email-message {
        padding-top: 10px;
        font-size: 12px;
        line-height: 24px;
    }
</style>

<div class="email-header">
    <table border="0" cellpadding="6" cellspacing="0">
        <tbody>
            <tr>
                <td align="center" valign="middle" style="padding: 10px">
                    <img src="<?=URL::baseUrl();?>/public/images/logo.png" alt="" />
                </td>
                <td align="left" valign="middle" class="email-title">Client Message</td>
            </tr>
        </tbody>
    </table>
</div>

<table border="0" cellpadding="5" cellspacing="0" class="email-form">
    <tbody>
        <tr>
            <td width="62"><b>Name:</b></td>
            <td><?=htmlspecialchars($this->name);?></td>
        </tr>
        <tr>
            <td><b>E-mail:</b></td>
            <td><?=htmlspecialchars($this->email);?></td>
        </tr>
        <tr>
            <td><b>Sex:</b></td>
            <td><?=htmlspecialchars($this->sex);?></td>
        </tr>
        <tr>
            <td><b>Subject:</b></td>
            <td><?=htmlspecialchars($this->subject);?></td>
        </tr>
        <tr>
            <td colspan="2" style="padding-top: 20px"><b>Message:</b></td>
        </tr>
        <tr>
            <td colspan="2" class="email-message">

                <?=nl2br(htmlspecialchars($this->message));?>

            </td>
        </tr>
    </tbody>
</table>