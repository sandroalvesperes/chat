<?php
/**
 * Class to the check the users access
 *
 * @author Sandro Alves Peres <sandroalvesperes@yahoo.com.br>
 * @see http://www.zend.com/en/yellow-pages/ZEND022656
 */
class UserAccess
{

    /**
     * Check whether the user session is valid
     *
     * @static
     * @access public
     * @return boolean
     */
    public static function isValid()
    {
        return (isset($_SESSION['client_user']) || isset($_SESSION['support_user']));
    }

    /**
     * Check whether the session is a client
     *
     * @static
     * @access public
     * @return boolean
     */
    public static function isClient()
    {
        return isset($_SESSION['client_user']);
    }

    /**
     * Check whether the session is a support user
     *
     * @static
     * @access public
     * @return boolean
     */
    public static function isSupport()
    {
        return isset($_SESSION['support_user']);
    }

}
?>