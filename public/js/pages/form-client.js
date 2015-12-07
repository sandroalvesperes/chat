/*
 *  Copyright (c) 2015, Sandro Alves Peres
 *  All rights reserved.
 *
 *  http://www.zend.com/en/yellow-pages/ZEND022656
 */

function signInChat()
{
    try{ $().toastmessage('removeToast', toast); }catch(e){}
    try{ $().toastmessage('removeToast', toastEmail); }catch(e){}

    var error     = false;
    var selectors = ['#email', '#name', ':radio:checked', '#subject'];

    for( var i in selectors )
    {
        if( !$(selectors[i]).val() )
        {
            error = true;

            if( $(selectors[i]).is(':text') )
            {
                $(selectors[i]).addClass('mandatory');
            }
            else
            {
                $('#male, #female').next().addClass('radio-element-mandatory');
            }
        }
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

    if( !error )
    {
        $('#loading').show();

        $.ajax({
            url: $.base() + '/client/register',
            type: 'POST',
            dataType: 'json',
            data: {
                'name'    : $('#name').val(),
                'email'   : $('#email').val(),
                'sex'     : $(':radio:checked').val(),
                'subject' : $('#subject').val()
            },
            async: true,
            timeout: 30000,
            cache: false,
            success: function( data )
            {
                if( data )
                {
                    if( data.ok )
                    {
                        window.location = $.base() + '/client/wait';
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
                        text      : 'Error occurred, try again later',
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
                    text      : 'Error occurred, try again later',
                    stayTime  : 4000,
                    sticky    : false,
                    position  : 'top-right',
                    type      : 'error',
                    closeText : ''
                });
            }
        });
    }
    else
    {
        $('#sign_in').disable();

        setTimeout(function()
        {
            $('#sign_in').enable();
        },
        1000);
    }
}

function loadForm()
{
    try
    {
        ajaxForm.abort();
    }
    catch(e){}
    finally
    {
        $('#email').removeClass('loading');
    }

    if( !$.isEmail( $('#email').val() ) )
    {
        return;
    }

    $('#sign_in').disable();
    $('#email').addClass('loading');

    ajaxForm = $.ajax({
        url: $.base() + '/client/information',
        type: 'POST',
        dataType: 'json',
        data: {
            'email' : $('#email').val()
        },
        async: true,
        timeout: 5000,
        cache: false,
        success: function( data )
        {
            if( data )
            {
                if( data.ok )
                {
                    $(':text').removeClass('mandatory');
                    $('#jQueryRadiomale, #jQueryRadiofemale').removeClass('radio-element-mandatory');

                    $('#name').val( data.name );
                    $('#' + (data.sex == 'F' ? 'female' : 'male')).radio('on');
                    $('#subject').focus();
                }
            }
        },
        complete: function()
        {
            $('#email').removeClass('loading');
            $('#sign_in').enable();
        }
    });
}

function onChangeRemoveMandatory()
{
    $(':text').change(function()
    {
        $(this).removeClass('mandatory');
    });

    $(':radio').change(function()
    {
        $(this).next().removeClass('radio-element-mandatory');
    });

    $('label, #jQueryRadiomale, #jQueryRadiofemale').click(function()
    {
        $('#jQueryRadiomale, #jQueryRadiofemale').removeClass('radio-element-mandatory');
    });
}

$(function()
{
    $(':radio').radio();
    $('#email').blur(loadForm);
    $('#sign_in').click(signInChat);

    onChangeRemoveMandatory();
});