<?php
namespace Squid\MySql\Impl\Connectors\Extensions\Select;


use PHPUnit\Framework\TestCase;
use Squid\MySql\Command\ICmdSelect;
use Squid\MySql\Impl\Command\CmdSelect;
use Squid\MySql\Impl\Connectors\Utils\Select\SelectDecorator;
use Squid\MySql\IMySqlConnector;
use Squid\OrderBy;


class SelectDecoratorTest extends TestCase
{
	/** @var \PHPUnit_Framework_MockObject_MockObject|ICmdSelect */
	private $select;
	
	
	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject|IMySqlConnector
	 */
	private function mockIMySqlConnector(): IMySqlConnector
	{
		$connector = $this->getMockBuilder(IMySqlConnector::class)->getMock();
		$this->select = $this->getMockBuilder(ICmdSelect::class)->getMock();
		$connector->method('select')->willReturn($this->select);
		return $connector;
	}
	
	private function subject(): SelectDecorator
	{
		$subject = new SelectDecorator();
		$subject->setConnector($this->mockIMySqlConnector());
		return $subject;
	}
	
	
	public function test_ReturnSelf()
	{
		$select = new CmdSelect(); 
		$subject = new SelectDecorator();
		
		self::assertSame($subject, $subject->setConnector($this->mockIMySqlConnector()));
		
		self::assertSame($subject, $subject->distinct());
		self::assertSame($subject, $subject->from('a'));
		self::assertSame($subject, $subject->join('a', 'b', 'c'));
		self::assertSame($subject, $subject->leftJoin('a', 'b', 'c'));
		self::assertSame($subject, $subject->rightJoin('a', 'b', 'c'));
		self::assertSame($subject, $subject->groupBy('a'));
		self::assertSame($subject, $subject->having('b'));
		self::assertSame($subject, $subject->union($select));
		self::assertSame($subject, $subject->unionAll($select));
		self::assertSame($subject, $subject->where('a'));
		self::assertSame($subject, $subject->limit(1, 2));
		self::assertSame($subject, $subject->withRollup());
		self::assertSame($subject, $subject->forUpdate());
		self::assertSame($subject, $subject->lockInShareMode());
		
		
		// WHERE
		self::assertSame($subject, $subject->byField('a', 'b'));
		self::assertSame($subject, $subject->byFields(['a' => 'b']));
		self::assertSame($subject, $subject->whereIn('a', [1]));
		self::assertSame($subject, $subject->whereNotIn('a', [1]));
		self::assertSame($subject, $subject->whereExists($select));
		self::assertSame($subject, $subject->whereNotExists($select));
		
		// LIMIT
		self::assertSame($subject, $subject->limitBy(1));
		self::assertSame($subject, $subject->page(1, 2));
		self::assertSame($subject, $subject->orderBy('a'));
		
		// COLUMN
		self::assertSame($subject, $subject->column('a'));
		self::assertSame($subject, $subject->columns(['a']));
		self::assertSame($subject, $subject->columnsExp('a'));
		self::assertSame($subject, $subject->columnAs('a', 'b'));
		self::assertSame($subject, $subject->columnAsExp('a', 'b', $bind = []));
	}
	
	
	private function assertMethod(string $method, array $paramsCollection)
	{
		foreach ($paramsCollection as $params)
		{
			if ((!is_array($params)))
				$params = [$params];
			
			$subject = $this->subject();
			$this->select
				->expects($this->once())
				->method($method)
				->with(...$params);
			
			$subject->$method(...$params);
		}
	}
	
	private function assertMethodRecalls(string $method, string $expected, array $paramsCollection)
	{
		foreach ($paramsCollection as [$call, $get])
		{
			$subject = $this->subject();
			$this->select
				->expects($this->once())
				->method($expected)
				->with(...$get);
			
			$subject->$method(...$call);
		}
	}
	
	
	public function test_distinct()
	{
		$this->assertMethod('distinct', [true, false]);
	}
	
	public function test_from()
	{
		$this->assertMethod('from', [['a', true], ['b', false]]);
	}
	
	public function test_join()
	{
		$this->assertMethod('join', [
			['a', 'b', 'c', true], 
			['1', '2', '3', false]
		]);
	}
	
	public function test_leftJoin()
	{
		$this->assertMethod('leftJoin', [
			['a', 'b', 'c', true, true], 
			['1', '2', '3', false, false]
		]);
	}
	
	public function test_rightJoin()
	{
		$this->assertMethod('rightJoin', [
			['a', 'b', 'c', true, true], 
			['1', '2', '3', false, false]
		]);
	}
	
	public function test_groupBy()
	{
		$this->assertMethod('groupBy', [
			['a', true], 
			['1', false]
		]);
	}
	
	public function test_having()
	{
		$this->assertMethod('having', [
			['a', true], 
			['1', false]
		]);
	}
	
	public function test_union()
	{
		$select = new CmdSelect();
		$this->assertMethod('union', [
			[$select, true], 
			[$select, false]
		]);
	}
	
	public function test_unionAll()
	{
		$select = new CmdSelect();
		$this->assertMethod('union', [$select]);
	}
	
	public function test_where()
	{
		$this->assertMethod('where', [
			['a'],
			['b', [1, 2]]
		]);
	}
	
	public function test_limit()
	{
		$this->assertMethod('limit', [
			[1, 2]
		]);
	}
	
	public function test_withRollup()
	{
		$this->assertMethod('withRollup', [true, false]);
	}
	
	public function test_forUpdate()
	{
		$this->assertMethod('forUpdate', [true, false]);
	}
	
	public function test_lockInShareMode()
	{
		$this->assertMethod('forUpdate', [true, false]);
	}
	
	
	public function test_byField()
	{
		$this->assertMethodRecalls('byField', 'byField', [
			[['a', 1], ['a', 1]],
			[['a', [1]], ['a', [1]]],
			[['a', [1, 2]], ['a', [1, 2]]]
		]);
	}
	
	public function test_byFields()
	{
		$this->assertMethodRecalls('byFields', 'byFields', [
			[
				[['a' => 1]], 
				[['a' => 1]]
			],
			[
				[['a'], [1]],
				[['a'], [1]]
			]
		]);
	}
	
	public function test_whereIn()
	{
		$this->assertMethodRecalls('whereIn', 'whereIn', [
			[
				['a', [1, 2, 3]], 
				['a', [1, 2, 3]]
			]
		]);
	}
	
	public function test_whereExists()
	{
		$select = new CmdSelect();
		$this->assertMethodRecalls('whereExists', 'whereExists', [
			[
				[$select], 
				[$select]
			],
			[
				[$select, true], 
				[$select, true]
			]
		]);
	}
	
	public function test_whereNotExists()
	{
		$select = new CmdSelect();
		$this->assertMethodRecalls('whereNotExists', 'whereNotExists', [
			[
				[$select], 
				[$select]
			]
		]);
	}
	
	public function test_limitBy()
	{
		$this->assertMethodRecalls('limitBy', 'limit', [
			[
				[1], 
				[0, 1]
			]
		]);
	}
	
	public function test_page()
	{
		$this->assertMethodRecalls('page', 'limit', [
			[
				[3, 4], 
				[12, 4]
			]
		]);
	}
	
	public function test_orderBy()
	{
		$this->assertMethodRecalls('orderBy', 'orderBy', [
			[
				['a'], 
				[['a']]
			],
			[
				['a', OrderBy::DESC],
				[['a DESC']]
			],
			[
				[['a', 'b'], OrderBy::DESC],
				[['a DESC', 'b DESC']]
			],
			[
				[['a', 'b'], OrderBy::DESC],
				[['a DESC', 'b DESC']]
			]
		]);
	}
	
	public function test_column()
	{
		$this->assertMethodRecalls('column', 'columnsExp', [
			[
				['a'], 
				[['a']]
			],
			[
				['a', 'b'], 
				[['a', 'b']]
			]
		]);
	}
	
	public function test_columns()
	{
		$this->assertMethodRecalls('columns', 'columnsExp', [
			[
				['a'], 
				[['a']]
			],
			[
				[['a', 'b']], 
				[['a', 'b']]
			]
		]);
	}
	
	public function test_columnsExp()
	{
		$this->assertMethodRecalls('columnsExp', 'columnsExp', [
			[
				['a'], 
				[['a']]
			],
			[
				[['a', 'b']], 
				[['a', 'b']]
			],
			[
				[['a', 'b'], 1], 
				[['a', 'b'], 1]
			],
			[
				[['a', 'b'], [1, 2]], 
				[['a', 'b'], [1, 2]]
			]
		]);
	}
	
	public function test_columnAs()
	{
		$this->assertMethodRecalls('columnAs', 'columnsExp', [
			[
				['a', 'b'], 
				[['a as b']]
			]
		]);
	}
	
	public function test_columnAsExp()
	{
		$this->assertMethodRecalls('columnAsExp', 'columnsExp', [
			[
				['a', 'b'], 
				[['a as b']]
			],
			[
				['a', 'b', 1], 
				[['a as b'], 1]
			],
			[
				['a', 'b', [1]], 
				[['a as b'], [1]]
			]
		]);
	}
}