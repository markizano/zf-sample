<?php

class Ads_FormController extends Zend_Controller_Action{
	public function init(){
		$this->view->headTitle()->set('Sample Zend Project - Advertisement Signup');
		$this->_form = new Kizano_Forms();
	}

	public function indexAction(){
		$this->view->form = $this->_form->ads();
	}
}

