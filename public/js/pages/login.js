/*
 *  Copyright (c) 2015, Sandro Alves Peres
 *  All rights reserved.
 *
 *  http://www.zend.com/en/yellow-pages/ZEND022656
 */

function signIn()
{
    $('.message-error').slideUp(150);

    var email          = $('#email').val();
    var password       = $('#password').val();
    var remember_email = ($('#remember_email').is(':checked') ? 1 : 0);

    if( email && !$.isEmail(email) )
    {
        $('.message-error').text('Invalid e-mail');
        $('.message-error').slideDown(300);
        return;
    }

    $('#loading').show();

    $.ajax({
        url: $.base() + '/login/signIn',
        type: 'POST',
        dataType: 'json',
        data: {
            'email'          : email,
            'password'       : password,
            'remember_email' : remember_email
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
                    window.location = $.base() + '/support';
                }
                else
                {
                    $('#loading').hide();
                    $('.message-error').text( data.msg );
                    $('.message-error').slideDown(300);
                }
            }
            else
            {
                $('#loading').hide();
                $('.message-error').text('Error occurred');
                $('.message-error').slideDown(300);
            }
        },
        error: function( response, status, xhr )
        {
            $('#loading').hide();
            $('.message-error').text('Error occurred');
            $('.message-error').slideDown(300);
        }
    });
}

$(function()
{
    $('#sign_in').click(signIn);
    $('#remember_email').checkbox();
    $('#email, #password').keypress(function(ev)
    {
        var chr = (ev.keyCode ? ev.keyCode : ev.which);

        if( chr == 13 ) // Enter
        {
            signIn();
        }
    });
});