<?php
namespace Squid\MySql\Connectors;


use Squid\MySql\Connectors\Table\ITableNameConnector;


interface ISingleTableConnector
{
	/**
	 * @param string|ITableNameConnector $table
	 * @return ISingleTableConnector|static
	 */
	public function setTable($table): ISingleTableConnector;
}