<?php
/**
 * Class for manipulating URLs
 *
 * @author Sandro Alves Peres <sandroalvesperes@yahoo.com.br>
 * @final
 * @see http://www.zend.com/en/yellow-pages/ZEND022656
 */
final class URL
{

    /**
     * Constructor method
     *
     * @access private
     * @return void
     */
    private function __construct(){} # static class

    /**
     * Try to find out the base URL and returns it
     *
     * @static
     * @access private
     * @return string
     */
    private static function base()
    {
        $protocol = strtolower(strtok($_SERVER['SERVER_PROTOCOL'], '/'));
        $host     = $_SERVER['HTTP_HOST'];
        $baseDir  = '/' . trim(dirname($_SERVER['PHP_SELF']), '/\\');

        return rtrim("{$protocol}://{$host}{$baseDir}", '/');
    }

    /**
     * Returns the base URL
     *
     * @static
     * @access public
     * @return string
     */
    public static function baseUrl()
    {
        if( Config::CHAT_BASE_URL )
        {
            return Config::CHAT_BASE_URL;
        }

        return self::base();
    }

}
?>