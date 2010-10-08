<?php

class IndexController extends Zend_Controller_Action{
	public function init(){

	}

	public function indexAction(){
		print "Default_IndexController::indexAction()";
	}

	public function errorAction(){
		print "Default_IndexController::errorAction()";
	}

	public function error(){
		print "Default_IndexController::error()";
	}
}

