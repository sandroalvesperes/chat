<?
    $this->layout = 'chat';
    $this->js     = 'pages/conversation.js';
    $this->widget->enable('ui');
    $this->widget->enable('toast');
?>

<div class="conversation">

    <div class="floating-button-group">
        <button class="form-button quit-button">Quit</button>
    </div>

    <div class="info-bar">
        <img src="<?=URL::baseUrl();?>/public/images/<?=$this->talkingTo['sex'];?>.png" alt="" />
        <?=$this->talkingTo['type'];?>: <?=htmlspecialchars($this->talkingTo['name']);?>
    </div>

    <div class="history-wrapper">

        <div class="history">

            <? foreach( $this->messages as $message ){ ?>

                <div class="<?=$message['who'];?>" message_id="<?=$message['id_chat_message'];?>">
                    <p class="message"><?=$message['message'];?></p>
                    <p class="datetime"><?=$message['datetime'];?></p>
                </div>

                <p class="clearfix">&nbsp;</p>

            <? } ?>

        </div>

        <div unselectable="on" class="typing unselectable" title="<?=strtok($this->talkingTo['name'], ' ');?> is typing...">&nbsp;</div>

    </div>

    <div class="type-message">

        <textarea name="message" id="message" autofocus="" placeholder="Type your message..." class="form-textarea"></textarea>

        <button class="form-button send-button" disabled="disabled">
            <img src="<?=URL::baseUrl();?>/public/images/send-message.png" alt="Send" />
        </button>

    </div>

    <input type="hidden" id="chat_id" value="<?=$this->idChat;?>" />

</div>