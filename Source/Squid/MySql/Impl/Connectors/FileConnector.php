<?php
namespace Squid\MySql\Impl\Connectors;


use Squid\MySql\IMySqlConnector;
use Squid\Exceptions\SquidException;


class FileConnector
{
	/** @var IMySqlConnector */
	private $connector;
	
	
	public function __construct(IMySqlConnector $connector = null)
	{
		if ($connector) 
			$this->connector = $connector; 
	}


	/**
	 * Set the connector to use.
	 * @param IMySqlConnector $connector
	 */
	public function setConnector(IMySqlConnector $connector)
	{
		$this->connector = $connector;
	}


	/**
	 * Execute the entire file. On error the execution is aborted. 
	 * @param string $path Full path to the file. 
	 * @return bool
	 */
	public function execute($path)
	{
		if (!file_exists($path) || !is_readable($path))
			throw new SquidException("The file at [$path] is unreadable or doesn't exists");
		
		$data = file_get_contents($path);
		
		$result = $this->connector
			->bulk()
			->add($data)
			->executeAll();
		
		return (bool)$result;
	}
}