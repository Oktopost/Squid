<?php
namespace Squid\MySql\Impl\Connectors\Object\Primary;


use Squid\MySql\Connectors\Object\IIdConnector;

use Squid\MySql\Impl\Connectors\Internal\Object\AbstractORMConnector;


/**
 * @mixin AbstractORMConnector
 * @mixin IIdConnector
 */
trait TIdComposition
{
	use TIdDecorator;
	use TIdKey;
}