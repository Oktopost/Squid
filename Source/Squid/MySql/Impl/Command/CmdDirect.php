<?php
namespace Squid\MySql\Impl\Command;


use Squid\MySql\Command\ICmdDirect;


class CmdDirect extends AbstractCommand implements ICmdDirect 
{
	use \Squid\MySql\Impl\Traits\CmdTraits\TDml;
	use \Squid\MySql\Impl\Traits\CmdTraits\TQuery;
	
	
	private $sql;
	
	/** @var array */
	private $params;
	
	
	/**
	 * Execute this query as a sub query of another query that must return a scalar value.
	 * @param callable $callback Callback to use to create the new query. Callback must be
	 * of format string func(string), where the param is the current query and return value is
	 * the new query to execute.
	 * @param mixed $default Default value to return on failure.
	 * @return bool|mixed
	 */
	private function asScalarSubQuery($callback, $default = false) 
	{
		$sql = $this->sql;
		
		$this->sql = $callback($sql);
		$result = $this->queryScalar(null);
		$this->sql = $sql;
		
		return (is_null($result) ? $default : $result);
	}
	
	
	/**
	 * @return array
	 */
	public function bind() 
	{
		return $this->params;
	}
	
	/**
	 * @return string
	 */
	public function assemble() 
	{
		return $this->sql;
	}
	
	
	/**
	 * @inheritdoc
	 */
	public function command($sql, array $bind = array()) 
	{
		$this->sql = $sql;
		$this->params = $bind;
		
		return $this;
	}
	
	
	/**
	 * @inheritdoc
	 */
	public function queryExists() 
	{
		$result = $this->asScalarSubQuery(
			function($sql) { 
				return "SELECT EXISTS ($sql)";
			}, 
			null);
		
		return (is_null($result) ? null : (bool)$result);
	}
	
	/**
	 * @inheritdoc
	 */
	public function queryCount() 
	{
		return $this->asScalarSubQuery(
			function($sql) 
			{ 
				return "SELECT COUNT(*) FROM ($sql) as _sub_";
			});
	}
}