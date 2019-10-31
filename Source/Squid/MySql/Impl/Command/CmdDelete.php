<?php
namespace Squid\MySql\Impl\Command;


use Squid\MySql\Command\ICmdDelete;
use Squid\MySql\Command\IWithLimit;
use Squid\Exceptions\SquidException;


class CmdDelete extends PartsCommand implements ICmdDelete
{
	use \Squid\MySql\Impl\Traits\CmdTraits\TDml;
	use \Squid\MySql\Impl\Traits\CmdTraits\TWithWhere;
	use \Squid\MySql\Impl\Traits\CmdTraits\TWithLimit;
	
	
	const PART_FROM		= 0;
	const PART_WHERE	= 1;
	const PART_ORDER_BY	= 2;
	const PART_LIMIT	= 3;
	
	
	/**
	 * @var array Collection of default values.
	 */
	private static $DEFAULT = [
		CmdDelete::PART_FROM	=> false,
		CmdDelete::PART_WHERE	=> false,
		CmdDelete::PART_ORDER_BY=> false,
		CmdDelete::PART_LIMIT	=> false
	];
	
	
	/**
	 * Get the parts this query can have.
	 * @return array Array containing only the part as keys and values set to false.
	 */
	protected function getDefaultParts()
	{
		return CmdDelete::$DEFAULT;
	}
	
	/**
	 * Combine all the parts into one sql.
	 * @return string Created query.
	 */
	protected function generate()
	{
		return
			'DELETE FROM ' . $this->getPart(CmdDelete::PART_FROM) . ' ' .
			Assembly::appendWhere($this->getPart(CmdDelete::PART_WHERE), true) .
			Assembly::appendOrderBy($this->getPart(CmdDelete::PART_ORDER_BY)) .
			Assembly::append('LIMIT', $this->getPart(CmdDelete::PART_LIMIT));
	}
	
	
	/**
	 * Set the table to delete from.
	 * @param string $table Name of the table to delete from.
	 * @return static
	 */
	public function from($table)
	{
		return $this->setPart(CmdDelete::PART_FROM, $table);
	}
	
	/**
	 * Add additional where clause.
	 * @param string $exp Expression to append.
	 * @param mixed|array|null $bind
	 * @return static
	 */
	public function where(string $exp, $bind = [])
	{
		return $this->appendPart(CmdDelete::PART_WHERE, $exp, $bind);
	}
	
	/**
	 * Required by the TLimit trait.
	 * @param array $columns
	 * @return static
	 */
	public function _orderBy(array $columns)
	{
		return $this->appendPart(CmdDelete::PART_ORDER_BY, $columns);
	}
	
	/**
	 * @param int $from Zero based index.
	 * @param int $count
	 * @return static|IWithLimit
	 */
	public function limit($from, $count): IWithLimit
	{
		if ($from)
			throw new SquidException('MySQL DELETE query supports only LIMIT [count] and not LIMIT [from], [count]');
		
		return $this->setPart(CmdDelete::PART_LIMIT, [$count], []);
	}
}