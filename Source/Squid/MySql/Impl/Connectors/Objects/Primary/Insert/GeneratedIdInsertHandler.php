<?php
namespace Squid\MySql\Impl\Connectors\Objects\Primary\Insert;


use Squid\Exceptions\SquidUsageException;
use Squid\MySql\Connectors\Objects\ID\IIdGenerator;


class GeneratedIdInsertHandler extends AbstractInsertHandler
{
	private $tableName;
	
	/** @var IIdGenerator */
	private $generator;
	
	
	public function setGenerator(IIdGenerator $generator): GeneratedIdInsertHandler
	{
		$this->generator = $generator;
		return $this;
	}
	
	public function setTableName(string $tableName): GeneratedIdInsertHandler
	{
		$this->tableName = $tableName;
		return $this;
	}
	
	
	/**
	 * @param array $items
	 * @return int|false
	 */
	public function insert(array $items)
	{
		$ids = $this->generator->generate($this->tableName, $items);
		
		try
		{
			$count = count($ids);
			$field = $this->idField();
			
			if ($count != count($items))
				throw new SquidUsageException('Incorect number of Ids generated! Id must be generated for each field');
			
			for ($i = 0; $i < $count; $i++)
			{
				$items[$i]->$field = $ids[$i];
			}
			
			return $this->doInsert($items);
		}
		finally
		{
			if ($ids)
			{
				$this->generator->release($ids);
			}
		}
	}
}