<?php
namespace Squid\MySql\Impl\Utils;


use Squid\MySql\Connection\IMySqlConnection;
use Squid\MySql\Connection\IOpenConnectionsManager;


class OpenConnectionsManager implements IOpenConnectionsManager 
{
	/** @var IMySqlConnection[] */
	private $openConnections = [];
	
	
	public function closeAll() 
	{
		if (!$this->openConnections) return;
		
		foreach ($this->openConnections as $connection) 
		{
			$connection->close();
		}
		
		$this->openConnections = [];
	}
	
	/**
	 * @param IMySqlConnection $connection
	 */
	public function subscribeToClose(IMySqlConnection $connection)
	{
		if (array_search($connection, $this->openConnections, true) === false)
			$this->openConnections[] = $connection;
	}
}