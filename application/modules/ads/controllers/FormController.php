<?php
/**
 *	@Name: ~/application/modules/ads/controllers/FormController.php
 *	@Depends: ~/application/modules/ads/models/Ad.php, ~/library/Zend/Controller/Action.php
 *	@Description: Form controller for the Advertisements
 *	@Notes: Edit with care.
 *	
 */

require 'ads/models/Ad.php';

/**
 *	Main controller for the Advertisements
 *	@member init			Initializes the class and starts things up right
 *	@member indexAction		Handles the default module action
 *	@member	adAction		Handles the advertisement action (after the form is submitted)
 */
class Ads_FormController extends Zend_Controller_Action{

	# The local form object to print to the end-user
	protected $_form;

	/**
	 *	The local ad object to handle the fetching of the remote page and formatting
	 *	the data accordingly. The main model of this module.
	 */
	protected $_ad;

	/**
	 *	Initializes this class and assigns the dependent objects accordingly.
	 *	@return void
	 */
	public function init(){
		$this->_form = new Kizano_Forms();
		$this->_ad = new Ads_Models_Ad();
	}

	/**
	 *	Handles the default form controller action.
	 *	@return void
	 */
	public function indexAction(){
		$this->view->form = $this->_form->ads();
	}

	/**
	 *	Handles the advertisement action after the form is posted.
	 *	@return void
	 */
	public function adAction(){
		if($this->_request->isPost()){
			if($this->_form->isValid($this->_request->getParams())){
				$ads = $this->_ad->fetchAds($this->_request->getPost());
				var_dump($ads);die;
				$this->view->carousel = $this->_ad->generateCarousel($ads);
			}else{
				var_dump($this->_form);die;
			}
		}else{
			$this->_helper->redirector->gotoUrl('/ads/form/');
		}
	}
}

