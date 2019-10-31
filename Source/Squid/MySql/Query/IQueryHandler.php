<?php
namespace Squid\MySql\Query;


use Squid\MySql\Command\ICmdSelect;


interface IQueryHandler
{
	public function setup(IQuery $query): void;
	public function preExecute(ICmdSelect $select): ICmdSelect;
	
	public function filterRecord(array $record): bool;
	
	/**
	 * @param array $record
	 * @return mixed
	 */
	public function processRecord(array $record);
	
	public function processAll(array $data): ?array;
}