<?php
/**
 * @var \Framework\Template\PhpRenderer $this
 * @var \App\ReadModel\Views\PostView $post
 */

$this->extend("layout/default");
?>

<?php $this->beginBlock("title"); ?>
	Psr framework - <?= $this->encode($post->title); ?>
<?php $this->endBlock(); ?>

<?php $this->beginBlock("meta"); ?>
	<meta name="description" content="<?= $this->encode($post->title); ?>">
<?php $this->endBlock(); ?>

<?php $this->beginBlock("navbar"); ?>
	<div class="inner">
		<h3 class="masthead-brand">Psr framework</h3>
		<nav class="nav nav-masthead">
			<a class="nav-link" href="<?=$this->generatePath("home")?>">Home</a>
			<a class="nav-link active" href="<?=$this->generatePath("blog")?>">Blog</a>
			<a class="nav-link" href="<?=$this->generatePath("about")?>">About</a>
			<a class="nav-link" href="<?=$this->generatePath("cabinet")?>">Cabinet</a>
		</nav>
	</div>
<?php $this->endBlock(); ?>

<?php $this->beginBlock("content"); ?>
<h3><?= $this->encode($post->title); ?></h3>
<div class="panel panel-default">
	<div class="panel-heading">
		<?= $post->date->format("Y-m-d"); ?>
	</div>
	<div class="panel-body">
		<?=nl2br($this->encode($post->content))?>
	</div>
</div>
<?php $this->endBlock(); ?>