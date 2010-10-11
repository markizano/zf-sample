<?php

$library_path = '/../../library';
define('GLOBAL_LIBRARY_PATH',   $library_path);
define('ZEND_LIBRARY_PATH',     realpath(dirname(__FILE__) . GLOBAL_LIBRARY_PATH . '/'));
define('CUSTOM_LIBRARY_PATH',     realpath(dirname(__FILE__) . GLOBAL_LIBRARY_PATH . '/Custom'));
define('DOCTRINE_LIBRARY_PATH', realpath(dirname(__FILE__) . GLOBAL_LIBRARY_PATH .'/'));

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../application'));

define('APPLICATION_ENV','development');

defined("DS")
	|| define('DS', ( (substr(strtoupper(PHP_OS), 0, 3) == "WIN") ? "\\" : "/"));

set_include_path(	GLOBAL_LIBRARY_PATH . PATH_SEPARATOR . 
					ZEND_LIBRARY_PATH . PATH_SEPARATOR .
					CUSTOM_LIBRARY_PATH . PATH_SEPARATOR . 
					DOCTRINE_LIBRARY_PATH . PATH_SEPARATOR . 
					get_include_path());

//take advantage of the zend autoloader

require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true);

$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini');
$db = $config->{APPLICATION_ENV}->database;
$dsn = $db->type . '://' . $db->username . ':' . $db->password . '@' . $db->host . '/' . $db->dbname;

try{
	$manager = Doctrine_Manager::connection($dsn);
	$manager->setAttribute(Doctrine::ATTR_USE_DQL_CALLBACKS, true);
} catch( Exception $e){
	echo $e;
}

// Configure Doctrine Cli
// Normally these are arguments to the cli tasks but if they are set here the arguments will be auto-filled
$config = array('data_fixtures_path'  =>  dirname(__FILE__) . DIRECTORY_SEPARATOR . '/fixtures',
                'models_path'         =>  dirname(__FILE__) . DIRECTORY_SEPARATOR . '/models',
                'migrations_path'     =>  dirname(__FILE__) . DIRECTORY_SEPARATOR . '/migrations',
                'sql_path'            =>  dirname(__FILE__) . DIRECTORY_SEPARATOR . '/sql',
                'yaml_schema_path'    =>  dirname(__FILE__) . DIRECTORY_SEPARATOR . '/schema');

$cli = new Doctrine_Cli($config);
$cli->run($_SERVER['argv']);
