<?php
namespace Squid\Tests;


use Squid\MySql\Command\ICmdSelect;


/**
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @mixin \PHPUnit\Framework\TestCase
 */
trait TMySqlTestCase
{
	public function select(): ICmdSelect
	{
		return MySqlTestConnection::requireTestConnector()->select();
	}
	
	public function table(array $fields, array $data = []): TestTable
	{
		$t = TestTable::get(fields: $fields);
		$t->insertData($data);
		return $t;
	}
}