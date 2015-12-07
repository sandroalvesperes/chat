<?php
/**
 * Autoload class.
 * Includes the class file whenever a new object is instantiated
 *
 * @final
 * @author Sandro Alves Peres <sandroalvesperes@yahoo.com.br>
 * @see http://www.zend.com/en/yellow-pages/ZEND022656
 */
final class Autoload
{

    /**
     * Constructor method
     * Static access to the methods only
     *
     * @access private
     * @return void
     */
    private function __construct(){}

    /**
     * Load the helper classes
     *
     * @static
     * @param string $class
     * @access public
     * @return void
     */
    public static function loadHelpers( $class )
    {
        $filename = realpath(dirname(__FILE__) . "/../helpers/{$class}.php");

        if( file_exists($filename) )
        {
            include_once $filename;
        }
    }

    /**
     * Load the core classes
     *
     * @static
     * @param string $class
     * @access public
     * @return void
     */
    public static function loadCore( $class )
    {
        $filename = realpath(dirname(__FILE__) . "/../core/{$class}.php");

        if( file_exists($filename) )
        {
            include_once $filename;
        }
    }

}
?>