<?php
namespace Squid\MySql\Impl\Connectors\Table;


use Squid\MySql\Connectors\Generic;
use Squid\MySql\Connectors\IGenericCRUDConnector;
use Squid\MySql\Connectors\Table\ISingleTableConnector;

use Squid\MySql\Impl\Connectors\Internal\Table\AbstractSingleTableConnector;
use Squid\MySql\Impl\Connectors\Internal\Generic\CountConnector;
use Squid\MySql\Impl\Connectors\Internal\Generic\DeleteConnector;
use Squid\MySql\Impl\Connectors\Internal\Generic\InsertConnector;
use Squid\MySql\Impl\Connectors\Internal\Generic\SelectConnector;
use Squid\MySql\Impl\Connectors\Internal\Generic\UpdateConnector;
use Squid\MySql\Impl\Connectors\Internal\Generic\UpsertConnector;


class GenericConnector extends AbstractSingleTableConnector implements IGenericCRUDConnector
{
	/** @var Generic\ISelectConnector */
	private $select;
	
	/** @var Generic\IUpdateConnector */
	private $update;
	
	/** @var Generic\IDeleteConnector */
	private $delete;
	
	/** @var Generic\IUpsertConnector */
	private $upsert;
	
	/** @var Generic\IInsertConnector */
	private $insert;
	
	/** @var Generic\ICountConnector */
	private $count;


	/**
	 * @param ISingleTableConnector $item
	 * @return mixed
	 */
	private function setup(ISingleTableConnector $item)
	{
		return $item
			->setConnector($this->getConnector())
			->setTable($this->getTable());
	}
	

	public function select(): Generic\ISelectConnector
	{
		if (!$this->select)
			$this->select = $this->setup(new SelectConnector());
		
		return $this->select;
	}

	public function delete(): Generic\IDeleteConnector
	{
		if (!$this->delete)
			$this->delete = $this->setup(new DeleteConnector());
		
		return $this->delete;
	}

	public function update(): Generic\IUpdateConnector
	{
		if (!$this->update)
			$this->update = $this->setup(new UpdateConnector());
		
		return $this->update;
	}

	public function upsert(): Generic\IUpsertConnector
	{
		if (!$this->upsert)
			$this->upsert = $this->setup(new UpsertConnector());
		
		return $this->upsert;
	}

	public function insert(): Generic\IInsertConnector
	{
		if (!$this->insert)
			$this->insert = $this->setup(new InsertConnector());
		
		return $this->insert;
	}

	public function count(): Generic\ICountConnector
	{
		if (!$this->count)
			$this->count = $this->setup(new CountConnector());
		
		return $this->count;
	}
}