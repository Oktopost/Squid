<?php
namespace Squid\MySql\Impl\Connectors\Object;


use Squid\MySql\Connectors\IConnector;
use Squid\MySql\Connectors\ISingleTableConnector;
use Squid\MySql\Connectors\Map\IRowMap;


interface IORMConnector extends IConnector, ISingleTableConnector
{
	/**
	 * @param mixed $mapper
	 */
	public function setMapper($mapper);

	/**
	 * @return IRowMap
	 */
	public function getMapper(): IRowMap;
}