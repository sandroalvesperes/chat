/*
 *  Copyright (c) 2015, Sandro Alves Peres
 *  All rights reserved.
 *
 *  http://www.zend.com/en/yellow-pages/ZEND022656
 */

function evaluateSupport()
{
    var rate = $('.evaluate li.selected:last').index() + 1;

    $('#loading').show();

    $.ajax({
        url: $.base() + '/conversation/evaluateSupport',
        type: 'POST',
        dataType: 'json',
        data: {
            'rate' : rate
        },
        async: true,
        timeout: 7000,
        cache: false,
        success: function( data )
        {
            if( data )
            {
                $('#loading').hide();

                if( data.ok )
                {
                    showSuccess('Thank you', 'Thank you for rating our support', 300, 160, function()
                    {
                        $(this).dialog('destroy');
                        $('#loading').show();

                        window.location = $.base();
                    });
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
}

function selectRate()
{
    var index = $(this).index();

    $('.evaluate li').removeClass('selected');

    for( var i=0; i <= index; i++ )
    {
        $('.evaluate li:eq(' + i + ')').addClass('selected');
    }

    $('#evaluate').enable();
}

$(function()
{
    $('#evaluate').click(evaluateSupport);
    $('.evaluate li').click(selectRate);
    $('.evaluate li').tipsy({gravity: 's', title: 'tip'});
});