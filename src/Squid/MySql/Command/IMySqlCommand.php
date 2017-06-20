<?php
namespace Squid\MySql\Command;


use Squid\MySql\Connection\IMySqlConnection;


interface IMySqlCommand 
{
	/**
	 * @param IMySqlConnection $conn
	 */
	public function setConnection(IMySqlConnection $conn);
	
	
	/**
	 * For debug only
	 * @return string Return string in format: "Query string : {json bind params}"
	 */
	public function __toString();
}