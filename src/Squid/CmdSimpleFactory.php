<?php
namespace Squid;


use \Squid\Cmd;
use \Squid\Base\CmdCreators;
use \Squid\Base\ICmdSimpleFactory;


class CmdSimpleFactory implements ICmdSimpleFactory {
	
	/**
	 * @return CmdCreators\ICmdController
	 */
	public function createController() {
		return new Cmd\CmdController();
	}
	
	/**
	 * @return CmdCreators\ICmdDelete
	 */
	public function createDelete() {
		return new Cmd\CmdDelete();
	}
	
	/**
	 * @return CmdCreators\ICmdDirect New direct object.
	 */
	public function createDirect() {
		return new Cmd\CmdDirect();
	}
	
	/**
	 * @return CmdCreators\ICmdInsert
	 */
	public function createInsert() {
		return new Cmd\CmdInsert();
	}
	
	/**
	 * @return CmdCreators\ICmdLock
	 */
	public function createLock() {
		return new Cmd\CmdLock();
	}
	
	/**
	 * @return CmdCreators\ICmdSelect
	 */
	public function createSelect() {
		return new Cmd\CmdSelect();
	}
	
	/**
	 * @return CmdCreators\ICmdUpdate
	 */
	public function createUpdate() {
		return new Cmd\CmdUpdate;
	}
	
	/**
	 * @return CmdCreators\ICmdUpsert
	 */
	public function createUpsert() {
		return new Cmd\CmdUpsert();
	}
	
	/**
	 * @return CmdCreators\ICmdDB
	 */
	public function createDB() {
		return new Cmd\CmdDB();
	}
}