<?php
namespace Squid\MySql\Impl\Cmd;


use Squid\MySql\Command\ICmdDelete;


class CmdDelete extends PartsCommand implements ICmdDelete {
	use Squid\MySql\Traits\CmdTraits\TDml;
	use Squid\MySql\Traits\CmdTraits\TWithWhere;
	use Squid\MySql\Traits\CmdTraits\TWithLimit;
	
	
	const PART_FROM		= 0;
	const PART_WHERE	= 1;
	const PART_ORDER_BY	= 2;
	const PART_LIMIT	= 3;
	
	
	/**
	 * @var array Collection of default values.
	 */
	private static $DEFAULT = array(
		CmdDelete::PART_FROM	=> false,
		CmdDelete::PART_WHERE	=> false,
		CmdDelete::PART_ORDER_BY=> false,
		CmdDelete::PART_LIMIT	=> false
	);
	
	
	/**
	 * Get the parts this query can have.
	 * @return array Array contianing only the part as keys and values set to false.
	 */
	protected function getDefaultParts() {
		return CmdDelete::$DEFAULT;
	}
	
	/**
	 * Commbine all the parts into one sql.
	 * @return string Created query.
	 */
	protected function generate() {
		return 
			'DELETE FROM ' . $this->getPart(CmdDelete::PART_FROM) . ' ' . 
				Assembly::appendWhere($this->getPart(CmdDelete::PART_WHERE), true) . 
				Assembly::appendOrderBy($this->getPart(CmdDelete::PART_ORDER_BY)) . 
				Assembly::appendLimit(
					$this->getPart(CmdDelete::PART_LIMIT), 
					$this->getBind(CmdDelete::PART_LIMIT));
	}
	
	
	/**
	 * Set the table to delete from.
	 * @param string $table Name of the table to delete from.
	 * @return ICmdDelete Always returns self.
	 */
	public function from($table) {
		return $this->setPart(CmdDelete::PART_FROM, $table);
	}
	
	/**
	 * Add additional where clause.
	 * @param string $exp Expression to append.
	 * @param mixed|array|null $bind Single bind value, array of values or false if no 
	 * bind values are needed for this expression.
	 * @return mixed Always returns self.
	 */
	public function where($exp, $bind = false) {
		return $this->appendPart(CmdDelete::PART_WHERE, $exp, $bind); 
	}
	
	/**
	 * Required by the TLimit trait.
	 * @param array $columns Array of expressions to order by.
	 * @return mixed Always returns self.
	 */
	public function _orderBy(array $columns) {
		return $this->appendPart(CmdDelete::PART_ORDER_BY, $columns);
	}
	
	/**
	 * @param int $from Zero based index.
	 * @param int $count
	 * @return static
	 * @throws \Exception
	 */
	public function limit($from, $count) {
		if ($from) {
			throw new \Exception('MySQL DELETE query supports only LIMIT <count> and not LIMIT <from>, <count>');
		}
		
		return $this->setPart(CmdDelete::PART_LIMIT, true, $count);
	}
}