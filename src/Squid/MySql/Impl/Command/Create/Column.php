<?php
namespace Squid\MySql\Impl\Command\Create;


use Squid\MySql\Command\Create\ITableColumn;


class Column implements ITableColumn
{
	const PART_NAME				= 0;
	const PART_TYPE				= 1;
	const PART_ATTRIBUTES		= 2;
	const PART_COLLATION		= 3;
	const PART_NULL				= 4;
	const PART_DEFAULT			= 5;
	const PART_AUTO_INCREMENT	= 6;
	const PART_COMMENT			= 7;
	
	
	/**
	 * @var [string|null]
	 */
	private $parts = [
		self::PART_NAME				=> null,
		self::PART_TYPE				=> null,
		self::PART_ATTRIBUTES		=> null,
		self::PART_COLLATION		=> null,
		self::PART_NULL				=> 'NOT NULL',
		self::PART_DEFAULT			=> null,
		self::PART_AUTO_INCREMENT	=> null,
		self::PART_COMMENT			=> null
	];
	
	
	/**
	 * @param string|null $name
	 */
	public function __construct($name = null) 
	{
		if ($name)
			$this->name($name);
	}
	
	
	
	/**
	 * @param string $name
	 * @return static
	 */
	public function name($name)
	{
		$this->parts[self::PART_NAME] = $name;
		return $this;
	}
	
	/**
	 * @param string $type
	 * @param int|string|null $length
	 * @return static
	 */
	public function type($type, $length = null)
	{
		if (!is_null($length))
			$type = "$type($length)";
		
		$this->parts[self::PART_TYPE] = $type;
		return $this;
	}
	
	/**
	 * @return static
	 */
	public function null()
	{
		$this->parts[self::PART_NULL] = 'NULL';
		return $this;
	}
	
	/**
	 * @return static
	 */
	public function notNull()
	{
		$this->parts[self::PART_NULL] = 'NOT NULL';
		return $this;
	}
	
	/**
	 * @param mixed $value
	 * @return static
	 */
	public function defaultValue($value)
	{
		$this->parts[self::PART_DEFAULT] = "DEFAULT $value";
		return $this;
	}
	
	/**
	 * @param string $characterSet
	 * @param string $collate
	 * @return static
	 */
	public function collation($characterSet, $collate)
	{
		$this->parts[self::PART_COLLATION] = "CHARACTER SET $characterSet COLLATE $collate";
		return $this;
	}
	
	/**
	 * @param string $expression
	 * @return static
	 */
	public function attributesExpression($expression)
	{
		$this->parts[self::PART_ATTRIBUTES] = $expression;
		return $this;
	}
	
	/**
	 * @return static
	 */
	public function onUpdateCurrentTimestampExpression()
	{
		$this->attributesExpression('ON UPDATE CURRENT_TIMESTAMP');
		return $this;
	}
	
	/**
	 * @param string $comment
	 * @return static
	 */
	public function comment($comment)
	{
		$this->parts[self::PART_COMMENT] = 'COMMENT "' . $comment . '"';
		return $this;
	}

	/**
	 * Mark this field as AUTO_INCREMENT
	 * @return static
	 */
	public function autoIncrement()
	{
		$this->parts[self::PART_AUTO_INCREMENT] = 'AUTO_INCREMENT';
		return $this;
	}
	
	
	/**
	 * @return string
	 */
	public function assemble()
	{
		$values = array_filter($this->parts);
		return implode(' ', $values);
	}
}