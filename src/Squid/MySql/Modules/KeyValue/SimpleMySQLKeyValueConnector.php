<?php
namespace Squid\MySql\Modules\KeyValue;


use Squid\MySql\IMySqlConnector;
use Squid\KeyValue\IKeyValueConnector;


class SimpleMySQLKeyValueConnector implements IKeyValueConnector
{
	/** @var IMySqlConnector */
	private $connector;
	
	private $tableName = 'KeyValueCache';
	
	
	/**
	 * @param IMySqlConnector $connection
	 * @param string|bool $tableName
	 */
	public function __construct(IMySqlConnector $connection, $tableName = false)
	{
		if ($tableName)
			$this->tableName = $tableName;
		
		$this->connector = $connection;
	}
	
	
	/**
	 * @param string $key
	 * @return bool
	 */
	public function has($key)
	{
		return $this->connector
			->select()
			->from($this->tableName)
			->byField('ID', $key)
			->queryExists();
	}
	
	/**
	 * @param string $key
	 * @return string|null
	 */
	public function get($key)
	{
		return $this->connector
			->select()
			->columns('Data')
			->from($this->tableName)
			->byField('ID', $key)
			->queryScalar(null, false);
	}
	
	/**
	 * @param string $key
	 * @param string $value
	 * @return bool
	 */
	public function set($key, $value)
	{
		return $this->connector
			->upsert()
			->into($this->tableName)
			->values([
				'ID'		=> $key,
				'Data'		=> $value
			])
			->executeDml();
	}
	
	/**
	 * @param string $key
	 */
	public function delete($key)
	{
		$this->connector
			->delete()
			->from($this->tableName)
			->byField('ID', $key)
			->executeDml();
	}
}