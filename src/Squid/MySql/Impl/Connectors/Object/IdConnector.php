<?php
namespace Squid\MySql\Impl\Connectors\Object;


use Squid\MySql\Connectors\Object\IIdConnector;

use Squid\MySql\Impl\Connectors\Generic\DeleteConnector;
use Squid\MySql\Impl\Connectors\Object\Identity\TIdentityDecorator;
use Squid\MySql\Impl\Connectors\Object\Primary\TIdKey;

use Squid\MySql\Impl\Connectors\Internal\Object\AbstractORMConnector;


class IdConnector extends AbstractORMConnector implements IIdConnector
{
	use TIdentityDecorator;
	use TIdKey;
	
	
	protected function getPrimaryKeys(): array
	{
		return $this->getIdKey();
	}
	
	
	public function deleteById($id)
	{
		$connector = new DeleteConnector($this);
		return $connector->byField($this->getIdField(), $id);
	}

	public function loadById($id)
	{
		$connector = new PlainObjectConnector($this);
		
		if (is_array($id))
		{
			return $connector->selectAllByFields([$this->getIdField() => $id]);
		}
		else
		{
			return $connector->selectOneByField($this->getIdField(), $id);
		}
	}
}