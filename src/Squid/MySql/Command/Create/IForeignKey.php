<?php
namespace Squid\MySql\Command\Create;


interface IForeignKey
{
	/**
	 * @param string $name
	 * @return static
	 */
	public function name($name);
	
	/**
	 * @return string
	 */
	public function getName();
	
	/**
	 * @param string $table
	 * @param string $column
	 * @return static
	 */
	public function on($table, $column);
	
	/**
	 * @param string $behavior
	 * @return static
	 */
	public function onUpdate($behavior);
	
	/**
	 * @return static
	 */
	public function onUpdateCascade();
	
	/**
	 * @return static
	 */
	public function onUpdateRestrict();
	
	/**
	 * @return static
	 */
	public function onUpdateSetNull();
	
	/**
	 * @param string $behavior
	 * @return static
	 */
	public function onDelete($behavior);
	
	/**
	 * @return static
	 */
	public function onDeleteCascade();
	
	/**
	 * @return static
	 */
	public function onDeleteRestrict();
	
	/**
	 * @return static
	 */
	public function onDeleteSetNull();
	
	/**
	 * @return string
	 */
	public function generate();
}