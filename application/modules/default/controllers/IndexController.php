<?php

class IndexController extends Zend_Controller_Action{
	public function init(){
		var_dump($this->_request);die;
	}

	public function indexAction(){
		print "Default_IndexController::indexAction()";
	}
}

