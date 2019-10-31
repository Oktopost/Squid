<?php
namespace Squid\MySql\Command;


interface IMySqlCommandConstructor extends IMySqlCommand
{
	/**
	 * @return array
	 */
	public function bind(): array;
	
	/**
	 * Generate the query string.
	 * @return string Currently set query.
	 */
	public function assemble(): string;
	
	/**
	 * Execute the generated query.
	 * @throws \PDOException
	 * @return \PDOStatement|mixed
	 */
	public function execute();
}