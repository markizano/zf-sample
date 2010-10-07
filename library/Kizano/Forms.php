<?php
/**
 *	@Name: ~/includes/library/Kizano/Forms.php
 *	@Depends: ~/includes/library/Zend/Form.php
 *	@Description: Main Form class
 *	@Notes: Edit with Care
 *
 *	Skillet Cafe
 *	@CopyRight: (c) 2010 Markizano Draconus <markizano@markizano.net>
 */

class Kizano_Forms extends Zend_Form{

	protected $_form;
	protected $_fields;
	protected $_defaults = array();
	protected $view;

	/**
	 *	Generates a new instance of this formset
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

	public function thisForm(){
		$this->_fields->submit = new Zend_Form_Element_Submit('submit', array('label'=>'Submit', 'value'=>'Submit'));
		$this->setElements((array)$this->_fields);
		$this->setElementDecorators(array('ViewHelper', new Kizano_Forms_Decorator(array('tag'=>'div'))));
		return $this->render($this->view);
	}

	public function render(Zend_View_Interface $view = null){
		$attribs = null;
		foreach(Current($this->_attribs) as $key => $attrib){
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
	 *	Creates Login Elements and attaches them to this instance of a form.
	 *	@return		Zend_Forms_Login		Login Form
	 */
	public function userLogin(){
		$this->setName('frmLogin');
		$this->setAction('/Login/');
		$this->setAttrib('id', 'frmLogin');
		$this->_form = 'login';

		$this->_fields->UserName = new Zend_Form_Element_Text(
			'UserName',
			new Zend_Config(array(
				'id'=>'UserName',
				'label'=>LANG_FORMS_USERNAME,
			), true)
		);
		$this->_fields->UserName
			->addValidator('NotEmpty')
			->setErrorMessages(array(LANG_FORMS_ERROR_USERNAME, LANG_FORMS_ERROR_USERNAME_LEN));

		$this->_fields->Password = new Zend_Form_Element_Password('Password');
		$this->_fields->Password
			->setLabel(LANG_FORMS_PASSWORD)
			->addValidator('NotEmpty')
			->setErrorMessages(array(LANG_FORMS_ERROR_PASSWORD, LANG_FORMS_ERROR_PASSWORD_LEN));
		return $this->thisForm();
	}

	public function adminContent(){
		$this->setName('frmContent');
		$this->setAction("/content/admin/list");
		$this->setAttrib('id', 'frmContent');
		$this->_form = 'content';

		$this->_fields->title = new Zend_Form_Element_Text(
			'title',
			new Zend_Config(array(
				'id'=>'title',
				'label'=>'Title:',
			), true)
		);
		$this->_fields->title->addValidator(new Zend_Validate_Alpha());

		$this->_fields->content = new Zend_Form_Element_Textarea(
			'_CONTENT',
			new Zend_Config(array(
				'id'=>'_CONTENT',
				'label'=>'Content',
				'class'=>'ckeditor',
			), true)
		);

		$this->_fields->publish = new Zend_Form_Element_Checkbox(
			'publish',
			new Zend_Config(array(
				'id'=>'publish',
				'label'=>'Live?',
			), true)
		);
		$this->_fields->publish->addValidator(new Zend_Validate_Int());

		if(!empty($this->_defaults)){
			isset($this->_defaults['title']) && $this->_fields->title->setValue($this->_defaults['title']);
			isset($this->_defaults['content']) && $this->_fields->content->setValue($this->_defaults['content']);
			isset($this->_defaults['publish']) && $this->_fields->publish->setValue($this->_defaults['publish']);
			isset($this->_defaults['id']) && $this->_fields->id = new Zend_Form_Element_Hidden(
				'id',
				new Zend_Config(array(
					'id'=>'id',
					'value'=>$this->_defaults['id']
				), true)
			);
		}

		return $this->thisForm();
	}

	public function isValid($what = array()){
		$result = parent::isValid();
		switch($this->_form){
			case 'login':
				# Check UserName
				# Check Password
				# Check Match?
				# Pass Judgement?
			break; case 'content':
				#Check Title
				#Check Content
				#IsLive?
				#Store,Stabb,Cut
			break; default:
				throw new Kizano_Exception('W0ah! You tried to validate the form before generating it?!?! O_o Try calling $self->$formName() first <.<');
		}
		return $result;
	}
}

