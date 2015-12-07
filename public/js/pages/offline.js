/*
 *  Copyright (c) 2015, Sandro Alves Peres
 *  All rights reserved.
 *
 *  http://www.zend.com/en/yellow-pages/ZEND022656
 */

function sendEmail()
{
    try{ $().toastmessage('removeToast', toast); }catch(e){}
    try{ $().toastmessage('removeToast', toastEmail); }catch(e){}

    var error     = false;
    var selectors = ['#email', '#name', ':radio:checked', '#subject', '#message'];

    for( var i in selectors )
    {
        if( !$(selectors[i]).val() )
        {
            error = true;

            if( $(selectors[i]).is(':input') )
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
        $('#send_email').disable();
        $('#loading').show();

        $.ajax({
            url: $.base() + '/client/sendEmail',
            type: 'POST',
            dataType: 'json',
            data: {
                'name'    : $('#name').val(),
                'email'   : $('#email').val(),
                'sex'     : $(':radio:checked').val(),
                'subject' : $('#subject').val(),
                'message' : $('#message').val()
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
                        showSuccess('SUCCESS', 'Your message was sent successfully!<br />We will reply soon.', 320, 170, function()
                        {
                            try
                            {
                                window.close();
                            }
                            catch(e){}

                            $('#loading').show();
                            $(this).dialog('destroy');

                            window.location = $.base();
                        });
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
            },
            complete: function()
            {
                $('#send_email').enable();
            }
        });
    }
    else
    {
        $('#send_email').disable();

        setTimeout(function()
        {
            $('#send_email').enable();
        },
        1000);
    }
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

    $('#send_email').disable();
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
            $('#send_email').enable();
        }
    });
}

$(function()
{
    $(':radio').radio();
    $('#email').blur(loadForm);
    $('#send_email').click(sendEmail);

    onChangeRemoveMandatory();

    $().toastmessage('showToast', {
        text      : 'Our support is offline',
        stayTime  : 3000,
        sticky    : false,
        position  : 'top-right',
        type      : 'notice',
        closeText : ''
    });
});