<?php
namespace Squid\MySql\Connectors\Object;


use Squid\MySql\Connectors\IConnector;
use Squid\MySql\Connectors\Table\ISingleTableConnector;


interface IORMConnector extends IConnector, ISingleTableConnector
{
	/**
	 * @param mixed $mapper
	 */
	public function setObjectMap($mapper);
}