<?php
namespace Squid\MySql\Impl\Traits\CmdTraits;


use Squid\MySql\Command\IWithExtendedWhere;


/**
 * @see IWithExtendedWhere
 */
trait TWithExtendedWhere
{
	/**
	 * @param string $field
	 * @param mixed $greater
	 * @param mixed $less
	 * @return IWithExtendedWhere|static
	 */
	public function whereBetween(string $field, $greater, $less): IWithExtendedWhere
	{
		$this->where("$field BETWEEN ? AND ?", [$greater, $less]);
		return $this;
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return IWithExtendedWhere|static
	 */
	public function whereNotEqual(string $field, $value): IWithExtendedWhere
	{
		$this->where("$field != ?", [$value]);
		return $this;
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return IWithExtendedWhere|static
	 */
	public function whereLess(string $field, $value): IWithExtendedWhere
	{
		$this->where("$field < ?", [$value]);
		return $this;
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return IWithExtendedWhere|static
	 */
	public function whereLessOrEqual(string $field, $value): IWithExtendedWhere
	{
		$this->where("$field <= ?", [$value]);
		return $this;
	}
	
	/**
	 * @param string $field
	 * @param mixed $value
	 * @return IWithExtendedWhere|static
	 */
	public function whereGreater(string $field, $value): IWithExtendedWhere
	{
		$this->where("$field > ?", [$value]);
		return $this;
	}
	
	/**
	 * @param string $field
	 * @param mixed $value
	 * @return IWithExtendedWhere|static
	 */
	public function whereGreaterOrEqual(string $field, $value): IWithExtendedWhere
	{
		$this->where("$field >= ?", [$value]);
		return $this;
	}
}