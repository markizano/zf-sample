<?php
/**
 *	@Name: ~/library/Kizano/Forms.php
 *	@Depends: ~/library/Zend/Form.php
 *	@Description: Main Form class
 *	@Notes: Edit with Care
 *
 */

class Kizano_Forms extends Zend_Form{

	protected $_form;
	protected $_fields;
	protected $_defaults = array();
	protected $view;

	/**
	 *	Generates a new instance of this form
	 *	@param	Zend_Config		options		(Optional) Zend_Form Configuration options
	 *	@return void
	 */
	public function __construct($options = null){
		parent::__construct($options);
		if(is_array($options) && count($options)){
			$this->_defaults = Current($options);
			parent::__construct(array());
		}else
			parent::__construct($options);
		$this->view = Zend_Registry::getInstance()->get('view');
		$this->setName('Default_Form');
		$this->setAction('');
		$this->setDisableLoadDefaultDecorators(true);
		$this->clearDecorators();
		$this->_fields = (object)array();
		return $this;
	}

	/**
	 *	Adds a submit button, sets the elements and renders the form. Returns the result.
	 *	@return		String		The rendered form, complete with submit button.
	 */
	protected function _Form(){
		$this->_fields->submit = new Zend_Form_Element_Submit(
			'submit',
			array(
				'label'=>'Submit',
				'value'=>'Submit'
			)
		);
		$this->setElements((array)$this->_fields);
		$this->setElementDecorators(
			array(
				'ViewHelper',
				new Kizano_Forms_Decorator(array('tag'=>'div'))
			)
		);
		return $this->render($this->view);
	}

	/**
	 *	Renders this form for HTML viewing.
	 *	@param view		Zend_View_Interface		A view controller to incorporate into
	 *											the rendering scheme.
	 *	@return			String					The rendered form
	 */
	public function render(Zend_View_Interface $view = null){
		$attribs = null;
		foreach($this->_attribs as $key => $attrib){
			$attrib = htmlEntities($attrib, ENT_QUOTES, 'utf-8');
			$attribs .= (empty($attribs)? null: chr(32))."$key='$attrib'";
		}
		$result = "\n\t\t\t\t\t<form method='{$this->getMethod()}'$attribs>\n";
		foreach($this->_elements as $element){
			$result .= $element->render($view);
		}
		return "$result\n\t\t\t\t\t</form>\n\t\t\t\t";
	}

	/**
	 *	Generates the advertisement form.
	 *	@return		Kizano_Forms		The pre-rendered form to print.
	 */
	public function ads(){
		$this->setName('Advertise');
		$this->setAction('/ads/form');
		$this->setAttrib('id', 'frmLogin');
		$this->_form = 'login';

		$this->_fields->url = new Zend_Form_Element_Text(
			'url',
			new Zend_Config(array(
				'id'=>'url',
				'label'=>'URL:',
			), true)
		);

		$this->_fields->url = new Zend_Form_Element_Text(
			'url',
			new Zend_Config(array(
				'id'=>'url',
				'label'=>'URL:',
			), true)
		);

		return $this->_Form();
	}
}

