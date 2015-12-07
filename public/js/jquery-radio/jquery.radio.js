/*
*  Copyright (c) 2013, Sandro Alves Peres
*  All rights reserved.
*
*  Date: 12/05/2013
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
                if( !$(this).is(':radio') )
                {
                    return;
                }               
                
                // Settings
                // *************************************************************
                     
                if( !$(this).next().hasClass('radio-element') ) // it wasn't built yet
                {
                    if( $(this).parent().is('label') )
                    {
                        $.error('Radio element cannot have a parent type "label"!');
                    }
                    
                    var checked  = ($(this).is(':checked') ? ' radio-element-on' : ' radio-element-off')
                    var disabled = ($(this).is(':disabled') ? ' radio-element-disabled' : '');
                    
                    $(this).after('<p class="radio-element' + checked + disabled + '" id="jQueryRadio' + $(this).attr('id') + '"></p>'); 

                    $(this).hide();
                }
                
                // Click
                // ************************************************************* 

                $(this).data('callback', settings.callback);				
				
                $('#jQueryRadio' + $(this).attr('id')).unbind('click');
                $('#jQueryRadio' + $(this).attr('id')).click(function()
                {     
                    if( $(this).prev().is(':disabled') || $(this).hasClass('radio-element-on') )
                    {
                        return;
                    }
                    
                    var name = $(this).prev().attr('name');                        

                    $('input:radio[name="' + name + '"]').each(function()
                    {
                        if( $(this).next().is('p.radio-element') )
                        {
                            $(this).next().removeClass('radio-element-on');
                            $(this).next().addClass('radio-element-off');
                        }
						
						$(this).prev().removeAttr('checked');
                    }); 

                    $(this).removeClass('radio-element-off');
                    $(this).addClass('radio-element-on');
                    $(this).prev().attr('checked', 'checked');

                    if( typeof $(this).prev().data('callback') == 'function' )
                    {	
						$(this).prev().data('callback').apply( $(this).prev() );
                    } 
                }); 
                 
                // Change
                // *************************************************************                
                
				$(this).unbind('change');
                $(this).change(function()
                {
                    var name = $(this).attr('name');                  
                
                    $('input:radio[name="' + name + '"]').each(function()
                    {
                        if( $(this).next().is('p.radio-element') )
                        {
                            $(this).next().removeClass('radio-element-on');
                            $(this).next().addClass('radio-element-off');
                        }
                    });                     
                    
                    if( $(this).next().hasClass('radio-element-off') ) // turn on
                    {
                        $(this).next().removeClass('radio-element-off');
                        $(this).next().addClass('radio-element-on');
                    }
                    else // turn off
                    {
                        $(this).next().removeClass('radio-element-on');
                        $(this).next().addClass('radio-element-off'); 
                    }
                    
                    if( $(this).is(':disabled') )
                    {
                        $(this).next().addClass('radio-element-disabled');
                    }
                    else
                    {
                        $(this).next().removeClass('radio-element-disabled');
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
                        if( $('#' + id).is(':disabled') || $('#' + id).is(':checked') )
                        {
                            return;
                        }

                        $('#' + id).attr('checked', 'checked');

                        var name = $('#' + id).attr('name');                        

                        $('input:radio[name="' + name + '"]').each(function()
                        {
                            if( $(this).next().is('p.radio-element') )
                            {
                                $(this).next().removeClass('radio-element-on');
                                $(this).next().addClass('radio-element-off');
                            }
                        }); 

                        $('#' + id).next().removeClass('radio-element-off');
                        $('#' + id).next().addClass('radio-element-on');

						if( typeof $('#' + id).data('callback') == 'function' )
						{	
							$('#' + id).data('callback').apply( $('#' + id) );
						} 						
                    });

                    $(this).removeAttr('for');
                });                
            });          
        },       
        
        on: function()
        {
            if( this.selector.indexOf('#') != 0 )
            {
                $.error('Selector must be an ID only! (#ID)')
            }
            
            if( $(this.selector).size() > 1 )
            {
                $.error('Selector must return just one element!')
            }            

            if( !this.is(':radio') || this.is(':checked') )
            {
                return;
            }             
            
            this.attr('checked', 'checked');

            var name = this.attr('name');                        

            $('input:radio[name="' + name + '"]').each(function()
            {
                if( $(this).next().is('p.radio-element') )
                {
                    $(this).next().removeClass('radio-element-on');
                    $(this).next().addClass('radio-element-off');
                }
            }); 

            this.next().removeClass('radio-element-off');
            this.next().addClass('radio-element-on');
        }, 
        
        off: function()
        {
            this.each(function()
            {
                if( !$(this).is(':radio') )
                {
                    return;
                }                
                
                var name = $(this).attr('name');
                
                $('input:radio[name="' + name + '"]').each(function()
                {
                    if( !$(this).is(':checked') )
                    {
                        return;
                    }
                    
                    if( $(this).next().is('p.radio-element') )
                    {
                        $(this).next().removeClass('radio-element-on');
                        $(this).next().addClass('radio-element-off');
                    }
                    
                    $(this).removeAttr('checked');
                });                
            });
        },         
        
        enable: function()
        {
            this.each(function()
            {
                if( !$(this).is(':radio') )
                {
                    return;
                }                 
                
                $(this).removeAttr('disabled');                
                $(this).next().removeClass('radio-element-disabled');
            });
        },        
        
        disable: function()
        {
            this.each(function()
            {    
                if( !$(this).is(':radio') )
                {
                    return;
                }                 
                
                $(this).attr('disabled', 'disabled');                
                $(this).next().addClass('radio-element-disabled');
            });
        }       
        
    };

    $.fn.radio = function( method ) 
    {        
        if( typeof method == 'string' )
        {
            if( methods[method] ) 
            {
                return methods[method].call(this);
            }
            else
            {
                $.error('Method ' + method + ' does not exist on jQuery.checkbox!');
            }
        }
        else
        {
            return methods._init.apply(this, arguments);            
        }
    };

})(jQuery);