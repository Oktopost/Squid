<?php

namespace Squid\MySql\Impl\Connectors\Extensions\PolymorphicIdentity;


use Squid\Exceptions\SquidException;
use Squid\MySql\Connectors\Object\CRUD\IIdentifiedObjectConnector;
use Squid\MySql\Connectors\Extensions\PolymorphicIdentity\IPolymorphicIdentityConfig;
use Squid\MySql\Impl\Connectors\Internal\Connector;


class PolymorphicIdentityConnector extends Connector implements IIdentifiedObjectConnector
{
	/** @var IPolymorphicIdentityConfig */
	private $config;
	
	
	private function getConnectorByObject($object): IIdentifiedObjectConnector
	{
		$conn = $this->config->getConnectorByObject($object);
		
		if (!$conn)
			throw new SquidException('Connector for object not found!');
		
		return $conn;
	}
	
	private function getConnectorById($id): IIdentifiedObjectConnector
	{
		$conn = $this->config->getConnectorByObject($id);
		
		if (!$conn)
			throw new SquidException('Connector for identifier not found!');
		
		return $conn;
	}

	/**
	 * @param array $map
	 * @param string $methodName
	 * @return false|int
	 */
	private function executeForMap(array $map, string $methodName)
	{
		$totalResult = 0;
		
		foreach ($map as $type => $items)
		{
			$result = $this->config->getConnector($type)->$methodName($items);
			
			if ($result === false)
				return false;
			
			$totalResult += $result;
		}
		
		return $totalResult;
	}
	
	
	public function __construct(IPolymorphicIdentityConfig $config)
	{
		parent::__construct();
		$this->config = $config;
	}
	
	
	/**
	 * @param mixed|array $id
	 * @return mixed|null|false
	 */
	public function load($id)
	{
		if (!is_array($id))
			return $this->getConnectorById($id)->load($id);
		
		$types = $this->config->getTypeByIdentities($id);
		$allItems = [];
		
		foreach ($types as $type => $typeIds)
		{
			$items = $this->config->getConnector($type)->load($typeIds);
			
			if ($items === false)
			{
				return false;
			}
			else if ($items)
			{
				$allItems[] = $items;
			}
		}
		
		return array_merge($allItems);
	}
	
	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function save($object)
	{
		if (!is_array($object))
			return $this->getConnectorByObject($object)->save($object);
		
		$types = $this->config->getTypeByObjects($object);
		return $this->executeForMap($types, 'save');
	}

	/**
	 * @param mixed $id
	 * @return mixed|false
	 */
	public function deleteById($id)
	{
		if (!is_array($id))
			return $this->getConnectorById($id)->load($id);
		
		$types = $this->config->getTypeByIdentities($id);
		$totalResult = 0;
		
		foreach ($types as $type => $typeIds)
		{
			$result = $this->config->getConnector($type)->delete($typeIds);
			
			if ($result === false)
				return false;
			
			$totalResult += $result;
		}
		
		return $totalResult;
	}

	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function delete($object)
	{
		if (!is_array($object))
			return $this->getConnectorByObject($object)->delete($object);
		
		$types = $this->config->getTypeByObjects($object);
		return $this->executeForMap($types, 'delete');
	}

	/**
	 * @param mixed|array $object
	 * @param bool $ignore
	 * @return false|int
	 */
	public function insert($object, bool $ignore = false)
	{
		if (!is_array($object))
			return $this->getConnectorByObject($object)->insert($object, $ignore);
		
		$types = $this->config->getTypeByIdentities($object);
		$totalResult = 0;
		
		foreach ($types as $type => $typeObjects)
		{
			$result = $this->config->getConnector($type)->insert($typeObjects, $ignore);
			
			if ($result === false)
				return false;
			
			$totalResult += $result;
		}
		
		return $totalResult;
	}

	/**
	 * @param mixed $object
	 * @return int|false
	 */
	public function update($object)
	{
		return $this->getConnectorByObject($object)->update($object);
	}

	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function upsert($object)
	{
		if (!is_array($object))
			return $this->getConnectorByObject($object)->upsert($object);
		
		$types = $this->config->getTypeByObjects($object);
		return $this->executeForMap($types, 'upsert');
	}
}