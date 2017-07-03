<?php
namespace Squid\MySql\Connectors\Object\Polymorphic;


use Squid\MySql\Connectors\Object\Generic\IGenericObjectConnector;


interface IPolymorphicConfig
{
	public function getConnector(string $name): IGenericObjectConnector;
	public function getObjectConnector($object): IGenericObjectConnector;
	
	/** 
	 * @return IGenericObjectConnector[] 
	 */
	public function getConnectors(): array;
	
	public function sortObjectsByGroups(array $objects): array;
	public function sortExpressionsByGroups(array $whereExpression): array;
	
	public function expressionsIterator(array $fields): iterable;
	public function objectsIterator(array $objects): iterable;
}