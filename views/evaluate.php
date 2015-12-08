<?
    $this->layout = 'chat';
    $this->js     = 'pages/evaluate.js';
    $this->widget->enable('ui');
    $this->widget->enable('toast');
?>

<div class="evaluate">

    <div class="info-bar">
        Evaluate our support
    </div>

    <div class="info-message">
        <img src="<?=URL::baseUrl();?>/public/images/support.png" alt="" />
        <p>
            In order to improve our services.<br />Please evaluate this support
        </p>
    </div>

    <div class="rate-stars unselectable" unselectable="on">
        <ul>
            <li tip="Very Poor">&nbsp;</li>
            <li tip="Poor">&nbsp;</li>
            <li tip="Average">&nbsp;</li>
            <li tip="Good">&nbsp;</li>
            <li tip="Excelent">&nbsp;</li>
        </ul>
    </div>

    <div class="button-group">
        <button id="evaluate" class="form-button" disabled="disabled"> Evaluate </button>
    </div>

</div>