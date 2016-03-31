<?php
namespace Squid\Base;


use \Squid\Base\Cmd;


interface ICmdSimpleFactory {
	
	/**
	 * @return Cmd\ICmdController
	 */
	public function createController();
	
	/**
	 * @return Cmd\ICmdDelete
	 */
	public function createDelete();
	
	/**
	 * @return Cmd\ICmdDirect
	 */
	public function createDirect();
	
	/**
	 * @return Cmd\ICmdInsert
	 */
	public function createInsert();
	
	/**
	 * @return Cmd\ICmdLock
	 */
	public function createLock();
	
	/**
	 * @return Cmd\ICmdSelect
	 */
	public function createSelect();
	
	/**
	 * @return Cmd\ICmdUpdate
	 */
	public function createUpdate();
	
	/**
	 * @return Cmd\ICmdUpsert
	 */
	public function createUpsert();
	
	/**
	 * @return Cmd\ICmdDB
	 */
	public function createDB();	
}