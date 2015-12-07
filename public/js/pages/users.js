/*
 *  Copyright (c) 2015, Sandro Alves Peres
 *  All rights reserved.
 *
 *  http://www.zend.com/en/yellow-pages/ZEND022656
 */

function filterUsers( showLoading )
{
    var name       = $('#filter_name').val();
    var email      = $('#filter_email').val();
    var active_yes = ($('#filter_active_yes').is(':checked') ? 1 : 0);
    var active_no  = ($('#filter_active_no').is(':checked') ? 1 : 0);
    var online_yes = ($('#filter_online_yes').is(':checked') ? 1 : 0);
    var online_no  = ($('#filter_online_no').is(':checked') ? 1 : 0);

    $('.tipsy').hide();

    if( showLoading !== false )
    {
        $('#loading').show();
    }

    $.ajax({
        url: $.base() + '/users/list',
        type: 'POST',
        dataType: 'html',
        data: {
            'name'       : name,
            'email'      : email,
            'active_yes' : active_yes,
            'active_no'  : active_no,
            'online_yes' : online_yes,
            'online_no'  : online_no
        },
        async: true,
        timeout: 20000,
        cache: false,
        success: function( data )
        {
            if( data )
            {
                $('#users_list').html( data );
                $('#users_list button[tip]').tipsy({gravity: 'e', title: 'tip'});
            }
            else
            {
                $().toastmessage('showToast', {
                    text      : 'Error filtering users',
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
                    text      : 'Error filtering users',
                    stayTime  : 4000,
                    sticky    : false,
                    position  : 'top-right',
                    type      : 'error',
                    closeText : ''
                });
            }
        },
        complete: function()
        {
            if( showLoading !== false )
            {
                $('#loading').hide();
            }
        }
    });
}

function newUser()
{
    $('#loading').show();

    $.get($.base() + '/users/newUser', {}, function(data, textStatus)
    {
        $('#loading').hide();

        if( textStatus == 'success' )
        {
            showModal('New User', data, 550, 450,
            {
                'Save': function()
                {
                    newUserSave();
                },
                'Cancel': function()
                {
                    $(this).dialog('destroy');
                }
            });

            $('.form-user :radio').radio();
            $('.form-user fieldset :checkbox').checkbox();

            $('#user_status').switchStyle({
                'label_on'   : 'Active',
                'label_off'  : 'Inactive',
                'label_size' : 12,
                'width'      : 140,
                'height'     : 25
            });

            $('#name').focus();
            onChangeRemoveMandatory();
        }
        else
        {
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
}

function editUser( id, name )
{
    $('#loading').show();

    $.get($.base() + '/users/editUser', {'id' : id}, function(data, textStatus)
    {
        $('#loading').hide();

        if( textStatus == 'success' )
        {
            showModal('Edit User: ' + name, data, 550, 450,
            {
                'Save': function()
                {
                    editUserSave(id);
                },
                'Cancel': function()
                {
                    $(this).dialog('destroy');
                }
            });

            $('.form-user :radio').radio();
            $('.form-user fieldset :checkbox').checkbox();

            $('#user_status').switchStyle({
                'label_on'   : 'Active',
                'label_off'  : 'Inactive',
                'label_size' : 12,
                'width'      : 140,
                'height'     : 25
            });

            $('#name').focus();
            onChangeRemoveMandatory();
        }
        else
        {
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
}

function validateFormUser( edit )
{
    try{$().toastmessage('removeToast', toast);}catch(e){}
    try{$().toastmessage('removeToast', toastEmail);}catch(e){}

    var error = false;

    $('.form-user :text').each(function()
    {
        if( !$(this).val() && !($(this).attr('id') == 'password' && edit === true) )
        {
            error = true;
            $(this).addClass('mandatory');
        }
    });

    if( $('.form-user :radio:checked').size() == 0 )
    {
        error = true;
        $('#male, #female').next().addClass('radio-element-mandatory');
    }

    if( error )
    {
        error = true;

        toast = $().toastmessage('showToast', {
            text      : 'All fields are required',
            stayTime  : 4000,
            sticky    : false,
            position  : 'top-right',
            type      : 'error',
            closeText : ''
        });
    }

    if( $('#email').val() && !$.isEmail($('#email').val()) )
    {
        error = true;
        $('#email').addClass('mandatory');

        toastEmail = $().toastmessage('showToast', {
            text      : 'Invalid e-mail',
            stayTime  : 4500,
            sticky    : false,
            position  : 'top-right',
            type      : 'error',
            closeText : ''
        });
    }

    return !error;
}

function onChangeRemoveMandatory()
{
    $('.form-user :text').change(function()
    {
        $(this).removeClass('mandatory');
    });

    $('.form-user :radio').change(function()
    {
        $(this).next().removeClass('radio-element-mandatory');
    });

    $('.form-user label, .form-user #jQueryRadiomale, .form-user #jQueryRadiofemale').click(function()
    {
        $('.form-user #jQueryRadiomale, .form-user #jQueryRadiofemale').removeClass('radio-element-mandatory');
    });
}

function newUserSave()
{
    if( !validateFormUser() )
    {
        $('.ui-dialog-buttonpane button:first').disable();
        $('.ui-dialog-buttonpane button:first').addClass('ui-state-disabled');

        setTimeout(function()
        {
            $('.ui-dialog-buttonpane button:first').enable();
            $('.ui-dialog-buttonpane button:first').removeClass('ui-state-disabled');
        },
        1000);

        return;
    }

    var name     = $('#name').val();
    var email    = $('#email').val();
    var sex      = $('.form-user :radio:checked').val()
    var password = $('#password').val();
    var active   = ($('#user_status').is(':checked') ? 1 : 0);
    var access_maintain_user  = ($('#maintain_user').is(':checked') ? 1 : 0);
    var access_support_status = ($('#support_status').is(':checked') ? 1 : 0);

    $('#loading').show();

    $.ajax({
        url: $.base() + '/users/newUser',
        type: 'POST',
        dataType: 'json',
        data: {
            'name'                  : name,
            'email'                 : email,
            'sex'                   : sex,
            'password'              : password,
            'active'                : active,
            'access_maintain_user'  : access_maintain_user,
            'access_support_status' : access_support_status
        },
        async: true,
        timeout: 10000,
        cache: false,
        success: function( data )
        {
            if( data )
            {
                if( data.ok )
                {
                    $('#modalWindow').dialog('destroy');
                    filterUsers(false);

                    $().toastmessage('showToast', {
                        text      : 'New user saved successfully',
                        stayTime  : 4000,
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
                    text      : 'Error saving user',
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
                    text      : 'Error saving user',
                    stayTime  : 4000,
                    sticky    : false,
                    position  : 'top-right',
                    type      : 'error',
                    closeText : ''
                });
            }
        },
        complete: function()
        {
            $('#loading').hide();
        }
    });
}

function editUserSave( id )
{
    if( !validateFormUser(true) )
    {
        $('.ui-dialog-buttonpane button:first').disable();
        $('.ui-dialog-buttonpane button:first').addClass('ui-state-disabled');

        setTimeout(function()
        {
            $('.ui-dialog-buttonpane button:first').enable();
            $('.ui-dialog-buttonpane button:first').removeClass('ui-state-disabled');
        },
        1000);

        return;
    }

    var name     = $('#name').val();
    var email    = $('#email').val();
    var sex      = $('.form-user :radio:checked').val()
    var password = $('#password').val();
    var active   = ($('#user_status').is(':checked') ? 1 : 0);
    var access_maintain_user  = ($('#maintain_user').is(':checked') ? 1 : 0);
    var access_support_status = ($('#support_status').is(':checked') ? 1 : 0);

    $('#loading').show();

    $.ajax({
        url: $.base() + '/users/editUser',
        type: 'POST',
        dataType: 'json',
        data: {
            'id_support_user'       : id,
            'name'                  : name,
            'email'                 : email,
            'sex'                   : sex,
            'password'              : password,
            'active'                : active,
            'access_maintain_user'  : access_maintain_user,
            'access_support_status' : access_support_status
        },
        async: true,
        timeout: 10000,
        cache: false,
        success: function( data )
        {
            if( data )
            {
                if( data.ok )
                {
                    $('#modalWindow').dialog('destroy');
                    filterUsers(false);

                    $().toastmessage('showToast', {
                        text      : 'User information saved successfully',
                        stayTime  : 4000,
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
                    text      : 'Error saving user',
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
                    text      : 'Error saving user',
                    stayTime  : 4000,
                    sticky    : false,
                    position  : 'top-right',
                    type      : 'error',
                    closeText : ''
                });
            }
        },
        complete: function()
        {
            $('#loading').hide();
        }
    });
}

$(function()
{
    $('#filter').click(filterUsers);
    $('.new-user-button').click(newUser);
    $('.filter :checkbox').checkbox();

    $('.back-button').click(function()
    {
        window.location = $.base() + '/support';
    });
});