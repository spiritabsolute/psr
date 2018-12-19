<?php
namespace Framework\Template;

class TemplateRenderer
{
	public function render($view, $params = []): string
	{
		$templateFile = "../templates/".$view.".php";

		extract($params, EXTR_OVERWRITE);

		ob_start();
		require $templateFile;
		return ob_get_clean();
	}
}