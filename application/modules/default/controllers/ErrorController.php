<?php

class ErrorController extends Zend_Controller_Action{
	public function init(){
	
	}

	public function indexAction(){
		print "Default_ErrorController::indexAction()";
	}

	public function errorAction(){
		print "Default_ErrorController::errorAction()";
		var_dump($this);
	}
}

