<?php
/**
 * This class is used to parse the request and map it
 * to the correct "controller" and "action"
 *
 * @author Sandro Alves Peres <sandroalvesperes@yahoo.com.br>
 * @see http://www.zend.com/en/yellow-pages/ZEND022656
 */
class RouteMap
{

    /**
     * @var string
     * @access private
     */
    private $controller;
    
    /**
     * @var string
     * @access private
     */    
    private $action = 'index';
    
    /**
     * @var string
     * @access private
     */    
    private $defaultController = 'Start';

    /**
     * Constructor method
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->controller = $this->defaultController;
        $this->parse();
    }

    /**
     * Splits the request information
     *
     * @access private
     * @return void
     */
    private function parse()
    {
        $dirname = trim(dirname($_SERVER['PHP_SELF']), '/\\');;
        $url     = preg_replace("@^/?{$dirname}/?@", '', $_SERVER['REQUEST_URI']);
        $path    = parse_url($url, PHP_URL_PATH);
        $path    = trim($path, '/\\');

        if( preg_match('@^\w+(/\w+)?$@', $path) )
        {
            @list($c, $a) = explode('/', $path);

            !$c || $this->controller = ucfirst($c);
            !$a || $this->action     = $a;
        }
    }

    /**
     * Retrives the http method
     *
     * @access public
     * @return string
     */
    public function getHttpMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Gets the controller
     *
     * @access public
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Gets the action
     *
     * @access public
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Dispatch the request to the right controller and action.
     * It instantiates the controller and execute the method that
     * corresponds to the http method and action name
     *
     * @access public
     * @return void
     */
    public function dispatch()
    {
        if( file_exists("controllers/{$this->controller}.php") )
        {
            require_once "controllers/{$this->controller}.php";

            $methodExists = method_exists($this->controller, "{$this->getHttpMethod()}_{$this->action}");
            $allExists    = method_exists($this->controller, "all_{$this->action}");

            if( $methodExists || $allExists )
            {
                $method = ($methodExists ? "{$this->getHttpMethod()}_{$this->action}" : "all_{$this->action}");

                $objController = new $this->controller();
                $objController->setController( $this->controller );
                $objController->setAction( $this->action );
                $objController->before();
                $objController->$method();
                $objController->after();
            }
            else
            {
                require_once "controllers/{$this->defaultController}.php";

                $default = new $this->defaultController();
                $default->all_notFound();
            }
        }
        else
        {
            require_once "controllers/{$this->defaultController}.php";

            $default = new $this->defaultController();
            $default->all_notFound();
        }
    }

}
?>