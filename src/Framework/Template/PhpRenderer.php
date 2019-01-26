<?php
namespace Framework\Template;

class PhpRenderer implements TemplateRenderer
{
	private $path;
	private $extend;
	private $params = [];
	private $blocks = [];

	public function __construct($path)
	{
		$this->path = $path;
	}

	public function render($view, $params = []): string
	{
		$templateFile = $this->path ."/".$view.".php";
		extract($params, EXTR_OVERWRITE);
		ob_start();
		$this->extend = null;
		require $templateFile;
		$content = ob_get_clean();

		if ($this->extend === null)
		{
			return $content;
		}

		return $this->render($this->extend, [
			"content" => $content
		]);
	}

	public function renderBlock($name)
	{
		return $this->blocks[$name] ?? "";
	}

	public function extend($view): void
	{
		$this->extend = $view;
	}

	public function beginBlock()
	{
		ob_start();
	}

	public function endBlock($name)
	{
		$this->blocks[$name] = ob_get_clean();
	}
}