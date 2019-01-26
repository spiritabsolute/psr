<?php
/**
 * @var \Framework\Template\PhpRenderer $this
 * @var string $username
 */

$this->extend("layout/columns");
?>

<?php $this->beginBlock("title"); ?>
Psr framework - cabinet
<?php $this->endBlock(); ?>

<?php $this->beginBlock("navbar"); ?>
<div class="inner">
	<h3 class="masthead-brand">Psr framework</h3>
	<nav class="nav nav-masthead">
		<a class="nav-link" href="/">Home</a>
		<a class="nav-link" href="/about">About</a>
		<a class="nav-link active" href="/cabinet">Cabinet</a>
	</nav>
</div>
<?php $this->endBlock(); ?>

<?php $this->beginBlock("main"); ?>
<h3 class="cover-heading">Cabinet of <?=htmlspecialchars($username)?></h3>
<?php $this->endBlock(); ?>

<?php $this->beginBlock("sidebar"); ?>
<div class="panel panel-default">
	<div class="panel-heading">Links</div>
	<div class="panel-body">Cabinet</div>
	<div class="panel-body">Settings</div>
</div>
<?php $this->endBlock(); ?>