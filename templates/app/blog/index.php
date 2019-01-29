<?php
/**
 * @var \Framework\Template\PhpRenderer $this
 * @var \App\ReadModel\PostReadRepository $posts
 */

$this->extend("layout/default");
?>

<?php $this->beginBlock("title"); ?>
	Psr framework - blog
<?php $this->endBlock(); ?>

<?php $this->beginBlock("meta"); ?>
	<meta name="description" content="Blog page description">
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
<h3>Blog</h3>
<?php foreach ($posts as $post): ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<span class="pull-right"><?= $post->date->format("Y-m-d"); ?></span>
			<a href="<?= $this->encode($this->generatePath("blog_show", ["id" => $post->id])); ?>">
				<?= $this->encode($post->title); ?>
			</a>
		</div>
		<div class="panel-body">
			<?=nl2br($this->encode($post->content))?>
		</div>
	</div>
<?php endforeach; ?>
<?php $this->endBlock(); ?>