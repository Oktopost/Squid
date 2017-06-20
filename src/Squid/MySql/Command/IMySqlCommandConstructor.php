<?php
namespace Squid\MySql\Command;


interface IMySqlCommandConstructor extends IMySqlCommand
{
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
}