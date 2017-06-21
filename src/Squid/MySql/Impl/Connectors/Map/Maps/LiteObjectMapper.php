<?php
namespace Squid\MySql\Impl\Connectors\Map\Maps;


use Squid\MySql\Connectors\Map\IRowMap;
use Squid\MySql\Connectors\Map\TRowMap;

use Objection\Mapper;


class LiteObjectMapper implements IRowMap
{
	use TRowMap;
	
	
	/** @var Mapper */
	private $mapper;
	
	
	public function __construct(Mapper $mapper)
	{
		$this->mapper = $mapper;
	}


	/**
	 * @param mixed $object
	 * @return array Assoc array that can be inserted into the database.
	 */
	public function toRow($object): array
	{
		return $this->mapper->getArray($object);
	}

	/**
	 * @param array $row Assoc row from database.
	 * @return mixed
	 */
	public function toObject(array $row)
	{
		return $this->mapper->getObject($row);
	}
}