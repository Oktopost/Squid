<?php
namespace Squid\MySql\Command;


use Squid\MySql\Command\Create\IIndexable;
use Squid\MySql\Command\Create\IForeignKey;
use Squid\MySql\Command\Create\IColumnsSource;


interface ICmdCreate extends IColumnsSource, IIndexable 
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
	 * @param string $name
	 * @return static
	 */
	public function table($name);
	
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
}