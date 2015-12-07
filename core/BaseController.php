<?php
/**
 * All controllers must extend this class
 *
 * @author Sandro Alves Peres <sandroalvesperes@yahoo.com.br>
 * @abstract
 * @see http://www.zend.com/en/yellow-pages/ZEND022656
 */
abstract class BaseController
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
    private $action;

    /**
     * @var ORM
     * @access protected
     */
    protected $orm;

    /**
     * @var Request
     * @access protected
     */
    protected $request;

    /**
     * Constructor method
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->orm     = new ORM();
        $this->request = new Request();
    }

    /**
     * This method is executed before dispatching the action
     *
     * @access public
     * @return void
     */
    public function before(){}

    /**
     * This method is executed after dispatching the action
     *
     * @access public
     * @return void
     */
    public function after(){}

    /**
     * Sets the controller
     *
     * @access public
     * @param string $controller
     * @return void
     */
    public function setController( $controller )
    {
        $this->controller = $controller;
    }

    /**
     * Sets the action
     *
     * @access public
     * @param string $action
     * @return void
     */
    public function setAction( $action )
    {
        $this->action = $action;
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

}
?>