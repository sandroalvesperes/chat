<?php
/**
 * Class used to render the views
 * Singleton Pattern
 *
 * @property array $css - Stores the css files injected in the page
 * @property array $js - Stores the js files injected in the page
 * @author Sandro Alves Peres <sandroalvesperes@yahoo.com.br>
 * @see http://www.zend.com/en/yellow-pages/ZEND022656
 */
class View
{

    /**
     * @var string | null
     * @access private
     */
    private $layout;

    /**
     * @var array
     * @access private
     */
    private $params;

    /**
     * @var ViewWidget
     * @access private
     */
    private $widget;

    /**
     * @var View
     * @static
     * @access private
     */
    private static $instance;

    /**
     * Constructor method
     *
     * @access private
     * @return void
     */
    private function __construct()
    {
        $this->params = array();
        $this->widget = new ViewWidget();
    }

    /**
     * Magic method __set
     *
     * @param $name
     * @param $value
     * @access public
     * @return void
     */
    public function __set( $name, $value )
    {
        if( $name == 'js' || $name == 'css' )
        {
            if( !isset($this->params[ $name ]) )
            {
                $this->params[ $name ] = array();
            }

            $this->params[ $name ][] = URL::baseUrl() . "/public/{$name}/{$value}";
            $this->params[ $name ]   = array_unique($this->params[ $name ]);
        }
        else
        {
            $this->params[ $name ] = $value;
        }
    }

    /**
     * Magic method __get
     *
     * @param $name
     * @access public
     * @return mixed
     */
    public function __get( $name )
    {
        if( !isset($this->params[ $name ]) )
        {
            return null;
        }

        return $this->params[ $name ];
    }

    /**
     * Method to retrive the View instance.
     * Singleton Pattern
     *
     * @static
     * @access public
     * @return View
     */
    public static function instance()
    {
        if( self::$instance )
        {
            return self::$instance;
        }

        self::$instance = new self;

        return self::$instance;
    }

    /**
     * Renderize a view
     * <b>Note:</b> if the layout is set inside a view it will
     * overrive the layout set as parameter
     *
     * @param string $view
     * @param string $layout = null
     * @param boolean $return = false
     * @access public
     * @return void | string
     */
    public function render( $view, $layout = null, $return = false )
    {
        $view = preg_replace('/\.php$/i', '', $view);

        if( $layout )
        {
            $this->layout = $layout;
        }

        ob_start();

        require_once "views/{$view}.php";

        $body = ob_get_contents();
        ob_clean();

        if( $this->layout )
        {
            if( file_exists("views/layout/{$this->layout}.php") )
            {
                $js      = '';
                $css     = '';
                $widgets = array();

                if( $this->js )
                {
                    $js = array_map(create_function('$path', 'return "<script src=\"{$path}\" type=\"text/javascript\"></script>";'), $this->js);
                    $js = implode(PHP_EOL, $js);
                }

                if( $this->css )
                {
                    $css = array_map(create_function('$path', 'return "<link href=\"{$path}\" rel=\"stylesheet\" type=\"text/css\" />";'), $this->css);
                    $css = implode(PHP_EOL, $css);
                }

                if( count($this->widget->getWidgets()) > 0 )
                {
                    $widgets = array();

                    foreach( $this->widget->getWidgets() as $widget )
                    {
                        foreach( $widget['files'] as $file )
                        {
                            if( preg_match('/\.js$/i', $file) )
                            {
                                $widgets[] = '<script src="' . URL::baseUrl() . $file . '" type="text/javascript"></script>';
                            }
                            elseif( preg_match('/\.css$/i', $file) )
                            {
                                $widgets[] = '<link href="' . URL::baseUrl() . $file . '" rel="stylesheet" type="text/css" />';
                            }
                        }
                    }

                    $widgets = implode(PHP_EOL, $widgets);
                }
                else
                {
                    $widgets = '';
                }

                require_once "views/layout/{$this->layout}.php";

                $body = ob_get_contents();
                ob_clean();
            }
        }

        if( $return )
        {
            ob_end_clean();
            return $body;
        }
        else
        {
            print $body;
            ob_end_flush();
        }
    }

}
?>