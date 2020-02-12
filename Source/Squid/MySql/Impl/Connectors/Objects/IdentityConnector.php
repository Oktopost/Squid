<?php
namespace Squid\MySql\Impl\Connectors\Objects;


use Squid\MySql\Connectors\Objects\IIdentityConnector;
use Squid\MySql\Connectors\Objects\Generic\IGenericObjectConnector;

use Squid\MySql\Impl\Connectors\Objects\Generic\GenericObjectConnector;
use Squid\MySql\Impl\Connectors\Objects\Identity\TPrimaryKeys;

use Squid\MySql\Impl\Connectors\Internal\Objects\AbstractORMConnector;


class IdentityConnector extends AbstractORMConnector implements IIdentityConnector
{
	use TPrimaryKeys;
	
	
	/** @var IGenericObjectConnector */
	private $genericConnector;
	

	protected function getGenericObjectConnector(): IGenericObjectConnector
	{
		if (!$this->genericConnector)
			$this->genericConnector = new GenericObjectConnector($this);
		
		return $this->genericConnector;
	}
	
	
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
				
				return $this->getGenericObjectConnector()->deleteByField($keyField, $ids);
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
			
			return $this->getGenericObjectConnector()->deleteByFields($by);
		}
	}

	/**
	 * @param mixed $object
	 * @return int|false
	 */
	public function update($object)
	{
		return $this->getGenericObjectConnector()->updateObject($object, $this->getPrimaryFields());
	}

	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function upsert($object)
	{
		return $this->getGenericObjectConnector()->upsertObjectsByKeys($object, $this->getPrimaryFields());
	}
	
	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function insert($object)
	{
		return $this->getGenericObjectConnector()->insertObjects($object);
	}
}