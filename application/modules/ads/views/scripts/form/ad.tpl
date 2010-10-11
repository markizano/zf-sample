<?php
$this->headTitle()->set('Sample Zend Project - Advertisement Signup');
$this->placeholder('layout-content')->captureStart();
?>
	<p>
		Provided below are some links for you to select. Please choose the one that
		is on your site:
	</p>
<?php
print $this->form;
$this->placeholder('layout-content')->captureEnd();


