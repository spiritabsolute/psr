<?php
namespace Infrastructure\Framework\Http\Middleware\ErrorHandler;

class Utils
{
	public static function getStatusCode(\Throwable $exception): int
	{
		$code = $exception->getCode();
		if ($code >= 400 && $code < 600)
		{
			return $code;
		}
		return 500;
	}
}