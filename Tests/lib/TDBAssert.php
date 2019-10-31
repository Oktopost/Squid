<?php

namespace lib;


use PHPUnit\Framework\TestCase;

/**
 * @mixin TestCase
 */
trait TDBAssert
{
	public static $LAST_ROW = [];
	
	
	public static function assertRowExists($table, $fields, $value = null)
	{
		if (is_string($fields))
			$fields = [$fields => $value];
		
		$result = DataSet::connector()->select()->from($table)->byFields($fields)->queryExists();
		
		self::assertTrue($result);
	}
	
	public static function assertRowCount($expected, $table, $fields = null, $value = null)
	{
		$select = DataSet::connector()->select()->from($table);
		
		if ($fields)
		{
			if (is_string($fields))
				$fields = [$fields => $value];
			
			$select->byFields($fields);
		}
		
		self::assertSame($expected, $select->queryCount());
	}
	
	public static function assertLastRowExists($table)
	{
		self::assertRowExists($table, self::$LAST_ROW);
	}


	public static function row(...$values): array
	{
		static $fields = 'abcdefghijklmnopqrstvuwxyz';
		
		self::$LAST_ROW = [];
		
		for ($i = 0; $i < count($values); $i++)
		{
			self::$LAST_ROW[$fields[$i]] = $values[$i];
		}
		
		return self::$LAST_ROW;
	}
}