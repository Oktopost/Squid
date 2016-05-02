<?php
namespace Squid\MySql\Utils;


interface IMappedObject {
	
	/**
	 * @return array
	 */
	public function getKeyFields();
	
	/**
	 * @return array
	 */
	public function getValueFields();
	
	/**
	 * @return IColumnToPropertyResolve
	 */
	public function getColumnMapper();
}