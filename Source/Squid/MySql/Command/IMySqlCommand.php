<?php
namespace Squid\MySql\Command;


use Squid\MySql\Connection\IMySqlConnection;


interface IMySqlCommand 
{
	/**
	 * @param IMySqlConnection $conn
	 */
	public function setConnection(IMySqlConnection $conn): void;
	
	
	/**
	 * For debug only
	 * @return string For debugging
	 */
	public function __toString();
}