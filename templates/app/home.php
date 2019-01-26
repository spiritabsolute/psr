<?php
/**
 * @var \Framework\Template\PhpRenderer $this
 */

$this->params["title"] = "Psr framework - home";
$this->extend("layout/default");
?>

<?php $this->beginBlock(); ?>
<div class="inner">
	<h3 class="masthead-brand">Psr framework</h3>
	<nav class="nav nav-masthead">
		<a class="nav-link active" href="/">Home</a>
		<a class="nav-link" href="/about">About</a>
		<a class="nav-link" href="/cabinet">Cabinet</a>
	</nav>
</div>
<?php $this->endBlock("navbar"); ?>


<div class="inner cover">
	<h1 class="cover-heading">Hello, Guest!</h1>
</div>