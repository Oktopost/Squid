<?php
namespace Squid\MySql\Command\MultiQuery;


use Squid\MySql\Command\IQuery;


interface IStatementResult extends IQuery
{
	/**
	 * @return int
	 */
	public function rowsCount();
}