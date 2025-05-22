<?php
namespace Squid\MySql\Impl\Command;


use Squid\MySql\Command\ICmdUpdate;
use Squid\MySql\Command\IWithLimit;


class CmdUpdate extends PartsCommand implements ICmdUpdate
{
	use \Squid\MySql\Impl\Traits\CmdTraits\TDml;
	use \Squid\MySql\Impl\Traits\CmdTraits\TWithSet;
	use \Squid\MySql\Impl\Traits\CmdTraits\TWithWhere;
	use \Squid\MySql\Impl\Traits\CmdTraits\TWithLimit;
	
	
	const PART_IGNORE	= 0;
	const PART_TABLE	= 1;
	const PART_SET		= 2;
	const PART_WHERE	= 3;
	const PART_ORDER_BY	= 4;
	const PART_LIMIT	= 5;
	
	
	/**
	 * @var array Collection of default values.
	 */
	private static $DEFAULT = array(
		CmdUpdate::PART_IGNORE	=> false,
		CmdUpdate::PART_TABLE	=> false,
		CmdUpdate::PART_SET		=> false,
		CmdUpdate::PART_WHERE	=> false,
		CmdUpdate::PART_ORDER_BY=> false,
		CmdUpdate::PART_LIMIT	=> false
	);
	
	
	/**
	 * @return array Array containing only the part as keys and values set to false.
	 */
	protected function getDefaultParts()
	{
		return CmdUpdate::$DEFAULT;
	}
	
	/**
	 * Combined all the parts into one sql.
	 * @return string Created query.
	 */
	protected function generate() 
	{
		return 
			'UPDATE ' . 
				($this->getPart(CmdUpdate::PART_IGNORE) ? 'IGNORE ' : '') . 
				$this->getPart(CmdUpdate::PART_TABLE) . ' ' . 
				Assembly::appendSet($this->getPart(CmdUpdate::PART_SET), true) . 
				Assembly::appendWhere($this->getPart(CmdUpdate::PART_WHERE), true) . 
				Assembly::appendOrderBy($this->getPart(CmdUpdate::PART_ORDER_BY)) . 
				Assembly::append('LIMIT', $this->getPart(CmdUpdate::PART_LIMIT));
	}
	
	
	/**
	 * Set the status of the ignore flag.
	 * @param bool $ignore If true, use ignore flag, otherwise don't.
	 * @return static
	 */
	public function ignore(bool $ignore = true) 
	{
		return $this->setPart(CmdUpdate::PART_IGNORE, $ignore);
	}
	
	/**
	 * Set the table to update.
	 * @param string $table Name of the table to update.
	 * @param bool $escape
	 * @return static
	 */
	public function table($table, bool $escape = true)
	{
		if ($escape)
		{
			$table = "`{$table}`";
		}
		
		return $this->setPart(CmdUpdate::PART_TABLE, $table);
	}
	
	/**
	 * Add additional where clause.
	 * @param string $exp Expression to append.
	 * @param mixed|array|null $bind Single bind value, array of values or false if no 
	 * bind values are needed for this expression.
	 * @return static
	 */
	public function where(string $exp, $bind = []) 
	{
		return $this->appendPart(CmdUpdate::PART_WHERE, $exp, $bind); 
	}
	
	/**
	 * Required by the TLimit trait.
	 * @param array $columns Array of expressions to order by.
	 * @return static
	 */
	public function _orderBy(array $columns) 
	{
		return $this->appendPart(CmdUpdate::PART_ORDER_BY, $columns);
	}
	
	/**
	 * Limit the query for given set.
	 * @param int $from Select form this row.
	 * @param int $count Maximum number of rows to select.
	 * @return IWithLimit|static
	 */
	public function limit($from, $count): IWithLimit
	{
		return $this->setPart(CmdUpdate::PART_LIMIT, ($from ? [(int)$from . ', ' . (int)$count] : [(int)$count]));
	}
	
	
	/**
	 * Function called by TWithSet.
	 * @param string $exp Full set expression.
	 * @param mixed $bind Bind params, if any.
	 * @return static
	 */
	public function _set($exp, $bind = []) 
	{
		return $this->appendPart(CmdUpdate::PART_SET, $exp, $bind); 
	}
}