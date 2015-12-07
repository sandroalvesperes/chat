<?php
/**
 * This class maps the request
 *
 * @author Sandro Alves Peres <sandroalvesperes@yahoo.com.br>
 * @see http://www.zend.com/en/yellow-pages/ZEND022656
 */
class Request
{

    /**
     * Method to retrieve information from $_REQUEST variable
     *
     * @access public
     * @param string $name
     * @return string
     */
    public function param( $name )
    {
        return $_REQUEST[ $name ];
    }

    /**
     * Method to retrieve a cookie
     *
     * @access public
     * @param string $name
     * @return mixed
     */
    public function cookie( $name )
    {
        return $_COOKIE[ $name ];
    }

    /**
     * Method to retrieve information from $_GET variable
     *
     * @access public
     * @param string $name
     * @param int $filter = FILTER_DEFAULT - (filter_input() filters)
     * @return string
     */
    public function get( $name, $filter = FILTER_DEFAULT )
    {
        return filter_input(INPUT_GET, $name, $filter);
    }

    /**
     * Method to retrieve information from $_POST variable
     *
     * @access public
     * @param string $name
     * @param int $filter = FILTER_DEFAULT - (filter_input() filters)
     * @return string
     */
    public function post( $name, $filter = FILTER_DEFAULT )
    {
        return filter_input(INPUT_POST, $name, $filter);
    }

    /**
     * Method to retrieve the body input (php://input)
     *
     * @access public
     * @return string
     */
    public function body()
    {
        return file_get_contents('php://input');
    }

    /**
     * Method to check whether the request was done through ajax or not
     *
     * @access public
     * @return boolean
     */
    public function isAjax()
    {
        return @(strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }

}
?>