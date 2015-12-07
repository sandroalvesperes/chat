<?php
/**
 * Class used to add the widgets (js plugins) to the page.
 * It appends the "js" and "css" files to the head tag
 *
 * @author Sandro Alves Peres <sandroalvesperes@yahoo.com.br>
 * @see http://www.zend.com/en/yellow-pages/ZEND022656
 */
class ViewWidget
{

    /**
     * @var array
     * @access private
     */
    private $widgets;

    /**
     * Constructor method
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->widgets = array
        (
            'ui' => array(
                'enable' => false,
                'files'  => array(
                    '/public/js/jquery-ui/css/chat/jquery-ui.css',
                    '/public/js/jquery-ui/js/jquery-ui.js'
                )
            ),
            'tipsy' => array(
                'enable' => false,
                'files'  => array(
                    '/public/js/tipsy/stylesheets/jquery.tipsy.css',
                    '/public/js/tipsy/javascripts/jquery.tipsy.js'
                )
            ),
            'radio' => array(
                'enable' => false,
                'files'  => array(
                    '/public/js/jquery-radio/jquery.radio.css',
                    '/public/js/jquery-radio/jquery.radio.js'
                )
            ),
            'checkbox' => array(
                'enable' => false,
                'files'  => array(
                    '/public/js/jquery-checkbox/jquery.checkbox.css',
                    '/public/js/jquery-checkbox/jquery.checkbox.js'
                )
            ),
            'switch' => array(
                'enable' => false,
                'files'  => array(
                    '/public/js/jquery-switch-style/jquery.switch.style.css',
                    '/public/js/jquery-switch-style/jquery.switch.style.js'
                )
            ),
            'toast' => array(
                'enable' => false,
                'files'  => array(
                    '/public/js/jquery-toast-message/resources/css/jquery.toastmessage.css',
                    '/public/js/jquery-toast-message/javascript/jquery.toastmessage.js'
                )
            )
        );
    }

    /**
     * Enables the widget
     *
     * @param string $widget
     * @access public
     * @return void
     */
    public function enable( $widget )
    {
        if( !array_key_exists($widget, $this->widgets) )
        {
            throw new OutOfBoundsException("Widget \"{$widget}\" doesn't exist");
        }

        $this->widgets[ $widget ]['enable'] = true;
    }

    /**
     * Disables the widget
     *
     * @param string $widget
     * @access public
     * @return void
     */
    public function disable( $widget )
    {
        if( !array_key_exists($widget, $this->widgets) )
        {
            throw new OutOfBoundsException("Widget \"{$widget}\" doesn't exist");
        }

        $this->widgets[ $widget ]['enable'] = false;
    }

    /**
     * Return all the enabled widgets
     *
     * @access public
     * @return void
     */
    public function getWidgets()
    {
        return array_filter($this->widgets, create_function('$item', 'return $item["enable"];'));
    }

}
?>