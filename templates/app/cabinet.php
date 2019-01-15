<?php
/**
 * @var \Framework\Template\PhpRenderer $this
 * @var string $username
 */

$this->params["title"] = "Psr framework - cabinet";
$this->extends = "layout/cabinet";
?>

<h1 class="cover-heading">Cabinet of <?=htmlspecialchars($username)?></h1>