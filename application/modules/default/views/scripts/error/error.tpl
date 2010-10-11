<?php $this->placeholder('layout-content')->captureStart(); ?>
		<h1>An error occurred</h1>
		<h2><?= $this->message ?></h2>
<?php if(!LIVE){ ?>
		<h3>Exception information:</h3>
		<p>
			<b>Message:</b> <?php print $this->message ?>
		</p>
		<h3>Stack trace:</h3>
		<pre><?php print $this->trace ?></pre>
		<h3>Request Parameters:</h3>
		<pre><?php var_dump($this->params) ?></pre>
<?php
	}
$this->placeholder('layout-content')->captureEnd();

