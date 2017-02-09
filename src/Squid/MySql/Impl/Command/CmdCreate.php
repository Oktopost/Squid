<?php
namespace Squid\MySql\Impl\Command;


use Squid\MySql\Command\ICmdCreate;
use Squid\MySql\Command\Create\IForeignKey;
use Squid\MySql\Command\Create\IColumnFactory;
use Squid\MySql\Impl\Command\Create\ColumnFactory;
use Squid\MySql\Impl\Command\Create\KeysCollection;
use Squid\MySql\Impl\Command\Create\ColumnsCollection;


class CmdCreate implements ICmdCreate
{
	
	// CREATE TABLE `okt`.`sad` ( `a` INT NOT NULL ) ENGINE = InnoDB CHARSET=binary COMMENT = 'asdasd';
	
	
	const PART_TEMP     = 0;
	const PART_DB		= 1;
	const PART_NAME		= 2;
	const PART_ENGINE	= 3;
	const PART_CHARSET	= 4;
	const PART_COMMENT  = 5;
	
	
	/**
	 * @var array Collection of default values.
	 */
	private static $DEFAULT = array(
		CmdCreate::PART_TEMP    => false,
		CmdCreate::PART_DB		=> false,
		CmdCreate::PART_NAME    => false,
		CmdCreate::PART_ENGINE  => false,
		CmdCreate::PART_CHARSET => false,
		CmdCreate::PART_COMMENT => false
	);
	
	
	/** @var KeysCollection */
	private $indexes; 
	
	/** @var ColumnsCollection */
	private $columnsList;
	
	
	public function __construct()
	{
		$this->indexes = new KeysCollection();
		$this->columnsList = new ColumnsCollection();
	}
	
	
	/**
	 * @return string
	 */
	public function getName()
	{
		return self::$DEFAULT[self::PART_NAME];
	}
	
	/**
	 * @return static
	 */
	public function temporary()
	{
		self::$DEFAULT[self::PART_TEMP] = 'TEMPORARY';
		return $this;
	}
	
	/**
	 * @param string $db
	 * @return static
	 */
	public function db($db)
	{
		self::$DEFAULT[self::PART_DB] = "$db.";
		return $this;
	}
	
	/**
	 * @param string $name
	 * @return static
	 */
	public function table($name)
	{
		self::$DEFAULT[self::PART_NAME] = $name;
		return $this;
	}
	
	
	/**
	 * @param string $engine
	 * @return static
	 */
	public function engine($engine)
	{
		self::$DEFAULT[self::PART_ENGINE] = $engine;
		return $this;
	}
	
	/**
	 * @return static
	 */
	public function innoDB()
	{
		return $this->engine('InnoDB');
	}
	
	/**
	 * @param string $charset
	 * @return static
	 */
	public function charset($charset)
	{
		self::$DEFAULT[self::PART_CHARSET] = $charset;
		return $this;
	}
	
	/**
	 * @param string $name
	 * @return IColumnFactory
	 */
	public function column($name)
	{
		return new ColumnFactory($this->columnsList, $name);
	}
	
	/**
	 * @param string[] ...$columns
	 * @return static
	 */
	public function primary(...$columns)
	{
		$this->indexes->primary(...$columns);
		return $this;
	}
	
	/**
	 * @param string|null $name
	 * @param string[] ...$columns
	 * @return static
	 */
	public function index($name, ...$columns)
	{
		$this->indexes->index($name, ...$columns);
		return $this;
	}
	
	/**
	 * @param string|null $name
	 * @param string[] ...$columns
	 * @return static
	 */
	public function unique($name, ...$columns)
	{
		$this->indexes->unique($name, ...$columns);
		return $this;
	}
	
	/**
	 * @param string|null $name
	 * @return IForeignKey
	 */
	public function foreignKey($name = null)
	{
		return $this->indexes->foreignKey($name);
	}

	/**
	 * @param string $comment
	 * @return static
	 */
	public function comment($comment)
	{
		self::$DEFAULT[self::PART_COMMENT] = $comment;
		return $this;
	}
}