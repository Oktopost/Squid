<?php
namespace Squid;


use Squid\MySql\Command;
use Squid\MySql\ICmdSimpleFactory;
use Squid\MySql\Impl\Command\CmdController;


class CmdSimpleFactory implements ICmdSimpleFactory {
	
	/**
	 * @return Command\ICmdController
	 */
	public function controller() {
		return new CmdController();
	}
	
	/**
	 * @return Command\ICmdDelete
	 */
	public function delete() {
		return new Command\CmdDelete();
	}
	
	/**
	 * @return Command\ICmdDirect New direct object.
	 */
	public function direct() {
		return new Command\CmdDirect();
	}
	
	/**
	 * @return Command\ICmdInsert
	 */
	public function insert() {
		return new Command\CmdInsert();
	}
	
	/**
	 * @return Command\ICmdLock
	 */
	public function lock() {
		return new Command\CmdLock();
	}
	
	/**
	 * @return Command\ICmdSelect
	 */
	public function select() {
		return new Command\CmdSelect();
	}
	
	/**
	 * @return Command\ICmdUpdate
	 */
	public function update() {
		return new Command\CmdUpdate;
	}
	
	/**
	 * @return Command\ICmdUpsert
	 */
	public function upsert() {
		return new Command\CmdUpsert();
	}
	
	/**
	 * @return Command\ICmdDB
	 */
	public function db() {
		return new Command\CmdDB();
	}
}