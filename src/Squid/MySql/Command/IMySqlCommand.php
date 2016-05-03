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
	 * @return array
	 */
	public function bind();
	
	/**
	 * Generate the query string.
	 * @return string Currently set query.
	 */
	public function assemble();
	
	/**
	 * Execute the generated query.
	 * @throws \PDOException
	 * @return \PDOStatement
	 */
	public function execute();
	
	
	/**
	 * For debug only
	 * @return string Return string in format: "Query string : {json bind params}"
	 */
	public function __toString();
}