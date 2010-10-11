<?php

class ErrorController extends Zend_Controller_Action{

	/**
	 *	Initializes this error controller.
	 */
	public function init(){

	}

	/**
	 *	Handles the error action when an error is passed.
	 */
	public function errorAction(){
		$error = $this->_getParam('error_handler');
		$this->view->message = null;
		if($error instanceof Exception){
			switch ($errors->getType()){
				case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
				case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
					// 404 error -- controller or action not found
					$this->getResponse()->setHttpResponseCode(404);
					$this->view->message = 'Page not found';
					break;
				default:
					// application error
					$this->getResponse()->setHttpResponseCode(500);
					$this->view->message = 'Application error';
					break;
			}
			$this->view->trace = $error->getTraceAsString();
			if(empty($this->view->message))
				$this->view->message = $error->getMessage();
		}
		$this->view->params = $this->_request->getParams();
	}
}

