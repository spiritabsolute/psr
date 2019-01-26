<?php
namespace Framework\Template;

class PhpRenderer implements TemplateRenderer
{
	private $path;
	private $extend;
	private $blocks = [];
	/**
	 * @var \SplStack
	 */
	private $blockNames;

	public function __construct($path)
	{
		$this->path = $path;
		$this->blockNames = new \SplStack();
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

		return $this->render($this->extend);
	}

	public function renderBlock($name): string
	{
		return $this->blocks[$name] ?? "";
	}

	public function extend($view): void
	{
		$this->extend = $view;
	}

	public function beginBlock($name): void
	{
		$this->blockNames->push($name);
		ob_start();
	}

	public function endBlock(): void
	{
		$name = $this->blockNames->pop();
		$this->blocks[$name] = ob_get_clean();
	}
}