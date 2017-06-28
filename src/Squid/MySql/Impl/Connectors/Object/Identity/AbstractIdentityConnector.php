<?php
namespace Squid\MySql\Impl\Connectors\Object\Identity;


use Squid\MySql\Connectors\Object\Generic\IGenericObjectConnector;
use Squid\MySql\Connectors\Object\IIdentityConnector;
use Squid\MySql\Impl\Connectors\Internal\Object\AbstractORMConnector;


abstract class AbstractIdentityConnector extends AbstractORMConnector implements IIdentityConnector
{
	use TPrimaryKeys;
	
	
	protected abstract function getGenericConnector(): IGenericObjectConnector;
	
	
	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function delete($object)
	{
		if (is_array($object))
		{
			if ($this->getKeysCount() == 1)
			{
				$ids = [];
				$keyProperty = $this->getPrimaryProperties()[0];
				$keyField = $this->getPrimaryFields()[0];
				
				foreach ($object as $item)
				{
					$ids[] = $item->$keyProperty;
				}
				
				return $this->getGenericConnector()->deleteByField($keyField, $ids);
			}
			else
			{
				$count = 0;
				
				foreach ($object as $item)
				{
					$res = $this->delete($item);
					
					if ($res === false)
						return false;
					
					$count += $res;
				}
				
				return $count;
			}
		}
		else
		{
			$by = [];
			
			foreach ($this->getPrimaryKeys() as $field => $property)
			{
				$by[$field] = $object->$property;
			}
			
			return $this->getGenericConnector()->deleteByFields($by);
		}
	}

	/**
	 * @param mixed $object
	 * @return int|false
	 */
	public function update($object)
	{
		return $this->getGenericConnector()->updateObject($object, $this->getPrimaryFields());
	}

	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function upsert($object)
	{
		return $this->getGenericConnector()->upsertObjectsByKeys($object, $this->getPrimaryFields());
	}
}