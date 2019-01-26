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

	public function extend($view): void
	{
		$this->extend = $view;
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

	public function block($name, $content)
	{
		if ($this->hasBlock($name))
		{
			return;
		}
		$this->blocks[$name] = $content;
	}

	public function ensureBlock($name): bool
	{
		if (!$this->hasBlock($name))
		{
			return false;
		}
		$this->beginBlock($name);
		return true;
	}

	public function beginBlock($name): void
	{
		$this->blockNames->push($name);
		ob_start();
	}

	public function endBlock(): void
	{
		$content = ob_get_clean();
		$name = $this->blockNames->pop();
		if ($this->hasBlock($name))
		{
			return;
		}
		$this->blocks[$name] = $content;
	}

	public function hasBlock($name): bool
	{
		return array_key_exists($name, $this->blocks);
	}

	public function renderBlock($name): string
	{
		$block = $this->blocks[$name] ?? null;
		if ($block instanceof \Closure)
		{
			return $block();
		}
		return $block ?? "";
	}

	public function encode($string)
	{
		return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE);
	}
}