<?php
namespace Squid\MySql\Impl\Command;


use Squid\MySql\Command\ICmdDirect;


class CmdDirect extends AbstractCommand implements ICmdDirect {
	use Squid\MySql\Traits\CmdTraits\TDml;
	use Squid\MySql\Traits\CmdTraits\TQuery;
	
	
	/**
	 * @var string Command to execute. 
	 */
	private $sql;
	
	/**
	 * @var array Array of bind params.
	 */
	private $params;
	
	
	/**
	 * Get the bind parameters.
	 * @return array Array of bind params.
	 */
	public function bind() {
		return $this->params;
	}
	
	/**
	 * Generate the query string.
	 * @return string Currently set query.
	 */
	public function assemble() {
		return $this->sql;
	}
	
	
	/**
	 * Set the query to execute.
	 * @param string $sql Sql command to execute.
	 * @param array $bind Array of bind params.
	 * @return ICmdDirect Always returns self.
	 */
	public function command($sql, array $bind = array()) {
		$this->sql = $sql;
		$this->params = $bind;
		
		return $this;
	}
	
	
	/**
	 * Execute and SELECT EXISTS query where the sub query of the exists in this object.
	 * @return bool True if query has values; false if not; null on error.
	 */
	public function queryExists() {
		$result = $this->asScalarSubQuery(
			function($sql) { 
				return "SELECT EXISTS ($sql)";
			}, 
			null);
		
		return (is_null($result) ? null : (bool)$result);
	}
	
	/**
	 * Execute count select for this query. Note that columns set will remain as was before.
	 * @return int|bool Number of rows or false on error.
	 */
	public function queryCount() {
		return $this->asScalarSubQuery(function($sql) { 
			return "SELECT COUNT(*) FROM ($sql) as _sub_";
		});
	}
	
	/**
	 * Execute this query as a subquery of other query that must return a scalar value.
	 * @param callable $callback Callback to use to create the new query. Callback must be 
	 * of format string func(string), where the param is the current query and return value is
	 * the new query to execute.
	 * @param mixed $default Default value to return for failer.
	 */
	private function asScalarSubQuery($callback, $default = false) {
		$sql = $this->sql;
		$this->sql = $callback($sql);
		
		$result = $this->queryScalar(null);
		
		$this->sql = $sql;
		
		return (is_null($result) ? $default : $result);
	}
}