<?php
namespace Squid\MySql\Command;


use Squid\MySql\Command\Create\IIndexable;
use Squid\MySql\Command\Create\IForeignKey;
use Squid\MySql\Command\Create\IColumnsSource;


interface ICmdCreate extends IMySqlCommand, IColumnsSource, IIndexable 
{
	/**
	 * @return string
	 */
	public function getName();
	
	
	/**
	 * @return static
	 */
	public function temporary();
	
	/**
	 * @param string $db
	 * @return static
	 */
	public function db($db);
	
	/**
	 * @param string $name Database name, or table name if second parameter is omitted. 
	 * @param string|bool $tableName
	 * @return static
	 */
	public function table($name, $tableName = false);

	/**
	 * @return static
	 */
	public function ifNotExists();
	
	/**
	 * @param string $engine
	 * @return static
	 */
	public function engine($engine);
	
	/**
	 * @return static
	 */
	public function innoDB();
	
	/**
	 * @param string $charset
	 * @return static
	 */
	public function charset($charset);
	
	/**
	 * @param string[] ...$columns
	 * @return static
	 */
	public function primary(...$columns);
	
	/**
	 * @param string|null $name
	 * @return IForeignKey
	 */
	public function foreignKey($name = null);

	/**
	 * @param string $comment
	 * @return static
	 */
	public function comment($comment);
	
	/**
	 * @param string $name Database name, or table name if second parameter is omitted. 
	 * @param string|bool $tableName
	 * @return static
	 */
	public function like($name, $tableName = false);
	
	/**
	 * @param IMySqlCommand|string $query
	 * @return static
	 */
	public function asQuery($query);
}