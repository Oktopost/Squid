<?php
namespace Squid\MySql\Connectors\Extensions\PolymorphicIdentity;


use Squid\MySql\Connectors\Object\IIdentityConnector;


interface IPolymorphicIdentityConfig
{
	public function getConnector(string $type): IIdentityConnector;
	
	public function getTypeByIdentity($id): ?string;
	public function getConnectorByIdentity($id): ?IIdentityConnector;
	public function getTypeByIdentities(array $ids): array;
	
	public function getTypeByObject($object): ?string;
	public function getConnectorByObject($object): ?IIdentityConnector;
	public function getTypeByObjects(array $objects): array;
}