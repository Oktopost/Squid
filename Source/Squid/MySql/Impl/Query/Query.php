<?php
namespace Squid\MySql\Impl\Query;


use Squid\MySql\Query\IQuery;
use Squid\MySql\Query\IQueryHandler;
use Squid\MySql\Command\ICmdSelect;


class Query implements IQuery
{
	/** @var ICmdSelect */
	private $select;
	
	/** @var IQueryHandler */
	private $handler;
	
	
	public function __construct(IQueryHandler $handler, ICmdSelect $select)
	{
		$this->select = $select;
		$this->handler = $handler;
		
		$handler->setup($this);
	}
	
	
	public function select(): ICmdSelect
	{
		return $this->select;
	}
	
	public function assemble(): string
	{
		return $this->select->assemble();
	}
	
	public function bind(): array
	{
		return $this->select->bind();
	}
	
	public function query(): array
	{
		$select = $this->handler->preExecute($this->select);
		$result = [];
		
		foreach ($select->query() as $record)
		{
			if (!$this->handler->filterRecord($record))
				continue;
			
			$parsed = $this->handler->processRecord($record);
			$parsed = $parsed ?? $record;
			
			$result[] = $parsed;
		}
		
		return $this->handler->processAll($result) ?? $result;
	}
	
	/**
	 * @return null|mixed
	 */
	public function queryFirst()
	{
		$select = $this->handler->preExecute($this->select);
		
		foreach ($select->query() as $record)
		{
			if (!$this->handler->filterRecord($record))
				continue;
			
			$parsed = $this->handler->processRecord($record) ?? $record;
			$bulkParsed = $this->handler->processAll([$parsed]);
			
			if (is_null($bulkParsed))
			{
				return $parsed;
			}
			else if (!$bulkParsed)
			{
				return null;
			}
			else
			{
				return $bulkParsed[0];
			}
		}
		
		return null;
	}
}