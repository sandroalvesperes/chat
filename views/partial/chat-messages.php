<? foreach( $this->messages as $message ){ ?>

    <div class="you" message_id="<?=$message['id_chat_message'];?>">
        <p class="message"><?=$message['message'];?></p>
        <p class="datetime"><?=$message['datetime'];?></p>
    </div>

    <p class="clearfix">&nbsp;</p>

<? } ?>