<?php
namespace Squid\MySql\Command;


use Squid\MySql\Command\MultiQuery\IStatementResult;


interface ICmdMultiQuery extends IMySqlCommand
{
	/**
	 * @param string|IMySqlCommand|array $query
	 * @param array $bind
	 * @return ICmdMultiQuery
	 */
	public function add($query, array $bind = []);
	
	/**
	 * @return IStatementResult|false Result of the last query returned
	 */
	public function executeAll();
	
	/**
	 * Return an iterator to iterate over all result sets.
	 * @return \Generator|IStatementResult[]
	 */
	public function executeIterator();
	
	/**
	 * @param callable $callback Called for each single query. 
	 * The callback receives the IStatementResult.
	 */
	public function executeWithCallback(callable $callback);
}