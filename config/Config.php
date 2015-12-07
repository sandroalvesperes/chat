<?php
/**
 * Configuration class
 *
 * @final
 * @author Sandro Alves Peres <sandroalvesperes@yahoo.com.br>
 * @see http://www.zend.com/en/yellow-pages/ZEND022656
 */
final class Config
{

    /**
     * Error reporting
     */
    const ERROR_REPORTING = E_ALL;

    /**
     * Chat's base URL
     * If null, the application will try to find out the base URL
     * Values e.g.: "http://www.mysite.com/chat" or "http://www.mychaturl.com"
     */
    const CHAT_BASE_URL = null;

    /**
     * Database settings
     */
    const DB_HOST     = 'localhost';
    const DB_NAME     = 'chat';
    const DB_USER     = 'root';
    const DB_PASSWORD = '';
    const DB_PORT     = 3306;
    const DB_PREFIX   = '';

    /**
     * Session settings
     */
    const SESSION_NAME = 'WeSupportChat';

    /**
     * Timezone settings
     * Used with the function: date_default_timezone_set
     */
    const TIMEZONE = 'America/Sao_Paulo';

    /**
     * If "true" it uses PHP to check whether the support is online.
     * If "false" you have to manage the support's availability by
     * your own, using "cron" or "mysql event".
     *
     * It changes the users status to offline based on their last activity
     *
     * There's a PHP file in the "cron" folder and a "mysql event" in
     * the "docs" folder that you can use if this is assigned to "false"
     */
    const CHECK_SUPPORT_INACTIVITY = true;

    /**
     * Constructor method
     * Static access only
     *
     * @access private
     * @return void
     */
    private function __construct(){}

}
?>