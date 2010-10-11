<?php print $this->doctype(); ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<!-- to correct the unsightly Flash of Unstyled Content. http://www.bluerobot.com/web/css/fouc.asp -->
		<script type="text/javascript"> </script>
<?php
	$this->headLink()->appendStylesheet('default.css');
	$this->headScript()->prependFile('default.js');
	print $this->headTitle().chr(10).$this->headLink().chr(10).$this->headScript().chr(10);
?>
	</head>
	<body>
		<div id="PAGE">
<?php
	print $this->render('header.tpl').
		$this->flash.
		$this->placeholder('layout-content').
		$this->render('footer.tpl');
?>
		</div>
	</body>
</html>

