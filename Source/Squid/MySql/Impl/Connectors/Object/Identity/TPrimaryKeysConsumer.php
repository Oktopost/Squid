<?php
namespace Squid\MySql\Impl\Connectors\Object\Identity;


trait TPrimaryKeysConsumer
{
	protected abstract function getPrimaryKeys(): array;
}