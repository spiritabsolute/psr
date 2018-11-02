<?php
namespace Framework\Container;

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
	private $defintions = [];
	private $results = [];

	public function __construct(array $defintions = [])
	{
		$this->defintions = $defintions;
	}

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
			if (class_exists($id))
			{
				$reflection = new \ReflectionClass($id);
				$arguments = [];
				if (($constructor = $reflection->getConstructor()) !== null)
				{
					foreach ($constructor->getParameters() as $parameter)
					{
						if ($paramClass = $parameter->getClass())
						{
							$arguments[] = $this->get($paramClass->getName());
						}
						elseif ($parameter->isArray())
						{
							$arguments[] = [];
						}
						else
						{
							if (!$parameter->isDefaultValueAvailable())
							{
								throw new ServiceNotFoundException(
									"Unable to resolve ".$parameter->getName()." in service ".$id);
							}
							$arguments[] = $parameter->getDefaultValue();
						}
					}
				}
				$result = $reflection->newInstanceArgs($arguments);
				return $this->results[$id] = $result;
			}

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

	public function has($id): bool
	{
		return array_key_exists($id, $this->defintions) || class_exists($id);
	}
}