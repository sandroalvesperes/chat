/*
 *  Copyright (c) 2015, Sandro Alves Peres
 *  All rights reserved.
 *
 *  http://www.zend.com/en/yellow-pages/ZEND022656
 */ 

function checkSupportInactivity()
{
    $.ajax({
        url: $.base() + '/support/checkSupportInactivity',
        type: 'POST',
        dataType: 'json',
        data: {},
        async: true,
        timeout: 7000,
        cache: false
    });
}

$(function()
{
    checkSupportInactivity();
    setInterval(checkSupportInactivity, 1000 * 60 * 30); // every 30 minutes
});