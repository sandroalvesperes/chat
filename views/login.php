<?
    $this->layout = 'chat';
    $this->js     = 'pages/login.js';
    $this->widget->enable('checkbox');
?>

<div class="login">

    <div class="message-error">&nbsp;</div>

    <p>
        <span>E-mail:</span>
        <input value="<?=$this->email;?>" type="text" id="email" autofocus="" maxlength="120" class="form-input" />
    </p>

    <p>
        <span>Password:</span>
        <input type="password" id="password" maxlength="50" class="form-input" />
    </p>

    <p>
        <input<?=($this->email ? ' checked="checked"' : '');?> type="checkbox" id="remember_email" value="1" />
        <label for="remember_email" unselectable="on" class="unselectable">Remember my e-mail</label>
    </p>

    <p>
        <button name="sign_in" id="sign_in" class="form-button"> Sign In </button>
    </p>

</div>