<?php
require_once dirname(__FILE__) . '/NotORM.php';

/**
 * ORM class
 *
 * @final
 * @author Sandro Alves Peres <sandroalvesperes@yahoo.com.br>
 * @see http://www.zend.com/en/yellow-pages/ZEND022656
 */
final class ORM extends NotORM
{

    /**
     * @var PDO
     * @access public
     */
    public $pdo;

    /**
     * Constructor method
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        $dsn  = 'mysql:host=' . Config::DB_HOST;
        $dsn .= ';dbname=' . Config::DB_NAME;
        $dsn .= ';port=' . Config::DB_PORT;

        $this->pdo = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD, array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ));

        $this->pdo->exec('SET NAMES UTF8 COLLATE utf8_general_ci');

        $structure = new NotORM_Structure_Convention('id_%s', 'id_%s', '%s', Config::DB_PREFIX);

        parent::__construct($this->pdo, $structure);
    }

}
?>