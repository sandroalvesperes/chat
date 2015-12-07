/*
 *  Copyright (c) 2015, Sandro Alves Peres
 *  All rights reserved.
 *
 *  http://www.zend.com/en/yellow-pages/ZEND022656
 */

function waitingTimeUpdates()
{
    $.ajax({
        url: $.base() + '/client/waitUpdates',
        type: 'POST',
        dataType: 'json',
        data: {},
        async: true,
        timeout: 30000,
        cache: false,
        success: function( data )
        {
            if( data )
            {
                if( data.ok )
                {
                    if( data.operatorsOnline == 0 )
                    {
                        showNotice('SORRY', 'Sorry for the inconvenience!<br />No one of our operators are online anymore.<br /><br />Click "OK" to send us an e-mail', 380, 200, function()
                        {
                            $('#loading').show();
                            $(this).dialog('destroy');
                            window.location = $.base() + '/client/supportOffline';
                        });

                        clearInterval(intervalUpdates);
                    }
                    else if( data.chatClosed )
                    {
                        showNotice('SORRY', 'Sorry for the inconvenience!<br />Your chat was closed.<br /><br />Click "OK" to send us an e-mail', 380, 200, function()
                        {
                            $('#loading').show();
                            $(this).dialog('destroy');
                            window.location = $.base() + '/client/supportOffline';
                        });

                        clearInterval(intervalUpdates);
                    }
                    else if( data.supportResponded )
                    {
                        $('#loading').show();
                        window.location = $.base() + '/conversation';
                    }
                    else if( data.chatStatus == 0 )
                    {
                        showNotice('SORRY', 'Sorry for the inconvenience!<br />Our support is offline.<br /><br />Click "OK" to send us an e-mail', 380, 200, function()
                        {
                            $('#loading').show();
                            $(this).dialog('destroy');
                            window.location = $.base() + '/client/supportOffline';
                        });

                        clearInterval(intervalUpdates);
                    }
                    else
                    {
                        $('.count-down .circle').text( data.clientsWaiting == 0 ? '1' : data.clientsWaiting );
                    }
                }
                else
                {
                    $().toastmessage('showToast', {
                        text      : data.msg,
                        stayTime  : 2000,
                        sticky    : false,
                        position  : 'top-right',
                        type      : 'error',
                        closeText : ''
                    });
                }
            }
            else
            {
                $().toastmessage('showToast', {
                    text      : 'Error ocurred',
                    stayTime  : 2000,
                    sticky    : false,
                    position  : 'top-right',
                    type      : 'error',
                    closeText : ''
                });
            }
        },
        error: function( response, status, xhr )
        {
            $().toastmessage('showToast', {
                text      : 'Error ocurred',
                stayTime  : 2000,
                sticky    : false,
                position  : 'top-right',
                type      : 'error',
                closeText : ''
            });
        }
    });
}

function cancelWaiting()
{
    showConfirm('CANCEL WAITING', 'Wanna cancel the support request?', 320, 160, function()
    {
        $(this).dialog('destroy');
        $('#loading').show();

        $.ajax({
            url: $.base() + '/client/cancel',
            type: 'POST',
            dataType: 'json',
            data: {},
            async: true,
            timeout: 10000,
            cache: false,
            success: function( data )
            {
                if( data )
                {
                    if( data.ok )
                    {
                        window.location = $.base();
                    }
                    else
                    {
                        $('#loading').hide();

                        $().toastmessage('showToast', {
                            text      : data.msg,
                            stayTime  : 2000,
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
                        text      : 'Error ocurred',
                        stayTime  : 2000,
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
                    text      : 'Error ocurred',
                    stayTime  : 2000,
                    sticky    : false,
                    position  : 'top-right',
                    type      : 'error',
                    closeText : ''
                });
            }
        });
    });
}

$(function()
{
    $('.cancel-button').click(cancelWaiting);

    waitingTimeUpdates();
    intervalUpdates = setInterval(waitingTimeUpdates, 7000);
});