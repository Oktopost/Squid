<?php
namespace Squid\MySql\Impl\Connectors\Object\Query\Selectors\Decorator;


/**
 * @mixin IObjectLoader
 */
trait TObjectLoader 
{
	public function loadAll(array $objects)
	{
		foreach ($objects as $object)
		{
			$this->loadOne($object);
		}
	}
}