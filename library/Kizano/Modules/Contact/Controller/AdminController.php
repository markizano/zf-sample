<?php

class Contact_AdminController extends Zend_Controller_Action{

	protected $_Home;
	protected $_Nav;
	protected $_view;

	public function init(){
		$this->_Home = new Kizano_Modules_Default_Model_Pages;
		$this->_Nav = new Kizano_Modules_Default_Model_Navigation;
		$this->_Nav->init();
		$this->_view = Zend_Registry::get('view');
	}

	public function aboutAction(){
		
	}

	public function masterAction(){
		$this->_Home->initPage($this->_request->getParam('action'));
		$this->_Nav->Navigation();
		$this->_Home->Content();
		$this->_view->placeholder('content')->append('<br />Welcome to the content controller :)<br />');
	}

	public function indexAction(){
		$this->_forward('master');
	}
}

