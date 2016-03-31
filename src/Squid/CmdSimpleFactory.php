<?php
namespace Squid;


use \Squid\MySql\Cmd;
use \Squid\Base\Cmd;
use \Squid\Base\ICmdSimpleFactory;


class CmdSimpleFactory implements ICmdSimpleFactory {
	
	/**
	 * @return Cmd\ICmdController
	 */
	public function createController() {
		return new Cmd\CmdController();
	}
	
	/**
	 * @return Cmd\ICmdDelete
	 */
	public function createDelete() {
		return new Cmd\CmdDelete();
	}
	
	/**
	 * @return Cmd\ICmdDirect New direct object.
	 */
	public function createDirect() {
		return new Cmd\CmdDirect();
	}
	
	/**
	 * @return Cmd\ICmdInsert
	 */
	public function createInsert() {
		return new Cmd\CmdInsert();
	}
	
	/**
	 * @return Cmd\ICmdLock
	 */
	public function createLock() {
		return new Cmd\CmdLock();
	}
	
	/**
	 * @return Cmd\ICmdSelect
	 */
	public function createSelect() {
		return new Cmd\CmdSelect();
	}
	
	/**
	 * @return Cmd\ICmdUpdate
	 */
	public function createUpdate() {
		return new Cmd\CmdUpdate;
	}
	
	/**
	 * @return Cmd\ICmdUpsert
	 */
	public function createUpsert() {
		return new Cmd\CmdUpsert();
	}
	
	/**
	 * @return Cmd\ICmdDB
	 */
	public function createDB() {
		return new Cmd\CmdDB();
	}
}