<?php

class ErrorController extends Zend_Controller_Action{
	public function init(){
		$this->_reg = Zend_Registry::getInstance();
	}
	
	public function errorAction(){
        $errors = $this->_getParam('error_handler');
        switch ($errors->type) { 
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                $this->getResponse()->setHttpResponseCode(404);
                $this->_reg->set('message', 'Page not found');
                break;
            default:
                $this->getResponse()->setHttpResponseCode(500);
                $this->_reg->set('message', 'Application error');
                break;
        }
        $this->_reg->set('error', $errors);
        $this->_reg->set('params', $this->_request->getParams());
	}
}

