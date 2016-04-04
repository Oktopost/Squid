<?php
namespace Squid\MySql\Command;


/**
 * Create "insert ... on duplicate update" query.
 */
interface ICmdUpsert extends IDml, ICmdInsert, IWithSet
{
	/**
	 * Use the new values of the fields that have duplicate error.
	 * This function fill generate: fieldA = VALUES(fieldA) sub queries for all
	 * fields specified in array.
	 * @param array|string $fields
	 * @return static
	 */
	public function setUseNewValues($fields);
	
	/**
	 * List of fields that are the keys of this insert and should not be used in the 'in duplicate' sub query.
	 * This is the opposite of the setUseNewValues(...) method.
	 * @param array|string $fields
	 * @return static
	 */
	public function setDuplicateKeys($fields);
}