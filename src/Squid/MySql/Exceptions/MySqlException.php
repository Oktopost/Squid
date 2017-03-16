<?php
namespace Squid\MySql\Exceptions;


use Squid\Exceptions\SquidException;


class MySqlException extends SquidException
{
	private $sqlErrorCode = null;
	
	
	/**
	 * @param string $message
	 * @param int $code
	 * @param \Exception|null $previous
	 */
	public function __construct($message, $code = 0, \Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
	
	
	/**
	 * @return int|null
	 */
	public function getSqlCode()
	{
		return $this->sqlErrorCode;
	}
	
	
	/**
	 * @param \PDOException $exception
	 * @return MySqlException
	 */
	public static function createFromPDOException(\PDOException $exception)
	{
		$message = $exception->getMessage();
		$codePosition = strpos($message, "[{$exception->getCode()}]");
		
		if ($codePosition !== false)
		{
			$message = trim(substr($message, $codePosition + strlen("[{$exception->getCode()}]") + 1));
		}
		
		return new MySqlException($message, $exception->getCode());
	}

	/**
	 * @param array $info
	 * @return MySqlException
	 */
	public static function createFromPDOInfo(array $info)
	{
		$e = new MySqlException($info[2], $info[1]);
		$e->sqlErrorCode = $info[0];
		return $e;
	}

	/**
	 * @param array|\PDOException $source
	 * @return MySqlException
	 */
	public static function create($source)
	{
		if ($source instanceof \PDOException)
			throw self::createFromPDOException($source);
		
		if (is_array($source))
			throw self::createFromPDOInfo($source);
		
		throw new \Exception('Invalid source!');
	}
}