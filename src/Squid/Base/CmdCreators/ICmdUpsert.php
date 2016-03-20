<?php
namespace Squid\Base\CmdCreators;


/**
 * Create "insert ... on duplicate update" query.
 */
interface ICmdUpsert extends IDml, ICmdInsert, IWithSet {
	
	/**
	 * Use the new values of the fields that have duplicate error.
	 * This function fill generate: fieldA = VALUES(fieldA) sub queries for all
	 * the fields spesified in the array, or all the insert fields minus $fields 
	 * if $negate is true (default behavior).
	 * @param string|array $fields single field, or array of fields that should 
	 * be ignored or used (depending on the value if $negate) to set them to the new 
	 * values used in insert.
	 * @param bool $negate If true, use all fields passed to insert expect given in 
	 * $fields. Otherwise use only $fields values.
	 * @return static
	 */
	public function setUseNewValues($fields);
	
	/**
	 * List of fields that are the keys of this insert and on duplicate, all fields
	 * but this keys should be copied. This is as logical inversion to setUseNewValues(...)
	 * @param string|array $fields single field, or array of fields that are a part of a 
	 * unique/primary key on the table. On Duplicate all fields but thouse are used in the set cluster.
	 * @return static
	 */
	public function setDuplicateKeys($fields);
	
}