<?php
namespace Squid\MySql\Impl\Connectors\Objects\Identity;


trait TPrimaryKeysConsumer
{
	protected abstract function getPrimaryKeys(): array;
}