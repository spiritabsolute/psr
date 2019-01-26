<?php
/**
 * @var \Framework\Template\PhpRenderer $this
 */

$this->params["title"] = "Psr framework - about";
$this->extend("layout/columns");
?>

<?php $this->beginBlock(); ?>
<div class="inner">
	<h3 class="masthead-brand">Psr framework</h3>
	<nav class="nav nav-masthead">
		<a class="nav-link" href="/">Home</a>
		<a class="nav-link active" href="/about">About</a>
		<a class="nav-link" href="/cabinet">Cabinet</a>
	</nav>
</div>
<?php $this->endBlock("navbar"); ?>

<?php $this->beginBlock(); ?>
	<div class="panel panel-default">
		<div class="panel-heading">Links</div>
		<div class="panel-body">About navigation</div>
	</div>
<?php $this->endBlock("sidebar"); ?>

<div class="inner cover">
	<h1 class="cover-heading">I am a simple site</h1>
</div>