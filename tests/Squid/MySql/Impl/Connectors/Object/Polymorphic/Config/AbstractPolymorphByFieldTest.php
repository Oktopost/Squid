<?php
namespace Squid\MySql\Impl\Connectors\Object\Polymorphic\Config;


use lib\DummyObject;
use lib\DummyObjectB;
use lib\SkeletonOverride;

use PHPUnit\Framework\TestCase;

use Squid\MySql\Impl\Connectors\Object\Generic\GenericObjectConnector;
use Squid\MySql\Connectors\Object\Generic\IGenericObjectConnector;


class AbstractPolymorphByFieldTest extends TestCase
{
	protected function tearDown()
	{
		SkeletonOverride::get()->clear();
	}


	/**
	 * @expectedException \Squid\Exceptions\SquidDevelopmentException
	 */
	public function test_getConnector_ConnectorForClassNotDefined_ThrowsException()
	{
		$subject = new AbstractPolymorphByFieldTestHelper(['a' => new GenericObjectConnector()], []);
		$subject->getConnector('b');
	}
	
	public function test_getConnector_ConnectorForClassFound_ConnectorReturned()
	{
		$conn = new GenericObjectConnector();
		
		$subject = new AbstractPolymorphByFieldTestHelper(['a' => $conn], []);
		self::assertSame($conn, $subject->getConnector('a'));
	}
	
	public function test_getConnector_ConnectorSetAsClassName_ConnectorResovledWithSkeleton()
	{
		$conn = new GenericObjectConnector();
		SkeletonOverride::set('abc', $conn);
		
		$subject = new AbstractPolymorphByFieldTestHelper(['a' => 'abc'], []);
		self::assertSame($conn, $subject->getConnector('a'));
	}
	
	
	/**
	 * @expectedException \Squid\Exceptions\SquidDevelopmentException
	 */
	public function test_getObjectConnector_ConnectorForClassNotDefined_ThrowsException()
	{
		$subject = new AbstractPolymorphByFieldTestHelper(['a' => new GenericObjectConnector()], []);
		$subject->getObjectConnector(new DummyObject());
	}
	
	public function test_getObjectConnector_ConnectorForClassFound_ConnectorReturned()
	{
		$conn = new GenericObjectConnector();
		
		$subject = new AbstractPolymorphByFieldTestHelper([DummyObject::class => $conn], []);
		self::assertSame($conn, $subject->getObjectConnector(new DummyObject()));
	}
	
	
	public function test_getConnectors_ConnectorsReturned()
	{
		$conn1 = new GenericObjectConnector();
		$conn2 = new GenericObjectConnector();
		
		$subject = new AbstractPolymorphByFieldTestHelper(['a' => $conn1, 'b' => $conn2], []);
		
		self::assertEquals([$conn1, $conn2], $subject->getConnectors());
	}
	
	public function test_getConnectors_CalledMoreThenOnce()
	{
		$conn1 = new GenericObjectConnector();
		$conn2 = new GenericObjectConnector();
		
		$subject = new AbstractPolymorphByFieldTestHelper(['a' => $conn1, 'b' => $conn2], []);
		
		self::assertEquals($subject->getConnectors(), $subject->getConnectors());
	}
	
	public function test_getConnectors_ConnectorSetAsClassName_ConnectorResovledWithSkeleton()
	{
		$conn = new GenericObjectConnector();
		SkeletonOverride::set('abc', $conn);
		
		$subject = new AbstractPolymorphByFieldTestHelper(['a' => 'abc'], []);
		self::assertSame([$conn], $subject->getConnectors());
	}
	
	
	public function test_sortObjectsByGroups_SingleObject_SingleObjectReturnedInItsGroup()
	{
		$subject = new AbstractPolymorphByFieldTestHelper([], []);
		$object = new DummyObject();
		
		$sorted = $subject->sortObjectsByGroups([$object]);
		
		self::assertEquals([DummyObject::class => [$object]], $sorted);
	}
	
	public function test_sortObjectsByGroups_MultipleObjectsOfSameType_SingleGroupReturned()
	{
		$subject = new AbstractPolymorphByFieldTestHelper([], []);
		$object1 = new DummyObject();
		$object2 = new DummyObject();
		
		$sorted = $subject->sortObjectsByGroups([$object1, $object2]);
		
		self::assertEquals([DummyObject::class => [$object1, $object2]], $sorted);
	}
	
	public function test_sortObjectsByGroups_MultipleObjects_CorrectGroupsReturned()
	{
		$subject = new AbstractPolymorphByFieldTestHelper([], []);
		$object1 = new DummyObject();
		$object2 = new DummyObject();
		$object3 = new DummyObjectB();
		$object4 = new DummyObject();
		$object5 = new DummyObjectB();
		
		$sorted = $subject->sortObjectsByGroups([
			$object1,
			$object2,
			$object3,
			$object4,
			$object5
		]);
		
		self::assertEquals(
			[
				DummyObject::class => [$object1, $object2, $object4],
				DummyObjectB::class => [$object3, $object5]
			],
			$sorted);
	}
	
	
	public function test_objectsIterator_SingleObjectPassed()
	{
		$subject = new AbstractPolymorphByFieldTestHelper([], []);
		$object = new DummyObject();
		
		$sorted = [];
		
		foreach ($subject->objectsIterator([$object]) as $group => $val)
		{
			$sorted[] = [$group, $val];
		}
		
		self::assertEquals([[DummyObject::class, [$object]]], $sorted);
	}
	
	public function test_objectsIterator_ObjectsOfSameTypePassed()
	{
		$subject = new AbstractPolymorphByFieldTestHelper([], []);
		$object1 = new DummyObject();
		$object2 = new DummyObject();
		
		$sorted = [];
		
		foreach ($subject->objectsIterator([$object1, $object2]) as $group => $val)
		{
			$sorted[] = [$group, $val];
		}
		
		self::assertEquals([[DummyObject::class, [$object1, $object2]]], $sorted);
	}
	
	public function test_objectsIterator_MultipleObjectsPassed()
	{
		$subject = new AbstractPolymorphByFieldTestHelper([], []);
		$object1 = new DummyObject();
		$object2 = new DummyObject();
		$object3 = new DummyObjectB();
		$object4 = new DummyObject();
		$object5 = new DummyObjectB();
		
		$sorted = []; 
		$iterator = $subject->objectsIterator([
			$object1,
			$object2,
			$object3,
			$object4,
			$object5
		]);
		
		foreach ($iterator as $group => $val)
		{
			$sorted[] = [$group, $val];
		}
		
		self::assertEquals(
			[
				[DummyObject::class,	[$object1, $object2, $object4]],
				[DummyObjectB::class,	[$object3, $object5]]
			],
			$sorted);
	}
	
	
	/**
	 * @expectedException \Squid\Exceptions\SquidDevelopmentException
	 */
	public function test_sortExpressionsByGroups_InvalidType_ExceptionThrown()
	{
		$subject = new AbstractPolymorphByFieldTestHelper([], ['a' => 123]);
		$subject->sortExpressionsByGroups(['a' => 'b']);
	}
	
	public function test_sortExpressionsByGroups_ExpressionDoesnMatchAnyGroup_ExpressionInAllGroupsReturned()
	{
		$conn = new GenericObjectConnector();
		
		$subject = new AbstractPolymorphByFieldTestHelper(['grp1' => $conn, 'grp2' => $conn], ['a' => 'b', 'c' => 'd']);
		$sorted = $subject->sortExpressionsByGroups(['exp' => 'val', 'exp2' => 'val']);
		
		self::assertEquals(
			[
				'grp1'	=> ['exp' => 'val', 'exp2' => 'val'],
				'grp2'	=> ['exp' => 'val', 'exp2' => 'val']
			],
			$sorted);
	}
	
	public function test_sortExpressionsByGroups_ArrayRule_WithUniqueValue()
	{
		$subject = new AbstractPolymorphByFieldTestHelper([], 
			[
				'fldA' => ['a' => 'grpA']
			]);
		
		$sorted = $subject->sortExpressionsByGroups(['fldA' => 'a']);
		
		self::assertEquals(
			[
				'grpA'	=> ['fldA' => ['a']]
			],
			$sorted);
	}
	
	/**
	 * @expectedException \Squid\Exceptions\SquidDevelopmentException
	 */
	public function test_sortExpressionsByGroups_ArrayRule_FiledValueNotInRuleList_ExceptionThrown()
	{
		$subject = new AbstractPolymorphByFieldTestHelper([], 
			[
				'fldA' => ['a' => 'grpA']
			]);
		
		$subject->sortExpressionsByGroups(['fldA' => 'not_in_rule']);
	}
	
	public function test_sortExpressionsByGroups_ArrayRule_EntireExpressionPassed()
	{
		$subject = new AbstractPolymorphByFieldTestHelper([], 
			[
				'fldA' => ['a' => 'grpA']
			]);
		
		$sorted = $subject->sortExpressionsByGroups(['fldA' => 'a', 'side' => [1, 2]]);
		
		self::assertEquals(
			[
				'grpA'	=> ['fldA' => ['a'], 'side' => [1, 2]]
			],
			$sorted);
	}
	
	public function test_sortExpressionsByGroups_ArrayRule_ArrayOfValuesMatchingSameGroup()
	{
		$subject = new AbstractPolymorphByFieldTestHelper([], 
			[
				'fldA' => ['a' => 'grpA', 'b' => 'grpA']
			]);
		
		$sorted = $subject->sortExpressionsByGroups(['fldA' => ['a', 'b']]);
		
		self::assertEquals(
			[
				'grpA'	=> ['fldA' => ['a', 'b']]
			],
			$sorted);
	}
	
	public function test_sortExpressionsByGroups_ArrayRule_ArrayOfValuesMatchingDifferentGroups()
	{
		$subject = new AbstractPolymorphByFieldTestHelper([], 
			[
				'fldA' => ['a' => 'grpA', 'b' => 'grpB', 'c' => 'grpA']
			]);
		
		$sorted = $subject->sortExpressionsByGroups(['fldA' => ['a', 'b', 'c'], 'fldB' => [1, 2]]);
		
		self::assertEquals(
			[
				'grpA'	=> ['fldA' => ['a', 'c'], 'fldB' => [1, 2]],
				'grpB'	=> ['fldA' => ['b'], 'fldB' => [1, 2]]
			],
			$sorted);
	}
	
	public function test_sortExpressionsByGroups_CallbackRule_CallbackCalled()
	{
		$isCalled = false;
		$subject = new AbstractPolymorphByFieldTestHelper([], 
			[
				'fldA' => 
					function(string $field, $value)
						use (&$isCalled)
					{
						$isCalled = true;
						return 'a';
					},
			]);
		
		$subject->sortExpressionsByGroups(['fldA' => 'a']);
		
		self::assertTrue($isCalled);
	}
	
	public function test_sortExpressionsByGroups_CallbackRule_SingleFieldValuePassedToCallback()
	{
		$fields = [];
		$values = [];
		
		$subject = new AbstractPolymorphByFieldTestHelper([], 
			[
				'fldA' => 
					function(string $field, $value)
						use (&$fields, &$values)
					{
						$fields[] = $field;
						$values[] = $value;
						return 'a';
					},
			]);
		
		$subject->sortExpressionsByGroups(['fldA' => 'a']);
		
		self::assertEquals(['fldA'], $fields);
		self::assertEquals(['a'], $values);
	}
	
	public function test_sortExpressionsByGroups_CallbackRule_ArrayFieldValuesPassedToCallback()
	{
		$fields = [];
		$values = [];
		
		$subject = new AbstractPolymorphByFieldTestHelper([], 
			[
				'fldA' => 
					function(string $field, $value)
						use (&$fields, &$values)
					{
						$fields[] = $field;
						$values[] = $value;
						return 'a';
					},
			]);
		
		$subject->sortExpressionsByGroups(['fldA' => ['a', 1]]);
		
		self::assertEquals(['fldA', 'fldA'], $fields);
		self::assertEquals(['a', 1], $values);
	}
	
	public function test_sortExpressionsByGroups_CallbackRule_DataSortedAccordingToCallbackResult()
	{
		$subject = new AbstractPolymorphByFieldTestHelper([], 
			[
				'fldA' => 
					function(string $field, $value)
					{
						static $count = 0;
						
						// First one will be g1
						return (($count++) % 2 == 0 ? 'g1' : 'g2');
					},
			]);
		
		$sorted = $subject->sortExpressionsByGroups(['fldA' => ['a', 'b', 'c', 'd']]);
		
		self::assertEquals(
			[
				'g1' => ['fldA' => ['a', 'c']],
				'g2' => ['fldA' => ['b', 'd']]
			],
			$sorted);
	}
	
	public function test_sortExpressionsByGroups_FirstFoundRuleUsed()
	{
		$subject = new AbstractPolymorphByFieldTestHelper([], 
			[
				'fldA' => ['a' => 'grpA'],
				'fldB' => ['b' => 'grpB'],
				'fldC' => ['c' => 'grpC']
			]);
		
		$sorted = $subject->sortExpressionsByGroups(['fldB' => 'b', 'fldC' => 'c']);
		
		self::assertEquals(
			[
				'grpB'	=> ['fldB' => ['b'], 'fldC' => 'c']
			],
			$sorted);
	}
	
	
	
	public function test_expressionsIterator_ExpressionResolveToSingleGroup()
	{
		$subject = new AbstractPolymorphByFieldTestHelper([], ['a' => ['b' => 'grp1']]);
		
		$sorted = [];
		
		foreach ($subject->expressionsIterator(['a' => 'b']) as $group => $val)
		{
			$sorted[] = [$group, $val];
		}
		
		self::assertEquals([['grp1', ['a' => ['b']]]], $sorted);
	}
	
	public function test_expressionsIterator_NumberOfExpressionsPassed()
	{
		$subject = new AbstractPolymorphByFieldTestHelper([], 
			[
				'a' => ['b' => 'grp1', 'c' => 'grp2']
			]);
		
		$sorted = [];
		
		foreach ($subject->expressionsIterator(['a' => ['b', 'c'], 'x' => 'y']) as $group => $val)
		{
			$sorted[] = [$group, $val];
		}
		
		self::assertEquals(
			[
				['grp1', ['a' => ['b'], 'x' => 'y']],
				['grp2', ['a' => ['c'], 'x' => 'y']]
			],
			$sorted);
	}
}


class AbstractPolymorphByFieldTestHelper extends AbstractPolymorphByField
{
	private $byClass;
	private $byField;
	
	
	public function __construct($byClass, $byField)
	{
		$this->byClass = $byClass;
		$this->byField = $byField;
	}
	
	
	/**
	 * @return IGenericObjectConnector[]
	 */
	protected function getConnectorsByClass(): array
	{
		return $this->byClass;
	}
	
	/**
	 * @return array
	 */
	protected function getByFieldRules(): array
	{
		return $this->byField;
	}
}