<?php
namespace Squid\MySql\Connectors\Table;


use Squid\MySql\Connectors\IConnector;


interface ISingleTableConnector extends IConnector
{
	/**
	 * @param string|ITableNameConnector $table
	 * @return ISingleTableConnector|static
	 */
	public function setTable($table): ISingleTableConnector;
}