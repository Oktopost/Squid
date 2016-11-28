<?php
namespace Squid\MySql\Command\Create;


interface IColumnFactory
{
	/**
	 * @param int $length
	 * @return IColumn
	 */
	public function char($length);
	
	/**
	 * @param int $length
	 * @return IColumn
	 */
	public function varchar($length);
	
	/**
	 * @param int $length
	 * @return IColumn
	 */
	public function int($length = 11);
}