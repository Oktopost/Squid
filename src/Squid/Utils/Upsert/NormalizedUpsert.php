<?php
namespace Squid\Utils\Upsert;

use Squid\MySql\Utils\IMappedObject;
use Squid\MySql\Utils\IUpsertObject;
use Squid\MySql\Command\ICmdUpsert;


/**
 * For Normalized upsert, the passed $upsert object must already contain the fields defined
 * in order key1, key2 .... keyN , param column, value columnt.
 */
class NormalizedUpsert implements IUpsertObject {
	
	/**
	 * @param ICmdUpsert $upsert
	 * @param IMappedObject $object
	 * @return bool
	 */
	public function upsert(ICmdUpsert $upsert, IMappedObject $object) {
		return $this->upsertAll($upsert, array($object));
	}
	
	/**
	 * @param ICmdUpsert $upsert
	 * @param array $objects Array of IMappedObject
	 * @return bool
	 */
	public function upsertAll(ICmdUpsert $upsert, array $objects) {
		$keys = false;
		
		foreach ($objects as $object) {
			$rows = $this->getRowsToInsert($object);
			
			if (!$keys) {
				$keys = $object->getKeyFields();
			}
			
			$upsert->valuesBulk($rows);
		}
		
		return $upsert
			->setDuplicateKeys($keys)
			->executeDml();
	}
	
	
	/**
	 * @param IMappedObject $object
	 * @return array
	 */
	private function getRowsToInsert(IMappedObject $object) {
		$fields	= array();
		$keys	= $this->getKeyValues($object);
		$values = $this->getColumnValues($object);
		
		foreach ($values as $name => $value) {
			$fields[] = array_merge($keys, array($name, $value));
		}
		
		return $fields;
	}
	
	/**
	 * @param IMappedObject $object
	 * @return array
	 */
	private function getKeyValues(IMappedObject $object) {
		$mapper	= $object->getColumnMapper();
		$row	= array();
		
		foreach ($object->getKeyFields() as $key) {
			$row[] = $mapper->getValue($object, $key);
		}
		
		return $row;
	}
	
	/**
	 * @param IMappedObject $object
	 * @return array
	 */
	private function getColumnValues(IMappedObject $object) {
		$mapper	= $object->getColumnMapper();
		$row	= array();
		
		foreach ($object->getValueFields() as $name) {
			$row[$name] = $mapper->getValue($object, $name);
		}
		
		return $row;
	}
}