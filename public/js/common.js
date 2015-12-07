/*
 *  Copyright (c) 2015, Sandro Alves Peres
 *  All rights reserved.
 *
 *  Date: 05/09/2013
 *  http://www.zend.com/en/yellow-pages/ZEND022656
 */ 
 
$.ajaxSetup({
    type: 'POST',
    dataType: 'html', 
    async: true,
    timeout: 30000,
    cache: false
});


function showNotice( title, html, w, h, callback )
{    
    $('.tipsy').hide();
    
    try
    {
        $('#messageUI').dialog('destroy');
    }
    catch(e){}    
    
    $('#messageUI').html('<div class="message-ui"><span class="message-ui-notice"></span><div class="message-ui-description">' + html + '</div></div>');
    $('#messageUI').dialog({
        title: title,
        resizable: false,
        width: w,
        height: h,
        modal: true,
        closeOnEscape: false, 
        draggable: true,
        position: 'center',
        buttons: 
        {
            'Ok': function() 
            {
                if( typeof callback == 'function' )
                {
                    callback.call(this);
                }
                else
                {
                    $(this).dialog('destroy');
                }
            }
        },
        open: function( event, ui )
        { 
            var self = this;
            
            $('#ui-dialog-title-messageUI + .ui-dialog-titlebar-close').attr('href', 'javascript:void(0);');            
            $('#ui-dialog-title-messageUI + .ui-dialog-titlebar-close').unbind();
            $('#ui-dialog-title-messageUI + .ui-dialog-titlebar-close').click(function()
            {
                if( typeof(callback) == 'function' )
                {
                    callback.call(self);   
                }
                else
                {
                    $(self).dialog('destroy');  
                } 
            });
        }      
    });  
    
    if( $.browser.msie ) // fuck IE
    {
        var buttonBar = $('#messageUI + .ui-dialog-buttonpane').height();
        
        if( isNaN(buttonBar) )
        {
            return;
        }
        
        var titleBar = $('.ui-dialog-titlebar').first().height();
        
        if( isNaN(titleBar) )
        {
            return;
        }
        
        $('#messageUI').height(h - buttonBar - titleBar - 30);
    }      
}


function showWarning( title, html, w, h, callback )
{    
    $('.tipsy').hide();
    
    try
    {
        $('#messageUI').dialog('destroy');
    }
    catch(e){}    
    
    $('#messageUI').html('<div class="message-ui"><span class="message-ui-warning"></span><div class="message-ui-description">' + html + '</div></div>');
    $('#messageUI').dialog({
        title: title,
        resizable: false,
        width: w,
        height: h,
        modal: true,
        closeOnEscape: false, 
        draggable: true,
        position: 'center',
        buttons: 
        {
            'Ok': function() 
            {
                if( typeof callback == 'function' )
                {
                    callback.call(this);
                }
                else
                {
                    $(this).dialog('destroy');
                }
            }
        },
        open: function( event, ui )
        { 
            var self = this;
            
            $('#ui-dialog-title-messageUI + .ui-dialog-titlebar-close').attr('href', 'javascript:void(0);');            
            $('#ui-dialog-title-messageUI + .ui-dialog-titlebar-close').unbind();
            $('#ui-dialog-title-messageUI + .ui-dialog-titlebar-close').click(function()
            {
                if( typeof(callback) == 'function' )
                {
                    callback.call(self);   
                }
                else
                {
                    $(self).dialog('destroy');  
                } 
            });
        }      
    }); 
    
    if( $.browser.msie ) // fuck IE
    {
        var buttonBar = $('#messageUI + .ui-dialog-buttonpane').height();
        
        if( isNaN(buttonBar) )
        {
            return;
        }
        
        var titleBar = $('.ui-dialog-titlebar').first().height();
        
        if( isNaN(titleBar) )
        {
            return;
        }
        
        $('#messageUI').height(h - buttonBar - titleBar - 30);
    }     
}


function showError( title, html, w, h, callback )
{    
    $('.tipsy').hide();
    
    try
    {
        $('#messageUI').dialog('destroy');
    }
    catch(e){}    
    
    $('#messageUI').html('<div class="message-ui"><span class="message-ui-error"></span><div class="message-ui-description">' + html + '</div></div>');
    $('#messageUI').dialog({
        title: title,
        resizable: false,
        width: w,
        height: h,
        modal: true,
        closeOnEscape: false, 
        draggable: true,
        position: 'center',
        buttons: 
        {
            'Ok': function() 
            {
                if( typeof callback == 'function' )
                {
                    callback.call(this);
                }
                else
                {
                    $(this).dialog('destroy');
                }
            }
        },
        open: function( event, ui )
        { 
            var self = this;
            
            $('#ui-dialog-title-messageUI + .ui-dialog-titlebar-close').attr('href', 'javascript:void(0);');            
            $('#ui-dialog-title-messageUI + .ui-dialog-titlebar-close').unbind();
            $('#ui-dialog-title-messageUI + .ui-dialog-titlebar-close').click(function()
            {
                if( typeof(callback) == 'function' )
                {
                    callback.call(self);   
                }
                else
                {
                    $(self).dialog('destroy');  
                } 
            });
        }      
    }); 
    
    if( $.browser.msie ) // fuck IE
    {
        var buttonBar = $('#messageUI + .ui-dialog-buttonpane').height();
        
        if( isNaN(buttonBar) )
        {
            return;
        }
        
        var titleBar = $('.ui-dialog-titlebar').first().height();
        
        if( isNaN(titleBar) )
        {
            return;
        }
        
        $('#messageUI').height(h - buttonBar - titleBar - 30);
    }     
}


function showSuccess( title, html, w, h, callback )
{    
    $('.tipsy').hide();
    
    try
    {
        $('#messageUI').dialog('destroy');
    }
    catch(e){}
    
    $('#messageUI').html('<div class="message-ui"><span class="message-ui-success"></span><div class="message-ui-description">' + html + '</div></div>');
    $('#messageUI').dialog({
        title: title,
        resizable: false,
        width: w,
        height: h,
        modal: true,
        closeOnEscape: false, 
        draggable: true,
        position: 'center',
        buttons: 
        {
            'Ok': function() 
            {
                if( typeof callback == 'function' )
                {
                    callback.call(this);
                }
                else
                {
                    $(this).dialog('destroy');
                }
            }
        },
        open: function( event, ui )
        { 
            var self = this;
            
            $('#ui-dialog-title-messageUI + .ui-dialog-titlebar-close').attr('href', 'javascript:void(0);');            
            $('#ui-dialog-title-messageUI + .ui-dialog-titlebar-close').unbind();
            $('#ui-dialog-title-messageUI + .ui-dialog-titlebar-close').click(function()
            {
                if( typeof(callback) == 'function' )
                {
                    callback.call(self);   
                }
                else
                {
                    $(self).dialog('destroy');  
                } 
            });
        }      
    });
    
    if( $.browser.msie ) // fuck IE
    {
        var buttonBar = $('#messageUI + .ui-dialog-buttonpane').height();
        
        if( isNaN(buttonBar) )
        {
            return;
        }
        
        var titleBar = $('.ui-dialog-titlebar').first().height();
        
        if( isNaN(titleBar) )
        {
            return;
        }
        
        $('#messageUI').height(h - buttonBar - titleBar - 30);
    }     
}


function showConfirm( title, html, w, h, callbackYes, callbackNo )
{    
    $('.tipsy').hide();
    
    try
    {
        $('#messageUI').dialog('destroy');
    }
    catch(e){}    
    
    $('#messageUI').html('<div class="message-ui"><span class="message-ui-confirm"></span><div class="message-ui-description">' + html + '</div></div>');
    $('#messageUI').dialog({
        title: title,
        resizable: false,
        width: w,
        height: h,
        modal: true,
        closeOnEscape: false, 
        draggable: true,
        position: 'center',
        buttons: 
        {
            'Ok': function() 
            {
                callbackYes.call(this);
            },
            'Cancel': function() 
            {
                if( typeof(callbackNo) == 'function' )
                {
                    callbackNo.call(this);   
                }
                else
                {
                    $(this).dialog('destroy');   
                }
            }            
        },
        open: function( event, ui )
        { 
            var self = this;
            
            $('#ui-dialog-title-messageUI + .ui-dialog-titlebar-close').attr('href', 'javascript:void(0);');            
            $('#ui-dialog-title-messageUI + .ui-dialog-titlebar-close').unbind();
            $('#ui-dialog-title-messageUI + .ui-dialog-titlebar-close').click(function()
            {
                if( typeof(callbackNo) == 'function' )
                {
                    callbackNo.call(self);   
                }
                else
                {
                    $(self).dialog('destroy');  
                } 
            });
        }      
    }); 
    
    if( $.browser.msie ) // fuck IE
    {
        var buttonBar = $('#messageUI + .ui-dialog-buttonpane').height();
        
        if( isNaN(buttonBar) )
        {
            return;
        }
        
        var titleBar = $('.ui-dialog-titlebar').first().height();
        
        if( isNaN(titleBar) )
        {
            return;
        }
        
        $('#messageUI').height(h - buttonBar - titleBar - 30);
    }     
}


function showModal( title, html, w, h, buttons )
{    
    $('.tipsy').hide();
    
    try
    {
        $('#modalWindow').dialog('destroy');
    }
    catch(e){}    
    
    $('#modalWindow').html(html);
    $('#modalWindow').dialog({
        title: title,
        resizable: false,
        width: w,
        height: h,
        modal: true,
        closeOnEscape: false, 
        draggable: true,
        position: 'center',
        buttons: buttons,
        open: function( event, ui )
        { 
            $('#ui-dialog-title-modalWindow + .ui-dialog-titlebar-close').remove();
        }      
    }); 
    
    if( $.browser.msie ) // fuck IE
    {
        var buttonBar = $('#modalWindow + .ui-dialog-buttonpane').height();
        
        if( isNaN(buttonBar) )
        {
            return;
        }
        
        var titleBar = $('.ui-dialog-titlebar').first().height();
        
        if( isNaN(titleBar) )
        {
            return;
        }
        
        $('#modalWindow').height(h - buttonBar - titleBar - 30);
    }  
    
    $('#modalWindow :text').alwaysTrim();
}


// AutoLoad
// *****************************************************************************

$(document).ready(function()
{
    
    if( $("#messageUI").size() == 0 )
    {
        $('<div id="messageUI"></div>').prependTo('body');   
    }
    
    if( $("#modalWindow").size() == 0 )
    {
        $('<div id="modalWindow"></div>').prependTo('body');   
    }    
    
    $(':text, textarea').alwaysTrim();
    
});


// JQuery Plugin
// By Sandro Alves Peres
(function($){
    

    $.fn.onlyNumbers = function()
    {
        $(this).keypress(function(ev)
        {
            var chr = (!isNaN(ev.charCode) ? ev.charCode : (!isNaN(ev.keyCode) ? ev.keyCode : ev.which));

            if( ev.ctrlKey && (chr == 118 || chr == 86) ) // Ctrl + V
            {
                return true;
            }

            if( chr != 8 && chr != 9 && chr != 0 && (chr < 48 || chr > 57) )
            {
                return false;
            }

            return true;
        });
        
        $(this).bind('paste', function(ev)
        {
            try
            {
                if( !$.browser.msie )
                {
                    ev.preventDefault();
                    var clipboard = ev.originalEvent.clipboardData.getData('text/plain');           
                    $(this).val( clipboard.replace(/\D/g, '') );
                }
            }
            catch(e){}
        });
        
        $(this).bind('beforepaste', function(ev)
        {
            try
            {
                var clipboard = window.clipboardData.getData('Text');
                window.clipboardData.setData('Text', clipboard.replace(/\D/g, ''));
            }
            catch(e){}
        }); 
    };
    
    
    $.fn.alwaysTrim = function()
    {
        $(this).blur(function()
        {
            $(this).val( $.trim($(this).val()) );
        });
    };
    
    
    $.fn.enable = function()
    {
        $(this).removeAttr('disabled');
    }; 
    
    
    $.fn.disable = function()
    {
        $(this).attr('disabled', 'disabled');
    };    
    
    
    $.fn.exists = function()
    {
        return $(this).length > 0 ? true : false;
    };  
    
    
    $.base = function()
    {
        return $('base').attr('href');
    };  
    
    
    $.trim = function( str )
    {
        return str.replace(/^[\n\r\s\t]+|[\n\r\s\t]+$/gi, '');
    };
    
    
    $.isEmail = function( email )
    {
        var expEmail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{1,3})+$/; // regular expression for email
	
        if( email.lastIndexOf('.') == (email.length - 1) )
        {
            return false;
        }

        var arrEmail = email.split('.');
        
        try
        {
            if( arrEmail[ arrEmail.length - 1 ].length >= 4 )
            {
                return false;  
            }
        }
        catch(e){}  
 
        return expEmail.exec(email);
    };
    

})(jQuery);