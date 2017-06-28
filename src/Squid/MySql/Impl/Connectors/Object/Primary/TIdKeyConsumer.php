<?php
namespace Squid\MySql\Impl\Connectors\Object\Primary;


trait TIdKeyConsumer
{
	protected abstract function getIdKey(): array;
}