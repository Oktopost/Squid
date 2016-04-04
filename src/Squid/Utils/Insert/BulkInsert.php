<?php
namespace Squid\Utils\Insert;


use Squid\MySql\Command\ICmdInsert;


class BulkInsert {
	
	private $maxChunkSize	= 50;
	private $currChunkSize	= 0;
	
	/** @var ICmdInsert */
	private $insertTemplate;
	
	/** @var ICmdInsert|null */
	private $insert;
	
	
	/**
	 * @param ICmdInsert $insert
	 */
	public function setInsert(ICmdInsert $insert) {
		$this->insertTemplate = $insert;
	}
	
	/**
	 * @param int $size
	 */
	public function setMaxChunkSize($size) {
		$this->maxChunkSize = $size;
	}
	
	/**
	 * @return bool
	 */
	public function flush() {
		if (!$this->insert) {
			return true;
		}
		
		$insert = $this->insert;
		
		$this->currChunkSize = 0;
		$this->insert = null;
		
		return $insert->executeDml();
	}
	
	/**
	 * Insert single row.
	 * @param array $values
	 * @return static
	 */
	public function values(array $values) {
		if (!$this->insert) {
			$this->insert = clone $this->insertTemplate;
		}
		
		$this->currChunkSize++;
		$this->insert->values($values);
		
		if ($this->currChunkSize >= $this->maxChunkSize) {
			$this->flush();
		}
		
		return $this;
	}
	
	/**
	 * Append a number of rows at once.
	 * @param array $valuesSet 
	 * @return static
	 */
	public function valuesSet(array $valuesSet) {
		foreach ($valuesSet as $row) {
			$this->values($row);
		}
		
		return $this;
	}
}