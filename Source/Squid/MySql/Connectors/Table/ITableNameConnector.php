<?php
namespace Squid\MySql\Connectors\Table;


use Squid\MySql\Command;


interface ITableNameConnector
{
	public function select(?string $alias = null): Command\ICmdSelect;
	public function update(): Command\ICmdUpdate;
	public function insert(): Command\ICmdInsert;
	public function delete(): Command\ICmdDelete;
	public function upsert(): Command\ICmdUpsert;
	public function name(): string;
	public function __toString();
}