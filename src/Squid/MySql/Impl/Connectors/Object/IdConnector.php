<?php
namespace Squid\MySql\Impl\Connectors\Object;


use Squid\MySql\Connectors\Object\Generic\IGenericObjectConnector;
use Squid\MySql\Connectors\Object\IIdConnector;

use Squid\MySql\Impl\Connectors\Object\Primary\TIdKey;
use Squid\MySql\Impl\Connectors\Object\Generic\GenericObjectConnector;
use Squid\MySql\Impl\Connectors\Object\Identity\TIdentityDecorator;

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
		return $this->getGenericObjectConnector()->deleteByField($this->getIdField(), $id);
	}

	public function loadById($id)
	{
		return $this->getGenericObjectConnector()->selectObjectsByFields([$this->getIdField() => $id]);
	}
}