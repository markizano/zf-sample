<?php

class AdminController extends Zend_Controller_Action{

	protected function _getForm($options = array()){
		return new Forms_Form_ContentForm($options);
	}

	public function init(){
		if(ADMIN_REQUEST){
			$this->_Home = new Kizano_Modules_Default_Model_Pages;
		}else{
#			die(var_dump(debug_backtrace()));
		}
	}
	
	public function indexAction(){
		return $this->_forward('home');
	}

	public function homeAction(){
		print "We made it home, admin!";
	}

	public function formAction(){
		if($this->_request->isPost()){
			# Try to validate the info submitted by the form.
		}else{
			$this->view->form = $this->_getForm();
		}
		return $this;
	}
}

