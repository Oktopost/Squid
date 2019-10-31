<?php
namespace Squid\MySql\Impl\Traits;


use Squid\MySql\Connection\IMySqlConnection;


trait TMysqlCommand
{
	/** @var IMySqlConnection */
	private $_conn;
	
	
	private function connection(): IMySqlConnection
	{
		return $this->_conn;
	}
	
	
	/**
	 * @param IMySqlConnection $conn
	 */
	public function setConnection(IMySqlConnection $conn): void
	{
		$this->_conn = $conn;
	}
}