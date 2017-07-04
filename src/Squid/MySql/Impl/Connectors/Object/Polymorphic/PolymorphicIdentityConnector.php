<?php
namespace Squid\MySql\Impl\Connectors\Object\Polymorphic;


use Squid\Exceptions\SquidUsageException;
use Squid\MySql\Connectors\Object\Generic\IGenericIdentityConnector;
use Squid\MySql\Connectors\Object\Generic\IGenericObjectConnector;
use Squid\MySql\Connectors\Object\Polymorphic\IPolymorphicIdentityConnector;


class PolymorphicIdentityConnector extends PolymorphicConnector implements IPolymorphicIdentityConnector
{
	private function getIdentityConnector(string $name): IGenericIdentityConnector
	{
		$connector = $this->getConfig()->getConnector($name);
		
		if (!($connector instanceof IGenericIdentityConnector))
		{
			throw new SquidUsageException(
				'Connectors passed to ' . self::class . ' must be of ' . 
				IGenericIdentityConnector::class . ' type');
		}
		
		return $connector;
	}
	
	/**
	 * @param mixed|array $object
	 * @param string $method
	 * @return int|false
	 */
	private function executeIdentityOperation($object, string $method)
	{
		if (!is_array($object)) 
			$object = [$object];
		
		$groups = $this->getConfig()->objectsIterator($object);
		$count = 0;
		
		foreach ($groups as $name => $objects)
		{
			$res = $this->getIdentityConnector($name)->$method($objects);
			
			if ($res === false)
				return $res;
			
			$count += $res;
		}
		
		return $count;
	}
	
	
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
		return $this->executeIdentityOperation($object, __FUNCTION__);
	}

	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function insert($object)
	{
		return $this->executeIdentityOperation($object, __FUNCTION__);
	}

	/**
	 * @param mixed $object
	 * @return int|false
	 */
	public function update($object)
	{
		$conn = $this->getConfig()->getObjectConnector($object);
		
		if (!($conn instanceof IGenericIdentityConnector))
		{
			throw new SquidUsageException(
				'Connectors passed to ' . self::class . ' must be of ' . 
				IGenericIdentityConnector::class . ' type');
		}
		
		return $conn->update($object);
	}

	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function upsert($object)
	{
		return $this->executeIdentityOperation($object, __FUNCTION__);
	}
}