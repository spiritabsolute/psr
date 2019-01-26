<?php
/**
 * @var \Framework\Template\PhpRenderer $this
 * @var string $content
 */

$this->extend("layout/default");
?>

<?php $this->beginBlock("content"); ?>
<div class="row">
	<div class="col-md-8">
		<?= $this->renderBlock("main"); ?>
	</div>
	<div class="col-md-4">
		<?= $this->renderBlock("sidebar"); ?>
	</div>
</div>
<?php $this->endBlock(); ?>