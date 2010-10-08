<?php

class IndexController extends Zend_Controller_Action{
	public function init(){
	
	}

	public function indexAction(){
		print "Error_IndexController::indexAction()";
	}

	public function errorAction(){
		print "Error_IndexController::errorAction()";
	}

	public function error(){
		print "Error_IndexController::error()";
	}
}

