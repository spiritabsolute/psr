<?php
namespace Framework\Template;

class PhpRenderer implements TemplateRenderer
{
	private $path;

	public function __construct($path)
	{
		$this->path = $path;
	}

	public function render($view, $params = []): string
	{
		$templateFile = $this->path ."/".$view.".php";

		extract($params, EXTR_OVERWRITE);

		ob_start();
		require $templateFile;
		return ob_get_clean();
	}
}