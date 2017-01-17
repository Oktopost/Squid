<?php
namespace Squid\MySql;


class ForeignKeyBehavior
{
	use \Objection\TEnum;
	
	
	const NO_ACTION = 'NO_ACTION';
	const CASCADE   = 'CASCADE';
	const SET_NULL  = 'SET_NULL';
	const RESTRICT  = 'RESTRICT';
	
	
	const VALUES = [
		self::NO_ACTION => 'NO ACTION',
		self::CASCADE   => 'CASCADE',
		self::RESTRICT  => 'RESTRICT',
		self::SET_NULL  => 'SET NULL'
	];
	
	
	/**
	 * @param string $behavior
	 * @return string
	 */
	public static function get($behavior) 
	{
		return self::VALUES[$behavior];
	}
}