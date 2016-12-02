<?php
namespace lib;


class DataSet
{
	use \Objection\TStaticClass;
	
	
	private static $tables = [];


	/**
	 * @return string
	 */
	private static function getRandomName()
	{
		$name = '';
		
		while (!$name && !isset(self::$tables[$name]))
		{
			
		}
		
		return $name;
	}
}