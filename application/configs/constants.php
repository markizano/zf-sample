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
define('ROOT_PATH', dirname(dirname(__DIR__)));
define('DIR_ROOT', ROOT_PATH.DS);

define('DIR_HTML', DIR_ROOT.'html'.DS);
define('DIR_APPLICATION', DIR_ROOT.'application'.DS);
define('DIR_LIBRARY', DIR_ROOT.'library'.DS);
define('DIR_USER', DIR_APPLICATION.'user'.DS);
define('DIR_TMP', DIR_ROOT.'cache'.DS);
define('DIR_LOGS', DIR_TMP.'logs'.DS);
define('DIR_CSS', DIR_HTML.'library'.DS.'css'.DS);
define('DIR_JS', DIR_HTML.'library'.DS.'js'.DS);
define('DIR_IMAGES', DIR_HTML.'library'.DS.'images'.DS);

define('WEB_ROOT', '/', true);
define('WEB_HTTP', "http://$_SERVER[SERVER_NAME]/");
define('WEB_HTTPS', "https://$_SERVER[SERVER_NAME]/");
define('WEB_CSS', WEB_ROOT.'assets/css/');
define('WEB_JS', WEB_ROOT.'assets/js/');
define('WEB_IMAGES', WEB_ROOT.'assets/images/');

// Ensure library/ is on include_path
set_include_path(
	implode(
		PATH_SEPARATOR,
		array(
			DIR_APPLICATION.'modules',
			DIR_LIBRARY,
			get_include_path(),
		)
	)
);

