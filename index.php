<?php
/*
 *  Copyright (c) 2015, Sandro Alves Peres
 *  All rights reserved.
 *
 *  http://www.zend.com/en/yellow-pages/ZEND022656
 */ 

require_once 'config/Config.php';

error_reporting( Config::ERROR_REPORTING );

require_once 'config/Autoload.php';
require_once 'orm/ORM.php';

spl_autoload_register('Autoload::loadCore');
spl_autoload_register('Autoload::loadHelpers');

date_default_timezone_set( Config::TIMEZONE );

session_name( Config::SESSION_NAME );
session_start();

$routeMap = new RouteMap();
$routeMap->dispatch();
?>