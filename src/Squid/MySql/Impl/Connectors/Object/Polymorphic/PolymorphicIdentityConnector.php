<?php
namespace Squid\MySql\Impl\Connectors\Object\Polymorphic;


use Squid\MySql\Connectors\Object\Generic\IGenericObjectConnector;
use Squid\MySql\Connectors\Object\Polymorphic\IPolymorphicIdentityConnector;

use Squid\MySql\Impl\Connectors\Object\Identity\TPrimaryKeys;
use Squid\MySql\Impl\Connectors\Object\Identity\TIdentityDecorator;


class PolymorphicIdentityConnector extends PolymorphicConnector implements IPolymorphicIdentityConnector
{
	use TPrimaryKeys;
	use TIdentityDecorator;
	
	
	protected function getGenericObjectConnector(): IGenericObjectConnector 
	{
		return $this;
	}
	
	
	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function delete($object)
	{
		if (!is_array($object))
			$object = [$object];
		
		$iterator = $this->getConfig()->objectsIterator($object);
		$count = 0;
		
		foreach ($iterator as $group => $objects)
		{
			$connector = $this->getConfig()->getConnector($group);
			
			if ($this->getKeysCount() == 1)
			{
				$ids = [];
				$keyProperty = $this->getPrimaryProperties()[0];
				$keyField = $this->getPrimaryFields()[0];
				
				foreach ($object as $item)
				{
					$ids[] = $item->$keyProperty;
				}
				
				$res = $connector->deleteByField($keyField, $ids);
				
				if ($res === false)
					return false;
				
				$count += $res; 
			}
			else
			{
				foreach ($object as $item)
				{
					$by = [];
			
					foreach ($this->getPrimaryKeys() as $field => $property)
					{
						$by[$field] = $item->$property;
					}
					
					$res = $connector->deleteByFields($by);
					
					if ($res === false)
						return false;
					
					$count += $res; 
				}
			}
		}
		
		return $count;
	}
}