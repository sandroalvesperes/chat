<?php
/**
 * This class provides headers for the application
 *
 * @author Sandro Alves Peres <sandroalvesperes@yahoo.com.br>
 * @final
 * @see http://www.zend.com/en/yellow-pages/ZEND022656
 */
final class Header
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
     * Send headers not to keep cache
     *
     * @static
     * @access private
     * @return void
     */
    private static function noCache()
    {
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
    }

    /**
     * Send "json" header
     *
     * @static
     * @access public
     * @return void
     */
    public static function json()
    {
        header('Content-Type: application/json; charset=utf-8');
        self::noCache();
    }

    /**
     * Send "html" header
     *
     * @static
     * @access public
     * @return void
     */
    public static function html()
    {
        header('Content-Type: text/html; charset=utf-8');
        self::noCache();
    }

    /**
     * Send "location" header
     * Redirects the page another url
     *
     * @static
     * @access public
     * @param string $url
     * @return void
     */
    public static function redirect( $url )
    {
        header("Location: {$url}");
        exit;
    }

}
?>