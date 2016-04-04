<?php
namespace Squid\MySql\Connection;


interface IMySqlConnectionDecorator extends IMySqlConnection
{
	/**
	 * @param IMySqlConnection $conn Decorated connection.
	 */
	public function init(IMySqlConnection $conn);
}