<?php
namespace Squid\MySql\Query;


use Objection\LiteObject;
use Squid\MySql\IMySqlConnector;
use Squid\MySql\Command\ICmdSelect;


abstract class AbstractQueryHandler implements IQueryHandler
{
	/** @var IMySqlConnector|null */
	private $connector;
	
	/** @var IQuery */
	private $query;
	
	
	private $table = null;
	private $object = null;
	
	
	private function invoke(string $func, array $args): AbstractQueryHandler
	{
		call_user_func_array([$this->select(), substr($func, 1)], $args);
		return $this;
	}
	
	
	protected function setTable(string $table): void
	{
		$this->table = $table;
	}
	
	protected function setObject(string $className): void
	{
		$this->object = $className;
	}
	
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
	
	
	protected function _limitBy(int $count): AbstractQueryHandler { return $this->invoke(__FUNCTION__, func_get_args()); }
	protected function _limit(int $from, int $count): AbstractQueryHandler { return $this->invoke(__FUNCTION__, func_get_args()); }
	protected function _where($exp, $bind = []): AbstractQueryHandler { return $this->invoke(__FUNCTION__, func_get_args()); }
	protected function _byField($field, $value): AbstractQueryHandler { return $this->invoke(__FUNCTION__, func_get_args()); }
	protected function _byFields($fields, $values = null): AbstractQueryHandler { return $this->invoke(__FUNCTION__, func_get_args()); }
	
	
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
		
		if ($this->table)
		{
			$this->query->select()->from($this->table, null, false);
		}
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
		if ($this->object)
		{
			$o = $this->object;
			
			/** @var LiteObject $i */
			$i = new $o;
			$i->fromArray($record);
			
			return $i;
		}
		
		return null;
	}
	
	public function processAll(array $data): ?array
	{
		return null;
	}
}