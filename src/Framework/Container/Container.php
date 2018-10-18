<?php
namespace Framework\Container;

class Container
{
	private $defintions = [];
	private $results = [];

	public function set($id, $value): void
	{
		if (array_key_exists($id, $this->results))
		{
			unset($this->results[$id]);
		}

		$this->defintions[$id] = $value;
	}

	public function get($id)
	{
		if (array_key_exists($id, $this->results))
		{
			return $this->results[$id];
		}

		if (!array_key_exists($id, $this->defintions))
		{
			throw new ServiceNotFoundException("Undefined parameter ".$id);
		}

		$defintions = $this->defintions[$id];

		if ($defintions instanceof \Closure)
		{
			$this->results[$id] = $defintions($this);
		}
		else
		{
			$this->results[$id] = $defintions;
		}

		return $this->results[$id];
	}
}