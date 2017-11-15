<?php
namespace Squid\MySql\Impl\Extensions\Query\Objects;


use Objection\LiteObject;
use Objection\LiteSetup;


/**
 * @property int	$Offset
 * @property int	$TotalCount
 * @property int	$Count
 * @property array	$Data
 */
class PaginatedByFieldResult extends LiteObject
{
	/**
	 * @return array
	 */
	protected function _setup()
	{
		return [
			'Offset'		=> LiteSetup::createInt(0),
			'TotalCount'	=> LiteSetup::createInt(0),
			'Count'			=> LiteSetup::createInt(0),
			'Data'			=> LiteSetup::createArray()
		];
	}
	
	
	public function setData(array $data): void
	{
		$this->Data = $data;
		$this->Count = count($data);
	}
	
	public function isEmpty(): bool
	{
		return $this->Count == 0;
	}
	
	public function isNotEmpty(): bool
	{
		return $this->Count > 0;
	}
}