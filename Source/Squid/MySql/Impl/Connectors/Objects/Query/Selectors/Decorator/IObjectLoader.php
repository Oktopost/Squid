<?php
namespace Squid\MySql\Impl\Connectors\Objects\Query\Selectors\Decorator;


interface IObjectLoader
{
	public function loadOne($object);
	public function loadAll(array $objects);
}