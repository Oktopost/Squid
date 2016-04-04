<?php
namespace Squid;


use Squid\MySql\Command;
use Squid\MySql\ICmdSimpleFactory;
use Squid\MySql\Impl\Cmd;


class CmdSimpleFactory implements ICmdSimpleFactory {
	
	/**
	 * @return Cmd\ICmdController
	 */
	public function controller() {
		return new Cmd\CmdController();
	}
	
	/**
	 * @return Cmd\ICmdDelete
	 */
	public function delete() {
		return new Cmd\CmdDelete();
	}
	
	/**
	 * @return Cmd\ICmdDirect New direct object.
	 */
	public function direct() {
		return new Cmd\CmdDirect();
	}
	
	/**
	 * @return Cmd\ICmdInsert
	 */
	public function insert() {
		return new Cmd\CmdInsert();
	}
	
	/**
	 * @return Cmd\ICmdLock
	 */
	public function lock() {
		return new Cmd\CmdLock();
	}
	
	/**
	 * @return Cmd\ICmdSelect
	 */
	public function select() {
		return new Cmd\CmdSelect();
	}
	
	/**
	 * @return Cmd\ICmdUpdate
	 */
	public function update() {
		return new Cmd\CmdUpdate;
	}
	
	/**
	 * @return Cmd\ICmdUpsert
	 */
	public function upsert() {
		return new Cmd\CmdUpsert();
	}
	
	/**
	 * @return Cmd\ICmdDB
	 */
	public function db() {
		return new Cmd\CmdDB();
	}
}