<?php

class Ads_Models_Ad{

	# Zend_Http_Client to handle the requests for the remote data
	protected $_client;

	public function __construct(){
		$this->_client = new Zend_Http_Client();
	}

	/**
	 *	Gathers the advertisements from the remote page
	 *	@param post		Array				The post data submitted by the form.
	 *	@return			Array				The ads retrieved from the remote page.
	 */
	public function fetchAds($post){
		$this->_client->setUri($post['url']);
		$response = $this->_client->request('GET')->getBody();
		/**
		 *	If the tidy class exists, attempt to cleanup the XML returned from the
		 *	response requested from the remote site.
		 */
		if(class_exists('tidy')){
			$tidy = new Tidy(
				'/dev/null',
				array(
					'indent'=>true,
					'tab-size'=>4,
					'output-encoding'=>'utf8',
					'newline'=>'LF',
					'output-xhtml'=>true,
				),
				'utf8'
			);
			$tidy->parseString($response);
			$tidy->cleanRepair();
			$response = $tidy->value;
		}
		/**
		 *	Once we've attempted to clean up the retrieved HTML, attempt to parse the
		 *	result in a DomDocument.
		 */
		$xml = new DOMDocument('1.0', 'utf-8');
		$xml->loadHTML($response);
		$result = array();
		# Foreach of the anchor links in the page,
		foreach($xml->getElementsByTagName('a') as $a){
			# Get it's target HREF
			$href = $a->getAttribute('href');
			if(preg_match("/^http:\/\/([a-z\-]+\.)?$post[ad].*$/i", $href)){
				# If a link's target points to the search query (the advertising site)
				$result[] = $href; # Append the result.
			}
		}
		return $result;
	}
}

