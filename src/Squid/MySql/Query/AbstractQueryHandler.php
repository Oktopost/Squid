<?php
namespace Squid\MySql\Query;


use Squid\MySql\IMySqlConnector;
use Squid\MySql\Command\ICmdSelect;


abstract class AbstractQueryHandler implements IQueryHandler
{
	/** @var IMySqlConnector|null */
	private $connector;
	
	/** @var IQuery */
	private $query;
	
	
	protected function conn(): IMySqlConnector
	{
		return $this->connector;
	}
	
	protected function select(): ICmdSelect
	{
		return $this->query->select();
	}
	
	protected function queryObject(): IQuery
	{
		return $this->query;
	}
	
	
	public function __construct(?IMySqlConnector $connector = null)
	{
		if ($connector)
		{
			$this->connector = $connector;
			$connector->query($this);
		}
	}
	
	
	public function setup(IQuery $query): void
	{
		$this->query = $query;
	}
	
	public function preExecute(ICmdSelect $select): ICmdSelect
	{
		return $select;
	}
	
	public function filterRecord(array $record): bool
	{
		return true;
	}
	
	/**
	 * @param array $record
	 * @return mixed
	 */
	public function processRecord(array $record)
	{
		return null;
	}
	
	public function processAll(array $data): ?array
	{
		return null;
	}
}