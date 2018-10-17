<?php
namespace Framework\Container;

class Container
{
	private $defintions = [];

	public function set($id, $value): void
	{
		$this->defintions[$id] = $value;
	}

	public function get($id)
	{
		if (!array_key_exists($id, $this->defintions))
		{
			throw new ServiceNotFoundException("Undefined parameter ".$id);
		}

		return $this->defintions[$id];
	}
}