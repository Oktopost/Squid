<?php
namespace Squid\MySql\Command\Create;


interface IColumn
{
	/**
	 * @param string $name
	 * @return static
	 */
	public function name($name);
	
	/**
	 * @param string $type
	 * @param string|null $length
	 * @return static
	 */
	public function type($type, $length = null);
	
	/**
	 * @return static
	 */
	public function null();
	
	/**
	 * @return static
	 */
	public function notNull();
	
	/**
	 * @param mixed $value
	 * @return static
	 */
	public function defaultValue($value);
	
	/**
	 * @param string $characterSet
	 * @param string $collate
	 * @return static
	 */
	public function collation($characterSet, $collate);
	
	/**
	 * @param string $expression
	 * @return static
	 */
	public function attributesExpression($expression);
	
	/**
	 * @return static
	 */
	public function onUpdateCurrentTimestampExpression();
	
	/**
	 * @param string $comment
	 * @return static
	 */
	public function comment($comment);

	/**
	 * Mark this field as AUTO_INCREMENT
	 * @return static
	 */
	public function autoIncrement();
}