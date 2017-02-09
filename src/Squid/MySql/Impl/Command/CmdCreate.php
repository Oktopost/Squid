<?php
namespace Squid\MySql\Impl\Command;


use Squid\MySql\Command\ICmdCreate;
use Squid\MySql\Command\ICmdSelect;
use Squid\MySql\Impl\Command\Create\KeysCollection;
use Squid\MySql\Impl\Command\Create\ColumnsCollection;


class CmdCreate extends AbstractCommand implements ICmdCreate
{
	use \Squid\MySql\Impl\Traits\CmdTraits\TWithIndex;
	use \Squid\MySql\Impl\Traits\CmdTraits\TWithColumns;
	
	
	const PART_TEMP			= 0;
	const PART_IF_NOT_EXIST	= 1;
	const PART_DB			= 2;
	const PART_NAME			= 3;
	const PART_ENGINE		= 4;
	const PART_CHARSET		= 5;
	const PART_COMMENT 		= 6;
	const PART_LIKE			= 7;
	const PART_AS			= 8;
	
	
	/**
	 * @var array Collection of default values.
	 */
	private static $DEFAULT = array(
		CmdCreate::PART_TEMP 			=> false,
		CmdCreate::PART_IF_NOT_EXIST	=> false,
		CmdCreate::PART_DB				=> '',
		CmdCreate::PART_NAME			=> false,
		CmdCreate::PART_ENGINE			=> false,
		CmdCreate::PART_CHARSET 		=> false,
		CmdCreate::PART_COMMENT 		=> false,
		CmdCreate::PART_LIKE			=> false,
		CmdCreate::PART_AS				=> false
	);


	/** @var array */
	private $parts;
	
	
	public function __construct()
	{
		$this->indexes = new KeysCollection();
		$this->columnsList = new ColumnsCollection();
		$this->parts = self::$DEFAULT;
	}
	
	
	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->parts[self::PART_NAME];
	}
	
	/**
	 * @return static
	 */
	public function temporary()
	{
		$this->parts[self::PART_TEMP] = 'TEMPORARY';
		return $this;
	}
	
	/**
	 * @param string $db
	 * @return static
	 */
	public function db($db)
	{
		$this->parts[self::PART_DB] = ($db ? "`$db`." : '');
		return $this;
	}
	
	/**
	 * @param string $name Database name, or table name if second parameter is omitted. 
	 * @param string|bool $tableName
	 * @return static
	 */
	public function table($name, $tableName = false)
	{
		if ($tableName) 
		{
			$this->db($name);
			$name = $tableName;
		}
		
		$this->parts[self::PART_NAME] = "`$name`";
		return $this;
	}

	/**
	 * @return static
	 */
	public function ifNotExists()
	{
		$this->parts[self::PART_IF_NOT_EXIST] = 'IF NOT EXISTS';
		return $this;
	}
	
	/**
	 * @param string $engine
	 * @return static
	 */
	public function engine($engine)
	{
		$this->parts[self::PART_ENGINE] = $engine;
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
		$this->parts[self::PART_CHARSET] = $charset;
		return $this;
	}
	
	/**
	 * @param string $comment
	 * @return static
	 */
	public function comment($comment)
	{
		$this->parts[self::PART_COMMENT] = $comment;
		return $this;
	}

	/**
	 * @return array
	 */
	public function bind()
	{
		return [];
	}

	/**
	 * @param int $part
	 * @param string $prefix
	 * @param string $suffix
	 * @return string
	 */
	private function getPartIfSet($part, $prefix = '', $suffix = '')
	{
		return ($this->parts[$part] ? $prefix . $this->parts[$part] . $suffix . ' ' : '');
	}
	
	/**
	 * Generate the query string.
	 * @return string Currently set query.
	 */
	public function assemble()
	{
		$command = 
			'CREATE ' . 
				$this->getPartIfSet(self::PART_TEMP) .
			'TABLE ' . 
				$this->getPartIfSet(self::PART_IF_NOT_EXIST) .
				$this->parts[self::PART_DB] . $this->parts[self::PART_NAME];
		
		$command .= "(\n";
		
		$command .= ') ' . 
			$this->getPartIfSet(self::PART_ENGINE, 'ENGINE=') .
			$this->getPartIfSet(self::PART_CHARSET, 'CHARSET=') .
			$this->getPartIfSet(self::PART_COMMENT, 'COMMENT=');
		
		return $command;
	}

	/**
	 * @param string $name Database name, or table name if second parameter is omitted.
	 * @param string|bool $tableName
	 * @return static
	 */
	public function like($name, $tableName = false)
	{
		$this->parts[self::PART_LIKE] = ($tableName ? "`$name`.`$tableName`" : "`$name`");
		return $this;
	}

	/**
	 * @param ICmdSelect|string $query
	 * @return static
	 */
	public function asQuery($query)
	{
		$this->parts[self::PART_AS] = $query;
		return $this;
	}
}