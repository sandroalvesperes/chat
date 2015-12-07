<?
    $this->layout = 'chat';
?>

<div class="error-page">

    <div class="info-bar">
        Sorry for the inconvenience
    </div>

    <div class="error-panel">

        <img src="<?=URL::baseUrl();?>/public/images/error-default.png" alt="" />

        <p><?=@($this->message ? $this->message : 'Error occurred');?></p>

    </div>

</div>