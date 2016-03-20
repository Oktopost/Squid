<?php
namespace Squid\Base;


use \Squid\Base\CmdCreators;


interface ICmdSimpleFactory {
	
	/**
	 * @return CmdCreators\ICmdController
	 */
	public function createController();
	
	/**
	 * @return CmdCreators\ICmdDelete
	 */
	public function createDelete();
	
	/**
	 * @return CmdCreators\ICmdDirect
	 */
	public function createDirect();
	
	/**
	 * @return CmdCreators\ICmdInsert
	 */
	public function createInsert();
	
	/**
	 * @return CmdCreators\ICmdLock
	 */
	public function createLock();
	
	/**
	 * @return CmdCreators\ICmdSelect
	 */
	public function createSelect();
	
	/**
	 * @return CmdCreators\ICmdUpdate
	 */
	public function createUpdate();
	
	/**
	 * @return CmdCreators\ICmdUpsert
	 */
	public function createUpsert();
	
	/**
	 * @return CmdCreators\ICmdDB
	 */
	public function createDB();	
}