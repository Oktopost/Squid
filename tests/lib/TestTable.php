<?php
namespace lib;


use Squid\MySql\IMySqlConnector;


class TestTable
{
	private $name;
	private $columns;
	private $data = [];
	
	/** @var IMySqlConnector */
	private $connector;


	/**
	 * @param IMySqlConnector $connection
	 * @param string $name
	 * @param array $columns
	 */
	public function __construct(IMySqlConnector $connection, $name, array $columns)
	{
		$this->name = $name;
		$this->connector = $connection;
		$this->columns = $columns;
	}


	/**
	 * @return mixed
	 */
	public function name()
	{
		return $this->name;
	}

	/**
	 * @return IMySqlConnector
	 */
	public function connector()
	{
		return $this->connector;
	}

	/**
	 * @param array $data
	 * @return static
	 */
	public function data(array $data)
	{
		$firstItem = reset($data);
		$isOneRow = !is_array($firstItem);
		
		$insert = $this->connector
			->insert()
			->into($this->name, $this->columns);
		
		if ($isOneRow)
		{
			$insert->values($data);
			$newRows = [$data];
		}
		else 
		{
			$insert->valuesBulk($data);
			$newRows = $data;
		}
		
		if (!$insert->executeDml())
		{
			throw new \Exception('Failed to save data! Table: ' . $this->name . ' data: ' . json_encode($data));
		}
		else
		{
			$this->data = array_merge($this->data, $newRows);
		}
		
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->name();
	}
	
	/**
	 * @return array
	 */
	public function __debugInfo()
	{
		return [
			'Name'	=> $this->name,
			'Data'	=> $this->data
		];
	}
}