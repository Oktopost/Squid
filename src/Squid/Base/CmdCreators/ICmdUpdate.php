<?php
namespace Squid\Base\CmdCreators;


use \Squid\Base\ICmdCreator;


/**
 * Create a new update query.
 */
interface ICmdUpdate extends IDml, ICmdCreator, IWithWhere, IWithSet, IWithLimit {
	
	/**
	 * Set the status of the ignore flag.
	 * @param bool $ignore If true, use ignore flag, otherwise don't.
	 * @return static
	 */
	public function ignore($ignore = true);
	
	/**
	 * Set the table to update.
	 * @param string $table Name of the table to update.
	 * @return static
	 */
	public function table($table);
	
}