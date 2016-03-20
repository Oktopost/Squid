<?php
namespace Squid\Base\CmdCreators;


use \Squid\Base\Utils\QueryFailedException;


/**
 * Select query command.
 */
interface IQuery {
	
	/**
	 * @param bool|int $isAssoc If true return assoc result; otherwise false. Also
	 * int, representing \PDO::FETCH_* mode can be passed for other mods.
	 * @return array
	 */
	public function queryAll($isAssoc = false);
	
	/**
	 * @param bool|int $isAssoc If true return assoc result; otherwise false. Also
	 * int, representing \PDO::FETCH_* mode can be passed for other mods.
	 * @param bool $oneOrNone
	 * @return array|bool
	 */
	public function queryRow($isAssoc = false, $oneOrNone = true);
	
	/**
	 * @param bool $oneOrNone
	 * @return array|bool
	 */
	public function queryRowAssoc($oneOrNone = true);
	
	/**
	 * @param bool $oneOrNone
	 * @return array|bool
	 */
	public function queryColumn($oneOrNone = true);
	
	/**
	 * @param mixed $default
	 * @param bool $expectOne
	 * @return mixed|bool
	 */
	public function queryScalar($default = false, $expectOne = true);
	
	/**
	 * @param bool $expectOne
	 * @return int|bool
	 */
	public function queryInt($expectOne = true);
	
	/**
	 * @return bool|null
	 */
	public function queryExists();
	
	/**
	 * @return int|bool
	 */
	public function queryCount();
	
	/**
	 * @param callable $callback Called for each selected row. 
	 * If callback returns false, queryWithCallback will abort and return false.
	 * If callback returns 0, queryWithCallback will abort and return true.
	 * For any other value, callback will continue to the next row.
	 * @param bool $isAssoc
	 * @return bool
	 */
	public function queryWithCallback($callback, $isAssoc = true);
	
	/**
	 * @param bool $isAssoc
	 * @return \Iterator
	 * @throws QueryFailedException
	 */
	public function queryIterator($isAssoc = true);
}