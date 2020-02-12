<?php
namespace Squid\MySql\Impl\Connectors\Objects\Primary\Insert;


use Squid\MySql\IMySqlConnector;


class AutoIncInsertHandler extends AbstractInsertHandler
{
	/** @var IMySqlConnector */
	private $connector;
	
	
	public function setConnector(IMySqlConnector $connector): AutoIncInsertHandler
	{
		$this->connector = $connector;
		return $this;
	}
	
	
	/**
	 * @param array $items
	 * @return int|false
	 */
	public function insert(array $items)
	{
		$idField = $this->idField();
		$result = $this->doInsert($items);
		
		if (!$result) return $result;
		
		$lastId = $this->connector->controller()->lastId();
		
		if ($lastId === false) return false;
		
		foreach ($items as $item)
		{
			$item->$idField = $lastId++;
		}
		
		return $result;
	}
}