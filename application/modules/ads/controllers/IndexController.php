<?php

class IndexController extends Zend_Controller_Action{
	public function init(){
		var_dump(sprintf('Ads_%s::%s', __CLASS__, __FUNCTION__));die;
	}

	public function indexAction(){
		print "Ads_IndexController::indexAction()";
	}
}

