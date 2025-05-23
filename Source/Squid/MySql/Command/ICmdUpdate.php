<?php
namespace Squid\MySql\Command;


interface ICmdUpdate extends IDml, IMySqlCommandConstructor, IWithWhere, IWithSet, IWithLimit
{
	/**
	 * Set the status of the ignore flag.
	 * @param bool $ignore If true, use ignore flag, otherwise don't.
	 * @return static
	 */
	public function ignore(bool $ignore = true);
	
	/**
	 * Set the table to update.
	 * @param string $table Name of the table to update.
	 * @param bool $escape
	 * @return static
	 */
	public function table($table, bool $escape = true);
}