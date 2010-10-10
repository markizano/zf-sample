<?php
/**
 *	@Name: ~/public/index.php
 *	@Depends: N/A
 *	@Description: Entry point into the application
 *	@Notes: None
 *	
 */

require_once __DIR__.'/../application/configs/constants.php';

/** Zend_Application */
require_once 'Zend/Application.php';  

/** Create Zend_Applicaton Instance  **/
$application = new Zend_Application($_SERVER['ENVIRONMENT'], DIR_APPLICATION.'/configs/application.ini');
$application->bootstrap()->run();

