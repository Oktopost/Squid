<?php
namespace Squid\MySql\Connectors\Extensions\PolymorphicIdentity;


use Squid\MySql\Connectors\Object\CRUD\IIdentifiedObjectConnector;


interface IPolymorphicIdentityConfig
{
	public function getConnector(string $type): IIdentifiedObjectConnector;
	
	public function getTypeByIdentity($id): ?string;
	public function getConnectorByIdentity($id): ?IIdentifiedObjectConnector;
	public function getTypeByIdentities(array $ids): array;
	
	public function getTypeByObject($object): ?string;
	public function getConnectorByObject($object): ?IIdentifiedObjectConnector;
	public function getTypeByObjects(array $objects): array;
}