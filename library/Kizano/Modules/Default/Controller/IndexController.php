<?php

class IndexController extends Zend_Controller_Action{

	protected $_Home;
	protected $_Nav;
	protected $_view;

	public function init(){
		$this->_view = Zend_Registry::get('view');
		$this->_Home = new Kizano_Modules_Default_Model_Pages;
		$this->_Nav = new Kizano_Modules_Default_Model_Navigation;
		$this->_Home->initPage();
		$this->_Nav->init();
		return $this;
	}

	public function masterAction(){
		$this->_Nav->Navigation();
		$this->_Home->Content();
		$this->_view->placeholder('content')->append('Welcome to the Default::indexController!');
	}

	public function indexAction(){
		$this->_forward('master');
	}
}

