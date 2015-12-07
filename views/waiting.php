<?
    $this->layout = 'chat';
    $this->js     = 'pages/waiting.js';
    $this->widget->enable('ui');
    $this->widget->enable('toast');
?>

<div class="waiting">

    <div class="floating-button-group">
        <button class="form-button cancel-button">Cancel</button>
    </div>

    <div class="info-bar">
        Wait a few minutes
    </div>

    <div class="info-message">
        <p>Please, wait for one of our support operators</p>
    </div>

    <div class="count-down">

        <div class="crossline"></div>

        <div class="middle-box">
            <div class="circle">&nbsp;</div>
            <span>clients waiting</span>
        </div>

    </div>

    <div class="waiting-operator"></div>

</div>