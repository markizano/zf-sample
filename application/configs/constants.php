<?php
/**
 *	@Name: ~/application/configs/constants.php
 *	@Depends: ~/public/index.php
 *	@Description: Defeines the environment to inject into the application
 *	@Notes: Edit with care
 */

# Define the configuration
defined("DS") || define('DS', DIRECTORY_SEPARATOR);

define('LIVE', preg_match('/(local|dev)/i', $_SERVER['HTTP_HOST'])? false: true, true);
define('ENV_PRODUCTION', 'production');
define('ENV_DEVELOPMENT', 'development');
define('ENV_LOCAL', 'local');
define('ENV_STAGING', 'staging');
define('ROOT_PATH', __DIR__);
define('DIR_ROOT', ROOT_PATH.DS);

define('DIR_HTML', DIR_ROOT.'html'.DS);
define('DIR_LIBRARY', LIBRARY_PATH.DS);
define('DIR_USER', APPLICATION_PATH.'user'.DS);
define('DIR_LOGS', DIR_ROOT.'logs'.DS);
define('DIR_TMP', DIR_ROOT.'cache'.DS);
define('DIR_CSS', DIR_HTML.'library'.DS.'css'.DS);
define('DIR_JS', DIR_HTML.'library'.DS.'js'.DS);
define('DIR_IMAGES', DIR_HTML.'library'.DS.'images'.DS);

define('WEB_ROOT', '/', true);
define('WEB_HTTP', "http://$_SERVER[SERVER_NAME]/");
define('WEB_HTTPS', "https://$_SERVER[SERVER_NAME]/");
define('WEB_CSS', WEB_ROOT.'assets/css/');
define('WEB_JS', WEB_ROOT.'assets/js/');
define('WEB_IMAGES', WEB_ROOT.'assets/images/');

if(LIVE){
	error_reporting(0);
	@ini_set('display_errors', false);
}else{
	error_reporting(E_ALL | E_STRICT);
	@ini_set('display_errors', true);
}

@ini_set('log_errors', true);
@ini_set('error_log', DIR_LOGS.'php.log');
@ini_set('ignore_repeated_errors', true);
@ini_set('ignore_repeated_source', true);
@ini_set('session.save_path', DIR_TMP.'session');
@ini_set('session.name', 'ZF');
@ini_set('session.use_only_cookies', 1);
@ini_set('session.cookie_lifetime', 0);
@ini_set('session.cookie_secure', 1);
@ini_set('session.cookie_httponly', 1);
@ini_set('session.cookie_path', WEB_ROOT);
@ini_set('session.hash_function', 1);
@ini_set('session.hash_bits_per_character', 5);

// Ensure library/ is on include_path
set_include_path(
	implode(
		PATH_SEPARATOR,
		array(
			DIR_APPLICATION,
			DIR_LIBRARY,
			get_include_path(),
		)
	)
);

