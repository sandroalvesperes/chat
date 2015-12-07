/*
*  Copyright (c) 2013, Sandro Alves Peres
*  All rights reserved.
*
*  Date: 05/12/2013
*  http://www.zend.com/en/yellow-pages/ZEND022656
*/ 

(function($){

    var settings = {};

    var methods = {
        
        _init: function( options ) 
        {     
            if( options )
            {
                $.extend(settings, options);
            }
            else
            {
                settings = {
                    callback: null
                };
            }                
            
            this.each(function()
            {  
                if( !$(this).is(':checkbox') )
                {
                    return;
                }   
                
                var self = this;
                
                // Settings
                // *************************************************************
                     
                if( !$(this).next().hasClass('checkbox-element') ) // it wasn't built yet
                {
                    if( $(this).parent().is('label') )
                    {
                        $.error('Checkboxes cannot have a parent "label" tag!');
                    }
                    
                    var checked  = ($(this).is(':checked') ? ' checkbox-element-on' : ' checkbox-element-off')
                    var disabled = ($(this).is(':disabled') ? ' checkbox-element-disabled' : '');
                    
                    $(this).after('<p class="checkbox-element' + checked + disabled + '" id="jQueryCheckbox' + $(this).attr('id') + '"></p>'); 

                    $(this).hide();
                }
                
                // Click
                // ************************************************************* 

                $(this).data('callback', settings.callback);
				
                $('#jQueryCheckbox' + $(this).attr('id')).click(function()
                {     
                    if( $(this).prev().is(':disabled') )
                    {
                        return;
                    }
                    
                    if( $(this).hasClass('checkbox-element-off') ) // turn on
                    {
                        $(this).prev().attr('checked', 'checked');
                        $(this).removeClass('checkbox-element-off');
                        $(this).addClass('checkbox-element-on');                        
                    }
                    else // turn off
                    {
                        $(this).prev().removeAttr('checked');
                        $(this).removeClass('checkbox-element-on');
                        $(this).addClass('checkbox-element-off');                         
                    }
                    
                    if( typeof $(self).data('callback') == 'function' )
                    {
                        $(self).data('callback').apply(self);
                    }                    
                }); 
                 
                // Change
                // *************************************************************                
                
                $(this).change(function()
                {
                    if( $(this).next().hasClass('checkbox-element-off') ) // turn on
                    {
                        $(this).next().removeClass('checkbox-element-off');
                        $(this).next().addClass('checkbox-element-on');
                    }
                    else // turn off
                    {
                        $(this).next().removeClass('checkbox-element-on');
                        $(this).next().addClass('checkbox-element-off'); 
                    }
                    
                    if( $(this).is(':disabled') )
                    {
                        $(this).next().addClass('checkbox-element-disabled');
                    }
                    else
                    {
                        $(this).next().removeClass('checkbox-element-disabled');
                    }
                    
                    if( typeof $(self).data('callback') == 'function' )
                    {
                        $(self).data('callback').apply(self);
                    }  
                }); 
                
                // Label click
                // *************************************************************  

                var id = $(this).attr('id');

                $('label[for="' + id + '"]').each(function()
                {
                    $(this).unbind('click');
                    $(this).click(function()
                    {
                        if( $('#' + id).is(':disabled') )
                        {
                            return;
                        }

                        if( $('#' + id).is(':checked') ) // turn off
                        {                                
                            $('#' + id).removeAttr('checked');
                            $('#' + id).next().removeClass('checkbox-element-on');
                            $('#' + id).next().addClass('checkbox-element-off'); 
                        }
                        else
                        {
                            $('#' + id).attr('checked', 'checked');
                            $('#' + id).next().removeClass('checkbox-element-off');
                            $('#' + id).next().addClass('checkbox-element-on');                                
                        }
                        
                        if( typeof $(self).data('callback') == 'function' )
                        {
                            $(self).data('callback').apply(self);
                        }
                    });

                    $(this).removeAttr('for');
                });                
            });          
        },       
        
        on: function()
        {
            this.each(function()
            {
                if( !$(this).is(':checkbox') )
                {
                    return;
                }                 
                
                $(this).attr('checked', 'checked');
                $(this).next().removeClass('checkbox-element-off');
                $(this).next().addClass('checkbox-element-on');  
            });
        }, 

        off: function()
        {
            this.each(function()
            {
                if( !$(this).is(':checkbox') )
                {
                    return;
                } 
                
                $(this).removeAttr('checked');
                $(this).next().removeClass('checkbox-element-on');
                $(this).next().addClass('checkbox-element-off'); 
            });
        }, 
        
        enable: function()
        {
            this.each(function()
            {
                if( !$(this).is(':checkbox') )
                {
                    return;
                }                 
                
                $(this).removeAttr('disabled');                
                $(this).next().removeClass('checkbox-element-disabled');
            });
        },        
        
        disable: function()
        {
            this.each(function()
            {    
                if( !$(this).is(':checkbox') )
                {
                    return;
                }                 
                
                $(this).attr('disabled', 'disabled');                
                $(this).next().addClass('checkbox-element-disabled');
            });
        }       
        
    };

    $.fn.checkbox = function( method ) 
    {        
        if( typeof method == 'string' )
        {
            if( methods[method] ) 
            {
                return methods[method].call(this);
            }
            else
            {
                $.error('Method ' + method + ' doesn\'t exist in jQuery.checkbox!');
            }
        }
        else
        {
            return methods._init.apply(this, arguments);            
        }
    };

})(jQuery);