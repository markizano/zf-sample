<?php

class Ads_Models_Ad{

	# Zend_Http_Client to handle the requests for the remote data
	protected $_client;

	public function __construct(){
		$this->_client = new Zend_Http_Client();
	}

	/**
	 *	Gathers the advertisements from the remote page
	 *	@param post		Array		The post data submitted by the form.
	 *	@return			Array		The ads retrieved from the remote page.
	 */
	public function fetchAds($post){
		$this->_client->setUri($post['url']);
		$request = $this->_client->request('GET')->getBody();
		var_dump($request);die;
	}

	/**
	 *	Generates the carousel to display to the end-user.
	 *	@param ads	Array		An associative array containing the ads to render.
	 *	@return		String		HTML to print to the end-user.
	 */
	public function generateCarousel($ads){
		
	}
}

