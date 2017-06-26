<?php
namespace Squid\MySql\Impl\Connectors\Extensions\PolymorphicIdentity;


use Squid\Exceptions\SquidException;
use Squid\MySql\Connectors\Extensions\PolymorphicIdentity\IPolymorphicIdentityConfig;
use Squid\MySql\Connectors\Object\CRUD\IIdentifiedObjectConnector;


abstract class AbstractPolymorphicIdentityConfig implements IPolymorphicIdentityConfig
{
	private function getTypeByCallback(array $items, callable $callback): array
	{
		$map = [];
		
		foreach ($items as $item)
		{
			$type = $callback($item);
			
			if (!$type) 
				throw new SquidException('Connector for requested object not found!');
			
			if (!isset($map[$type]))
			{
				$map[$type] = [];
			}
			
			$map[$type][] = $item;
		}
		
		return $map;
	}
	
	
	public function getConnectorByIdentity($id): ?IIdentifiedObjectConnector
	{
		$type = $this->getTypeByIdentity($id);
		return ($type ? $this->getConnector($type) : null);
	}
	
	public function getConnectorByObject($object): ?IIdentifiedObjectConnector
	{
		$type = $this->getTypeByObject($object);
		return ($type ? $this->getConnector($type) : null);
	}
	
	public function getTypeByIdentities(array $ids): array
	{
		return $this->getTypeByCallback($ids, [$this, 'getTypeByIdentity']);
	}
	
	public function getTypeByObjects(array $objects): array
	{
		return $this->getTypeByCallback($objects, [$this, 'getTypeByObject']);
	}
}