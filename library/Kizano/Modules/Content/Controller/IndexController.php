<?php
/**
 *	@Name: ~/includes/library/Kizano/Controllers/IndexController.php
 *	@Date: 2010-07-25
 *	@Depends: ~/includes/library/Zend/Controller/Action.php
 *	@Description: The Content-IndexController. The content module's Controller for the Index cluster of actions.
 *	@Notes: You cannot nest $this->_forward()!
 *	
 *	The Skillet Cafe
 *	@CopyRight: (c) 2010 Markizano Draconus <markizano@markizano.net>
 */

class Content_IndexController extends Zend_Controller_Action{

	protected $_Home;
	protected $_Nav;
	protected $_view;

	public function init(){
		$this->_view = Zend_Registry::get('view');
#		$this->Auth = Zend_Registry::get('auth');
		$this->_Home = new Kizano_Modules_Default_Model_Pages;
		$this->_Nav = new Kizano_Modules_Default_Model_Navigation;
		$this->_Home->initPage();
		$this->_Nav->init();
		return $this;
	}

	public function editAction(){
		$this->_Nav->Navigation();
		$this->_Home->Content();
		$form = new Kizano_Forms();
		$this->_view->placeholder('content')->set($form->adminContent());
		return $this;
	}

	public function listAction(){
		return $this->_forward('master');
	}

	public function errorAction(){
		$this->_Nav->Navigation();
		$this->_view->placeholder('content')->set('Page Not Found.');
		$this->_view->placeholder('content')->append('<br />Welcome to the Content::indexController :)<br />');
		$this->_view->placeholder('content')->append('That page does not exist. Are you sure you typed in the URL correctly?<br />');
		return $this;
	}

	public function masterAction(){
		$this->_Nav->Navigation();
		$this->_Home->Content();
		$this->_view->placeholder('content')->append('<br />Welcome to the Content::indexController :)<br />'.chr(10));
		$this->_view->placeholder('content')->append('Master Action<br />'.chr(10));
		$this->_view->page = $this->_Home->getPageBySlug($this->_view->placeholder('slug')->toString());
		return $this;
	}

	public function indexAction(){
		$error = (string)$this->_view->placeholder('error');
		if($error){
			$this->_request->setActionName('error');
			return $this->errorAction();
		}else
			$this->_request->setActionName('master');
			return $this->masterAction();
	}
}

