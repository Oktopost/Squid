<?php
namespace Squid\MySql\Impl\Extensions\Query;


use Squid\MySql\Command\ICmdSelect;
use Squid\MySql\Impl\Extensions\Query\Objects\PaginatedByFieldResult;

use Squid\OrderBy;


class PaginatedByFieldExtension
{
	public const DEFAULT_ORDER = OrderBy::ASC;
	
	private const SIGNS = [
		0 => '<',
		1 => '>'
	];
	
	
	/** @var null|int */ 
	private $limit = null;
	private $offset = 0;
	
	private $field;
	private $value;
	private $idField;
	private $idValue;
	
	private $isBefore = true;
	private $order = self::DEFAULT_ORDER;
	
	/** @var ICmdSelect */
	private $query;
	
	
	private function getFieldSign(bool $reversed): string
	{
		// Start from the less ('<') sign. Each condition reverses the sign that should be used. 
		$greater = false;
		
		if (!$this->isBefore)				$greater = !$greater;
		if ($this->order != OrderBy::ASC)	$greater = !$greater;
		if ($reversed)						$greater = !$greater;
		
		return self::SIGNS[$greater ? 1 : 0];
	}
	
	private function getOffset(): int 
	{
		$sign = $this->getFieldSign(!$this->isBefore);
		
		$beforeQuery = clone $this->query;
		$beforeQuery->where("{$this->field} $sign ?", $this->value);
		
		$sign = $this->isBefore ? '<' : '<=';
		
		$atQuery = clone $this->query;
		$atQuery
			->byField($this->field, $this->value)
			->where("{$this->idField} $sign ?", [$this->idValue]);
		
		return $beforeQuery->queryCount() + $atQuery->queryCount() + $this->offset;
	}
	
	private function getData(): array
	{
		$sign	= $this->getFieldSign(false);
		$idSign	= $this->getFieldSign($this->order != OrderBy::ASC);
		
		// Revers the order of the result when selecting the previous page.
		if ($this->isBefore)
		{
			$fieldOrder	= ($this->order == OrderBy::ASC ? OrderBy::DESC : OrderBy::ASC);
			$idOrder	= OrderBy::DESC;
		}
		else
		{
			$fieldOrder = $this->order;
			$idOrder	= OrderBy::ASC;
		}
		
		$query = clone $this->query;
		$query
			->where("{$this->field} {$sign}= ?", [$this->value])
			->having(
				"{$this->field} {$sign} ? OR {$this->idField} {$idSign} ?", 
				[$this->value, $this->idValue]
			)
			->orderBy($this->field,		$fieldOrder)
			->orderBy($this->idField,	$idOrder)
			->limit(abs($this->offset), $this->limit);
		
		return $query->query();
	}
	
	
	public function __construct(?ICmdSelect $select = null)
	{
		if ($select)
			$this->setQuery($select);
	}


	/**
	 * @param ICmdSelect $select
	 * @return PaginatedByFieldExtension|static
	 */
	public function setQuery(ICmdSelect $select): PaginatedByFieldExtension
	{
		$this->query = $select;
		return $this;
	}
	
	public function setOrder(int $order): PaginatedByFieldExtension
	{
		$this->order = ($order == OrderBy::ASC ? OrderBy::ASC : OrderBy::DESC); 
		return $this;
	}
	
	
	public function setIdField(string $field, $id): PaginatedByFieldExtension
	{
		$this->idField = $field;
		$this->idValue = $id;
		return $this;
	}
	
	public function before(string $field, $value): PaginatedByFieldExtension
	{
		$this->isBefore = true;
		$this->field = $field;
		$this->value = $value;
		return $this;
	}
	
	public function after(string $field, $value): PaginatedByFieldExtension
	{
		$this->isBefore = false;
		$this->field = $field;
		$this->value = $value;
		return $this;
	}
	
	public function setLimit(int $limit, int $offset): PaginatedByFieldExtension
	{
		$this->limit	= $limit;
		$this->offset	= $offset;
		return $this;
	}
	
	public function query(): PaginatedByFieldResult
	{
		$result = new PaginatedByFieldResult();
		
		$result->TotalCount	= $this->query->queryCount();
		$result->Offset		= $this->getOffset();
		
		$data = $this->getData();
		
		if ($this->isBefore)
		{
			$result->setData(array_reverse($data));
			$result->Offset = max(0, $result->Offset - $result->Count); 
		}
		else
		{
			$result->setData($data);
		}
		
		return $result;
	}
}