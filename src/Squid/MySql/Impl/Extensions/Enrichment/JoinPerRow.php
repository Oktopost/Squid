<?php
namespace Squid\MySql\Impl\Extensions\Enrichment;


use Squid\MySql\Command\ICmdSelect;
use Squid\MySql\Extensions\Enrichment\AbstractQueryEnrichment;


class JoinPerRow extends AbstractQueryEnrichment
{
	private $sourceColumn;
	private $targetColumn;
	private $asColumn;
	
	/** @var ICmdSelect */
	private $subQuery;
	
	
	/**
	 * @param array $data
	 * @param bool $isAssoc
	 * @return mixed
	 */
	private function enrichData(array $data, $isAssoc)
	{
		$keysMap = [];
		
		foreach ($data as $index => &$row)
		{
			$key = $row[$this->sourceColumn];
			
			if (!isset($keysMap[$key]))
			{
				$keysMap[$row[$this->sourceColumn]] = [$index];
			}
			else
			{
				$keysMap[$row[$this->sourceColumn]][] = $index;
			}
			
			$row[$this->asColumn] = [];
		}
		
		if (!$data)
			return $data;
		
		$ids = array_keys($keysMap);
		$query = clone $this->subQuery;
		
		$query->whereIn($this->targetColumn, $ids);
		
		$query->queryWithCallback(
			function($row)
			use (&$data, $keysMap)
			{
				$mappedKey = $row[$this->targetColumn];
				
				if (!isset($keysMap[$mappedKey]))
					return;
				
				foreach ($keysMap[$mappedKey] as $index)
				{
					$data[$index][$this->asColumn][] = $row;
				}
			},
			$isAssoc
		);
		
		return $data;
	}
	
	
	/**
	 * @param ICmdSelect $select
	 * @return static
	 */
	public function setSubQuery(ICmdSelect $select)
	{
		$this->subQuery = $select;
		return $this;
	}
	
	/**
	 * @param string $sourceColumn
	 * @param string $subQueryColumn
	 * @return static
	 */
	public function on($sourceColumn, $subQueryColumn)
	{
		$this->sourceColumn = $sourceColumn;
		$this->targetColumn = $subQueryColumn;
		return $this;
	}
	
	/**
	 * @param string $name
	 * @return static
	 */
	public function asColumn($name)
	{
		$this->asColumn = $name;
		return $this;
	}
	
	/**
	 * @return array
	 */
	public function queryNumeric()
	{
		$data = $this->getSource()->queryNumeric();
		
		if ($data !== false)
		{
			$data = $this->enrichData($data, false);
		}
		
		return $data;
	}
	
	/**
	 * @param bool $isAssoc
	 * @return array
	 */
	public function queryAll($isAssoc = false)
	{
		$data = $this->getSource()->queryAll($isAssoc);
		
		if ($data !== false)
		{
			$data = $this->enrichData($data, $isAssoc);
		}
		
		return $data;
	}
	
	/**
	 * @param bool|int $isAssoc Will accept \PDO::FETCH_*
	 * @param bool $expectOne
	 * @return array|false
	 */
	public function queryRow($isAssoc = false, $expectOne = true)
	{
		$row = $this->getSource()->queryRow($isAssoc, $expectOne);
		
		if ($row !== false)
		{
			$data = $this->enrichData([$row], $isAssoc);
			
			if ($data)
			{
				$row = $data[0];
			}
		}
		
		return $row;
	}
}