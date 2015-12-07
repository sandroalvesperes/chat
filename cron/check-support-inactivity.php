<?php
/*
 *  Copyright (c) 2015, Sandro Alves Peres
 *  All rights reserved.
 *
 *  Date: 11/16/2015
 *  http://www.zend.com/en/yellow-pages/ZEND022656
 */

error_reporting(E_ALL);
require_once realpath(dirname(__FILE__) . '/../config/Config.php');

if( extension_loaded('pdo') && extension_loaded('pdo_mysql') )
{
    require_once realpath(dirname(__FILE__) . '/../orm/NotORM.php');
    require_once realpath(dirname(__FILE__) . '/../orm/ORM.php');

    $orm       = new ORM();
    $timeLimit = $orm->param[ array('name' => 'SET_OFFLINE_IN') ]['value'];

    $supportUser = $orm->support_user();
    $supportUser->where('online', 1);
    $supportUser->and('TIMESTAMPDIFF(MINUTE, last_activity, NOW()) > ?', $timeLimit);
    $supportUser->update(array(
        'typing' => 0,
        'online' => 0
    ));
}
else
{
    $connection = mysql_connect(Config::DB_HOST . ':' . Config::DB_PORT, Config::DB_USER, Config::DB_PASSWORD);

    if( !$connection )
    {
        die("Can't connect to the database");
    }

    mysql_query('SET NAMES UTF8 COLLATE utf8_general_ci', $connection);
    mysql_select_db(Config::DB_NAME);

    $sql = "
        SELECT value
        FROM " . Config::DB_PREFIX . "param
        WHERE name = 'SET_OFFLINE_IN'
    ";

    $query     = mysql_query($sql, $connection);
    $timeLimit = mysql_result($query, 0, 'value');

    $sql = "
        UPDATE " . Config::DB_PREFIX . "support_user
        SET typing = 0, online = 0
        WHERE online = 1 AND TIMESTAMPDIFF(MINUTE, last_activity, NOW()) > {$timeLimit}
    ";

    mysql_query($sql, $connection);
    mysql_close($connection);
}
?>