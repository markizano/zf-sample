<?php

/**
 * Overrides the native ZF view helper to prepend the base URL for 
 * stylesheets to provide stylesheet paths.
 */
class Kizano_View_Helper_HeadLink extends Zend_View_Helper_HeadLink
{
    /**
     * Prefixes a given stylesheet path with a base URL for stylesheets 
     * before rendering the markup for a link element to include it.
     *
     * @param stdClass $item Object representing the stylesheet to render
     *
     * @return string Markup for the rendered link element
     */
    public function itemToString(stdClass $item)
    {

		try
		{
			//die($this->view->getRequest()->getParam('module'));
		
			if($this->_isAdminPage($this->view->getRequest()) && (Zend_Registry::isRegistered('config')))
   		    {
           	 	$config = Zend_Registry::get('config');
				$item->href = $config->filepath->style . '/' . ltrim($item->href, '/');
        	}
		} catch (Exception $e)
	    {
			//surpress exception that ends up causing a fatal error on some sites
		}		
        return parent::itemToString($item);
    }
	
    private function _isAdminPage($request)
    {
		if ($request->getParam('module') != 'public' && $request->getParam('controller') != 'public' && !$request->isXmlHttpRequest()) {
            return true;
        }
    }	
}
