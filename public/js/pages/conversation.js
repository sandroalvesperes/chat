/*
 *  Copyright (c) 2015, Sandro Alves Peres
 *  All rights reserved.
 *
 *  http://www.zend.com/en/yellow-pages/ZEND022656
 */

var queueAjax = [];

function sendMessage( message )
{
    var className = 'Sending';

    if( typeof message != 'string' )
    {
        message = $('#message').val();
    }

    var messageFormatted = message.replace(/</g, '&lt;');
        messageFormatted = messageFormatted.replace(/>/g, '&gt;');
        messageFormatted = messageFormatted.replace(/\r?\n/g, '<br />');

    if( queueAjax.length > 0 )
    {
        if( queueAjax[0].sent )
        {
            className = 'Waiting';
        }
    }

    var objMessage = $(
        '<div class="me">' +
            '<p class="message">' + messageFormatted + '</p>' +
            '<p class="unselectable ' + className.toLowerCase() + '">' + className + '</p>' +
        '</div>' +
        '<p class="clearfix">&nbsp;</p>'
    );

    $('#message').val('');
    $('#message').focus();
    $('.send-button').disable();

    appendNewMessages(objMessage, 'me');

    queueAjax.push({
        'functionAjax' : sendMessageQueue,
        'objMessage'   : objMessage,
        'message'      : message,
        'sent'         : false
    });

    try
    {
        ajaxTypingMessage.abort();
    }
    catch(e){}
    finally
    {
        typingOff();
    }
}

function sendMessageQueue( objMessage, message )
{
    var chat_id = $('#chat_id').val();

    $.ajax({
        url: $.base() + '/conversation/sendMessage',
        type: 'POST',
        dataType: 'json',
        data: {
            'chat_id' : chat_id,
            'message' : message
        },
        async: true,
        timeout: 30000,
        cache: false,
        success: function( data )
        {
            queueAjax.shift();
            objMessage.find('p:eq(1)').removeClass();

            if( data )
            {
                if( data.ok )
                {
                    objMessage.attr('message_id', data.message_id);
                    objMessage.find('p:eq(1)').addClass('datetime');
                    objMessage.find('p:eq(1)').html( data.datetime );
                }
                else
                {
                    objMessage.find('p:eq(1)').addClass('error');
                    objMessage.find('p:eq(1)').text( data.msg );
                }
            }
            else
            {
                objMessage.find('p:eq(1)').addClass('error');
                objMessage.find('p:eq(1)').text('Not sent');
            }
        },
        error: function( response, status, xhr )
        {
            queueAjax.shift();
            objMessage.find('p:eq(1)').removeClass();
            objMessage.find('p:eq(1)').addClass('error');
            objMessage.find('p:eq(1)').text('Not sent');
        },
        complete: function()
        {
            objMessage.find('p:eq(1).error').click(function()
            {
                var objError = $(this);

                showConfirm('Message not sent', 'Send again?', 280, 160, function()
                {
                    $(this).dialog('destroy');

                    var message = $(objError).prev().html();
                        message = message.replace('&lt;', '<', 'gi');
                        message = message.replace('&gt;', '>', 'gi');
                        message = message.replace(/<br\s?\/?>/gi, "\n");

                    sendMessage(message);
                    objError.closest('div').remove();
                });
            });
        }
    });
}

function queueCheck() // Executes the queue of messages
{
    if( queueAjax.length > 0 )
    {
        if( !queueAjax[0].sent )
        {
            queueAjax[0].objMessage.find('p:eq(1)').removeClass();
            queueAjax[0].objMessage.find('p:eq(1)').addClass('sending');
            queueAjax[0].objMessage.find('p:eq(1)').text('Sending');

            queueAjax[0].sent = true;
            queueAjax[0].functionAjax.call(this, queueAjax[0].objMessage, queueAjax[0].message);
        }
    }
}

function updateReceivedMessages()
{
    var chat_id         = $('#chat_id').val();
    var last_message_id = $('.history .you:last').attr('message_id');

    if( isNaN(last_message_id) )
    {
        last_message_id = 0;
    }

    try
    {
        ajaxNewMessages.abort();
    }
    catch(e){}

    ajaxNewMessages = $.ajax({
        url: $.base() + '/conversation/messages',
        type: 'POST',
        dataType: 'html',
        data: {
            'chat_id' : chat_id,
            'last_message_id' : last_message_id
        },
        async: true,
        timeout: 30000,
        cache: false,
        success: function( data )
        {
            if( data )
            {
                appendNewMessages(data, 'you');
            }
        },
        error: function( response, status, xhr )
        {
            if( status != 'abort' )
            {
                $().toastmessage('showToast', {
                    text      : 'Error updating messages',
                    stayTime  : 3000,
                    sticky    : false,
                    position  : 'top-right',
                    type      : 'error',
                    closeText : ''
                });
            }
        }
    });
}

function typingOn( ev )
{
    var key     = (ev.keyCode ? ev.keyCode : ev.which);
    var chat_id = $('#chat_id').val();

    if( key == 13 && $.trim($('#message').val()) == '' )
    {
        return false;
    }

    try
    {
        clearTimeout(timeOutTypingOff);
    }
    catch(e){}

    try
    {
        if( typeof ajaxTypingMessage == 'object' ) // Previous request is still sending
        {
            return true;
        }
    }
    catch(e){}

    ajaxTypingMessage = $.ajax({
        url: $.base() + '/conversation/typing',
        type: 'POST',
        dataType: 'json',
        data: {
            'chat_id' : chat_id,
            'typing'  : 1
        },
        async: true,
        timeout: 4000,
        cache: false,
        success: function( data )
        {
            if( data )
            {
                if( data.ok )
                {
                    timeOutTypingOff = setTimeout(typingOff, 2000);
                }
            }
        },
        complete: function()
        {
            delete ajaxTypingMessage;
        }
    });

    return true;
}

function typingOff()
{
    var chat_id = $('#chat_id').val();

    $.ajax({
        url: $.base() + '/conversation/typing',
        type: 'POST',
        dataType: 'json',
        data: {
            'chat_id' : chat_id,
            'typing'  : 0
        },
        async: true,
        timeout: 4000,
        cache: false,
        success: function( data )
        {
            if( data )
            {
                if( data.ok )
                {
                    try
                    {
                        ajaxTypingMessage.abort();
                    }
                    catch(e){}
                }
            }
        }
    });
}

function typingCheck()
{
    var chat_id = $('#chat_id').val();

    $.ajax({
        url: $.base() + '/conversation/typingCheck',
        type: 'POST',
        dataType: 'json',
        data: {
            'chat_id' : chat_id
        },
        async: true,
        timeout: 3000,
        cache: false,
        success: function( data )
        {
            if( data )
            {
                if( data.ok )
                {
                    if( data.typing )
                    {
                        $('.typing').show();
                    }
                    else
                    {
                        $('.typing').hide();
                    }
                }
            }
        }
    });
}

function chatClosedCheck()
{
    var chat_id = $('#chat_id').val();

    $.ajax({
        url: $.base() + '/conversation/closedCheck',
        type: 'POST',
        dataType: 'json',
        data: {
            'chat_id' : chat_id
        },
        async: true,
        timeout: 3000,
        cache: false,
        success: function( data )
        {
            if( data )
            {
                if( data.ok )
                {
                    if( data.closed )
                    {
                        try
                        {
                            clearInterval(intervalQueue);
                            clearInterval(intervalMessages);
                            clearInterval(intervalTypingCheck);
                            clearInterval(intervalClosedCheck);
                        }
                        catch(e){}

                        try
                        {
                            ajaxNewMessages.abort();
                        }
                        catch(e){}

                        var supportMsg = (data.closed_by == 'Support' && data.type == 'Client' ? '<br />It was closed by the support.' : '');

                        showWarning('CHAT CLOSED', 'This chat is no longer active.' + supportMsg, 350, 180, function()
                        {
                            if( 'Support' == data.type )
                            {
                                try
                                {
                                    opener.focus();
                                    opener.updateClientsList();
                                }
                                catch(e){}

                                window.close();
                            }
                            else
                            {
                                $(this).dialog('destroy');
                                $('#loading').show();

                                window.location = $.base() + '/conversation/evaluate';
                            }
                        });
                    }
                }
            }
        }
    });
}

function appendNewMessages( objMessage, who ) // who = (me or you)
{
    var moveToEnd = false;

    if( who == 'you' ) // if the chatmate sent me a message
    {
        var innerHeight  = $('.history').innerHeight();
        var scrollHeight = $('.history').prop('scrollHeight');
        var scrollTop    = $('.history').scrollTop();

        var difference = (scrollHeight - (innerHeight + scrollTop));
        var percentage = ((difference * 100) / scrollHeight);
            percentage = (new Number(percentage)).toFixed(2);
            percentage = parseFloat(percentage);

        // if the difference from the scroll position to
        // the end is more than 2%, it triggers the sound.
        // Otherwise it just moves the scroll to the end

        if( percentage > 2 )
        {
            try
            {
                audioNewMessage.play();
            }
            catch(e){}
        }
        else
        {
            moveToEnd = true;
        }
    }
    else
    {
        moveToEnd = true; // me
    }

    $('.typing').hide();
    $('.history').append( objMessage );

    if( moveToEnd )
    {
        moveScrollToEnd();
    }
}

function quitConversation()
{
    showConfirm('QUIT', 'Wanna quit this conversation?', 300, 160, function()
    {
        try
        {
            clearInterval(intervalQueue);
            clearInterval(intervalMessages);
            clearInterval(intervalTypingCheck);
            clearInterval(intervalClosedCheck);
        }
        catch(e){}

        try
        {
            ajaxNewMessages.abort();
        }
        catch(e){}

        $(this).dialog('destroy');
        $('#loading').show();

        var chat_id = $('#chat_id').val();

        $.ajax({
            url: $.base() + '/conversation/quit',
            type: 'POST',
            dataType: 'json',
            data: {
                'chat_id' : chat_id
            },
            async: true,
            timeout: 3000,
            cache: false,
            success: function( data )
            {
                if( data )
                {
                    if( data.ok )
                    {
                        if( 'Support' == data.type )
                        {
                            try
                            {
                                opener.focus();
                                opener.updateClientsList();
                            }
                            catch(e){}

                            window.close();
                        }
                        else
                        {
                            $(this).dialog('destroy');
                            $('#loading').show();

                            window.location = $.base() + '/conversation/evaluate';
                        }
                    }
                    else
                    {
                        $('#loading').hide();

                        $().toastmessage('showToast', {
                            text      : data.msg,
                            stayTime  : 4000,
                            sticky    : false,
                            position  : 'top-right',
                            type      : 'error',
                            closeText : ''
                        });
                    }
                }
                else
                {
                    $('#loading').hide();

                    $().toastmessage('showToast', {
                        text      : 'Error occurred',
                        stayTime  : 4000,
                        sticky    : false,
                        position  : 'top-right',
                        type      : 'error',
                        closeText : ''
                    });
                }
            },
            error: function( response, status, xhr )
            {
                $('#loading').hide();

                $().toastmessage('showToast', {
                    text      : 'Error occurred',
                    stayTime  : 4000,
                    sticky    : false,
                    position  : 'top-right',
                    type      : 'error',
                    closeText : ''
                });
            }
        });
    });
}

function keyUpMessage( ev )
{
    if( $.trim($(this).val()) != '' )
    {
        $('.send-button').enable();
    }
    else
    {
        $('.send-button').disable();
    }
}

function keyPressMessage( ev )
{
    var key = (ev.keyCode ? ev.keyCode : ev.which);

    if( $.trim($(this).val()) != '' )
    {
        if( ev.shiftKey && key == 13 ) // Shift + Enter
        {
            return true;
        }

        if( key == 13 ) // Enter
        {
            sendMessage();
            return false;
        }
    }
    else
    {
        if( key == 13 )
        {
            return false;
        }
    }

    return true;
}

function moveScrollToEnd()
{
    var scrollHeight = $('.history').prop('scrollHeight');

    $('.history').scrollTop( scrollHeight );
}

$(function()
{
    $(window).on('beforeunload', function()
    {
        try
        {
            clearInterval(intervalQueue);
            clearInterval(intervalMessages);
            clearInterval(intervalTypingCheck);
            clearInterval(intervalClosedCheck);
        }
        catch(e){}
    });

    // Load the audio
    // -------------------------------------------------------------------------

    try
    {
        var audioFile = $.base() + '/public/sounds/facebook-chat.mp3';
        audioNewMessage = new Audio(audioFile);
    }
    catch(e){}

    $('#message').val('');
    $('#message').keyup(keyUpMessage);
    $('#message').keypress(keyPressMessage);
    $('#message').keydown(typingOn);

    $('.send-button').click(sendMessage);
    $('.quit-button').click(quitConversation);

    setTimeout(function()
    {
        intervalQueue       = setInterval(queueCheck, 500);
        intervalMessages    = setInterval(updateReceivedMessages, 2000);
        intervalTypingCheck = setInterval(typingCheck, 2000);
        intervalClosedCheck = setInterval(chatClosedCheck, 5000);
    },
    1000);

    setTimeout(moveScrollToEnd, 300);
});