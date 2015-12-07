/*
 *  Copyright (c) 2015, Sandro Alves Peres
 *  All rights reserved.
 *
 *  http://www.zend.com/en/yellow-pages/ZEND022656
 */

function updateClientsList()
{
    ajaxClientsList = $.ajax({
        url: $.base() + '/support/clientsList',
        type: 'POST',
        dataType: 'html',
        data: {},
        async: true,
        timeout: 30000,
        cache: false,
        success: function( data )
        {
            if( data )
            {
                $('.tipsy').hide();
                $('#clients_list').html( data );
                $('#clients_list button[tip]').tipsy({gravity: 'e', title: 'tip'});
            }
            else
            {
                $().toastmessage('showToast', {
                    text      : 'Error updating clients list',
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
            if( status != 'abort' )
            {
                $().toastmessage('showToast', {
                    text      : 'Error updating clients list',
                    stayTime  : 4000,
                    sticky    : false,
                    position  : 'top-right',
                    type      : 'error',
                    closeText : ''
                });
            }
        }
    });
}

function openChat( idChat, obj )
{
    showConfirm('CONFIRM', 'Wanna open this chat?', 300, 160, function()
    {
        $('#loading').show();
        $(this).dialog('destroy');

        $.post($.base() + '/support/register', {'idChat' : idChat}, function(data, textStatus)
        {
            $('#loading').hide();

            if( textStatus != 'success' && textStatus != 'abort' )
            {
                $().toastmessage('showToast', {
                    text      : 'Error occurred trying to open the chat',
                    stayTime  : 3000,
                    sticky    : false,
                    position  : 'top-right',
                    type      : 'error',
                    closeText : ''
                });
            }
            else if( textStatus == 'success' )
            {
                if( data )
                {
                    if( data.ok )
                    {
                        if( $('#hdn_user_occupied').exists() )
                        {
                            $('#hdn_user_occupied').val('1');
                        }

                        $(obj).next().remove();
                        openChatPopup( idChat );
                    }
                    else
                    {
                        $().toastmessage('showToast', {
                            text      : data.msg,
                            stayTime  : 3000,
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
                        text      : 'Error occurred',
                        stayTime  : 3000,
                        sticky    : false,
                        position  : 'top-right',
                        type      : 'error',
                        closeText : ''
                    });
                }
            }
        },
        'json');
    });
}

function openChatPopup( idChat )
{
    var width  = 430;
    var height = 460;
    var top    = 0;
    var left   = 0;

    if( screen.availHeight > height )
    {
        top = Math.floor((screen.availHeight - height) / 2) - (screen.height - screen.availHeight);
    }

    if( screen.width > width )
    {
        left = Math.floor((screen.width - width) / 2);
    }

    var options = [];
        options.push('top=' + top, 'left=' + left);
        options.push('width=' + width, 'height=' + height);
        options.push('directories=no', 'resizable=yes');
        options.push('scrollbars=no', 'status=no');
        options.push('titlebar=no', 'toolbar=no');
        options.push('menubar=no', 'location=no');
        options.push('fullscreen=no', 'channelmode=no');

    try
    {
        var win = window.open($.base() + '/conversation/?id=' + idChat, '_blank', options.join(',') , true);
            win.focus();
    }
    catch(e)
    {
        $().toastmessage('showToast', {
            text      : 'Popups aren\'t enabled in your browser!',
            stayTime  : 5000,
            sticky    : false,
            position  : 'top-right',
            type      : 'error',
            closeText : ''
        });
    }
}

function disconnectClient( idChat, obj )
{
    showConfirm('CONFIRM', 'Wanna disconnect this client?', 300, 160, function()
    {
        $(this).dialog('destroy');
        $('#loading').show();

        $.post($.base() + '/support/disconnectClient', {'chat_id' : idChat}, function(data, textStatus)
        {
            $('#loading').hide();

            if( textStatus != 'success' && textStatus != 'abort' )
            {
                $().toastmessage('showToast', {
                    text      : 'Error occurred disconnecting the client',
                    stayTime  : 3000,
                    sticky    : false,
                    position  : 'top-right',
                    type      : 'error',
                    closeText : ''
                });
            }
            else if( textStatus == 'success' )
            {
                if( data )
                {
                    if( data.ok )
                    {
                        try
                        {
                            ajaxClientsList.abort();
                        }
                        catch(e){}

                        $(obj).closest('tr').remove();

                        $().toastmessage('showToast', {
                            text      : 'Client disconnected',
                            stayTime  : 3000,
                            sticky    : false,
                            position  : 'top-right',
                            type      : 'success',
                            closeText : ''
                        });
                    }
                    else
                    {
                        $().toastmessage('showToast', {
                            text      : data.msg,
                            stayTime  : 3000,
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
                        text      : 'Error occurred',
                        stayTime  : 3000,
                        sticky    : false,
                        position  : 'top-right',
                        type      : 'error',
                        closeText : ''
                    });
                }
            }
        },
        'json');
    });
}

function quitSupport()
{
    showConfirm('CONFIRM', 'Wish to quit your session?', 300, 160, function()
    {
        $('#loading').show();
        $(this).dialog('destroy');
        window.location = $.base() + '/login/signOut';
    });
}

function changeSupportStatus()
{
    var status = $(this).is(':checked');

    try
    {
        ajaxSupportStatus.abort();
    }
    catch(e){}

    ajaxSupportStatus = $.ajax({
        url: $.base() + '/support/supportStatus',
        type: 'POST',
        dataType: 'json',
        data: {
            'status': status
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
                    $().toastmessage('showToast', {
                        text      : 'Support is ' + (status ? 'online' : 'offline') + ' now',
                        stayTime  : 2500,
                        sticky    : false,
                        position  : 'top-right',
                        type      : 'notice',
                        closeText : ''
                    });
                }
                else
                {
                    $('#support_status').switchStyle( status ? 'off' : 'on' );

                    $().toastmessage('showToast', {
                        text      : 'Error changing support status',
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
                $().toastmessage('showToast', {
                    text      : 'Error changing support status',
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
            if( status != 'abort' )
            {
                $().toastmessage('showToast', {
                    text      : 'Error changing support status',
                    stayTime  : 4000,
                    sticky    : false,
                    position  : 'top-right',
                    type      : 'error',
                    closeText : ''
                });
            }
        }
    });
}

function changeUserStatus()
{
    var status = $(this).is(':checked');

    try
    {
        ajaxUserStatus.abort();
    }
    catch(e){}

    ajaxUserStatus = $.ajax({
        url: $.base() + '/support/userStatus',
        type: 'POST',
        dataType: 'json',
        data: {
            'status': status
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
                    $().toastmessage('showToast', {
                        text      : 'You are ' + (status ? 'online' : 'offline') + ' now',
                        stayTime  : 2500,
                        sticky    : false,
                        position  : 'top-right',
                        type      : 'notice',
                        closeText : ''
                    });
                }
                else
                {
                    $('#user_status').switchStyle( status ? 'off' : 'on' );

                    $().toastmessage('showToast', {
                        text      : 'Error changing user status',
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
                $().toastmessage('showToast', {
                    text      : 'Error changing user status',
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
            if( status != 'abort' )
            {
                $().toastmessage('showToast', {
                    text      : 'Error changing user status',
                    stayTime  : 4000,
                    sticky    : false,
                    position  : 'top-right',
                    type      : 'error',
                    closeText : ''
                });
            }
        }
    });
}

function checkClientsWaiting()
{
    if( $('#hdn_clients_waiting').exists() )
    {
        var supportStatus  = parseInt($('#hdn_support_status_check').val(), 10);
        var clientsWaiting = parseInt($('#hdn_clients_waiting').val(), 10);
        var isUserOccupied = ($('#hdn_user_occupied').val() == '1' ? true : false);

        if( clientsWaiting > 0 && !isUserOccupied )
        {
            try
            {
                audioClientWaiting.play();
            }
            catch(e){}
        }

        if( $('#support_status').exists() )
        {
            if( $('#support_status').is(':checked') != supportStatus )
            {
                $('#support_status').switchStyle(supportStatus ? 'on' : 'off');
            }
        }
        else
        {
            if( $('#hdn_support_status').val() != supportStatus )
            {
                $('#hdn_support_status').val(supportStatus);
                $('.status .description').text(supportStatus ? 'Online' : 'Offline');
                $('.status .description').fadeOut(0);
                $('.status .description').fadeIn(1000);
            }
        }
    }
}

$(function()
{
    $('#support_status').switchStyle({
        width: 80,
        height: 26,
        callback: changeSupportStatus
    });

    $('#user_status').switchStyle({
        width: 80,
        height: 26,
        callback: changeUserStatus
    });

    $('.quit-button').click(quitSupport);
    $('.users-button').click(function()
    {
        $('#loading').show();
        window.location = $.base() + '/users';
    });

    updateClientsList();
    setInterval(updateClientsList, 10000);

    if( ($('#support_status').exists() && !$('#support_status').is(':checked')) || !$('#user_status').is(':checked') || $('#hdn_support_status').val() === '0' )
    {
        $().toastmessage('showToast', {
            text      : 'Clients might not be able to contact the support, because either you or the chat are offline',
            stayTime  : 6000,
            sticky    : false,
            position  : 'top-right',
            type      : 'warning',
            closeText : ''
        });
    }

    // Load audio for clients
    // -------------------------------------------------------------------------

    try
    {
        var audioFile = $.base() + '/public/sounds/hangouts-message.ogg';
        audioClientWaiting = new Audio(audioFile);
    }
    catch(e){}

    setInterval(checkClientsWaiting, 7000);
});