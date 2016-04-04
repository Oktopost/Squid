<?php
namespace Squid\MySql;


use Squid\MySql\Command;


interface ICmdSimpleFactory 
{
	/**
	 * @return Command\ICmdController
	 */
	public function controller();
	
	/**
	 * @return Command\ICmdDelete
	 */
	public function delete();
	
	/**
	 * @return Command\ICmdDirect
	 */
	public function direct();
	
	/**
	 * @return Command\ICmdInsert
	 */
	public function insert();
	
	/**
	 * @return Command\ICmdLock
	 */
	public function lock();
	
	/**
	 * @return Command\ICmdSelect
	 */
	public function select();
	
	/**
	 * @return Command\ICmdUpdate
	 */
	public function update();
	
	/**
	 * @return Command\ICmdUpsert
	 */
	public function upsert();
	
	/**
	 * @return Command\ICmdDB
	 */
	public function db();	
}