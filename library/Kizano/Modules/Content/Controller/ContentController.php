<?php

class Content_ContentController extends Zend_Controller_Action{

	protected $_Home;
	protected $_Nav;
	protected $_view;

	public function init(){
		$this->_view = Zend_Registry::get('view');
#		$this->Auth = Zend_Registry::get('auth');
		$this->_Home = new Kizano_Modules_Default_Model_Pages;
		$this->_Nav = new Kizano_Modules_Default_Model_Navigation;
		$this->_Home->init();
		$this->_Nav->init();
		return $this;
	}

	public function viewAction(){
		$this->_Home->Content();
		$this->_view->placeholder('content')->append('<br />Welcome to the Content::contentController :)<br />');
		$this->_view->placeholder('content')->append('<span style="font-weight:bold;color:#0011CC;">ViewAction();</span><br />');
		$this->_view->pages = $this->_Home->getPages();
	}

	public function newAction(){
		$this->_Home->Content();
		$form = new Kizano_Forms($this->_Home->getPage($this->_request->getParam('id')));
		$this->_Home->handleContent($this->_request, $form);
		return $this;
	}

	public function editAction(){
		$this->_Home->Content();
		$form = new Kizano_Forms($this->_Home->getPage($this->_request->getParam('id')));
		$this->_Home->handleContent($this->_request, $form);
		return $this;
	}

	public function deleteAction(){
		$this->_Home->deletePage($this->_request->getParam('id'));
		$this->_Home->Content();
	}

	public function listAction(){
		$this->_forward('master');
	}

	public function masterAction(){
		$this->_Home->Content();
		$this->_view->placeholder('content')->append('<br />Welcome to the Content::contentController :)<br />');
		$this->_view->placeholder('content')->append('<span style="font-weight:bold;color:#0011CC;">masterAction();</span><br />');
		$this->_view->pages = $this->_Home->getPages();
	}

	public function indexAction(){
		$this->_forward('master');
	}
}

