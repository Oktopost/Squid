<?php
namespace Squid\MySql\Impl\Connectors\Object\Join\JoinConnectors;


use Objection\LiteObject;
use Objection\LiteSetup;
use PHPUnit\Framework\TestCase;
use Squid\MySql\Connectors\Object\CRUD\ID\IIdSave;
use Squid\MySql\Connectors\Object\Generic\IGenericIdConnector;
use Squid\MySql\Connectors\Object\Generic\IGenericIdentityConnector;


class ByPropertiesTest extends TestCase
{
	/** @var \PHPUnit_Framework_MockObject_MockObject|IGenericIdentityConnector */
	private $connector;
	
	
	private function subject()
	{
		$this->connector = $this->getMockBuilder(IGenericIdentityConnector::class)->getMock();
		
		$subject = new ByProperties();
		
		$subject->setProperties(
			[
				'a' => 'a',
				'b'	=> 'c'
			]);
		
		$subject->setConnector($this->connector);
		$subject->setChildPropertyFieldNames(['a' => 'a', 'c' => 'C']);
		$subject->setParentReferenceProperty('child');
		
		return $subject;
	}
	
	
	public function test_ReturnSelf()
	{
		$subject = new ByProperties();
		
		self::assertEquals($subject, $subject->setConnector('abc'));
		self::assertEquals($subject, $subject->setProperties('abc'));
		self::assertEquals($subject, $subject->setParentReferenceProperty('abc'));
		self::assertEquals($subject, $subject->setChildPropertyFieldNames('abc'));
	}
	
	
	public function test_loaded_ObjectPassed_ObjectReturned()
	{
		$subject = $this->subject();
		$this->connector->method('selectObjectsByFields')->willReturn([]);
		
		$object = new ByPropertiesParent();
		
		self::assertSame($object, $subject->loaded($object));
	}
	
	public function test_loaded_ArrayPassed_ArrayReturned()
	{
		$subject = $this->subject();
		$this->connector->method('selectObjectsByFields')->willReturn([]);
		
		$objects = [new ByPropertiesParent(), new ByPropertiesParent()];
		
		self::assertSame($objects, $subject->loaded($objects));
	}
	
	public function test_loaded_CorrectChildrenRequestSendToConnector()
	{
		$subject = $this->subject();
		
		$object = new ByPropertiesParent();
		$object->a = '123';
		$object->b = 'abc';
		
		$this->connector
			->expects($this->once())
			->method('selectObjectsByFields')
			->with(['a' => ['123'], 'C' => ['abc']])
			->willReturn([]);
		
		$subject->loaded($object);
	}
	
	public function test_loaded_ChildrenMapToParents()
	{
		$subject = $this->subject();
		
		$object = new ByPropertiesParent();
		$object->a = '123';
		$object->b = 'abc';
		
		$child = new ByPropertiesChild();
		$child->a = '123';
		$child->c = 'abc';
		
		$this->connector
			->method('selectObjectsByFields')
			->willReturn([$child]);
		
		$subject->loaded($object);
		
		self::assertEquals($child, $object->child);
	}
	
	public function test_loaded_MultipleObjectsMappedCorrectly()
	{
		$subject = $this->subject();
		
		$object1 = new ByPropertiesParent();
		$object1->a = '123';
		$object1->b = 'abc';
		
		$object2 = new ByPropertiesParent();
		$object2->a = '123';
		$object2->b = 'null';
		
		$object3 = new ByPropertiesParent();
		$object3->a = 'nnn';
		$object3->b = 'mmm';
		
		$child1 = new ByPropertiesChild();
		$child1->a = '123';
		$child1->c = 'abc';
		
		$child2 = new ByPropertiesChild();
		$child2->a = '123';
		$child2->c = 'not_found';
		
		$child3 = new ByPropertiesChild();
		$child3->a = 'nnn';
		$child3->c = 'mmm';
		
		$this->connector
			->method('selectObjectsByFields')
			->willReturn([$child3, $child2, $child1]);
		
		$subject->loaded([$object1, $object2, $object3]);
		
		self::assertSame($child1, $object1->child);
		self::assertNull($object2->child);
		self::assertSame($child3, $object3->child);
	}
	
	
	public function test_inserted_NoChildren_Return0()
	{
		$subject = $this->subject();
		
		$object1 = new ByPropertiesParent();
		$object1->a = '123';
		$object1->b = 'abc';
		
		self::assertEquals(0, $subject->inserted($object1));
	}
	
	public function test_inserted_HaveChildren_ChildrenInserted()
	{
		$subject = $this->subject();
		
		$child1 = new ByPropertiesChild();
		$child2 = new ByPropertiesChild();
		
		$object1 = new ByPropertiesParent();
		$object1->a = '123';
		$object1->b = 'abc';
		$object1->child = $child1;
		
		$object2 = new ByPropertiesParent();
		$object2->a = 'nnn';
		$object2->b = 'mmm';
		$object2->child = $child2;
		
		$this->connector
			->expects($this->once())
			->method('insertObjects')
			->with([$child1, $child2], false)
			->willReturn(2);
		
		$subject->inserted([$object1, $object2]);
	}
	
	public function test_inserted_HaveChildren_CountFromConnectorReturned()
	{
		$subject = $this->subject();
		
		$object = new ByPropertiesParent();
		$object->child = new ByPropertiesChild();
		
		$this->connector
			->method('insertObjects')
			->willReturn(123);
		
		self::assertEquals(123, $subject->inserted([$object]));
	}
	
	public function test_inserted_FalseReturnedByConnector_FalseReturned()
	{
		$subject = $this->subject();
		
		$object = new ByPropertiesParent();
		$object->child = new ByPropertiesChild();
		
		$this->connector
			->method('insertObjects')
			->willReturn(false);
		
		self::assertFalse($subject->inserted($object));
	}
	
	public function test_inserted_ChildPropertiesSet()
	{
		$subject = $this->subject();
		
		$object = new ByPropertiesParent();
		$object->a = 'ac';
		$object->b = 'dc';
		$object->child = new ByPropertiesChild();
		
		$subject->inserted([$object]);
		
		self::assertEquals($object->a, $object->child->a);
		self::assertEquals($object->b, $object->child->c);
	}
	
	
	public function test_upserted_NoChildren_Return0()
	{
		$subject = $this->subject();
		
		$object1 = new ByPropertiesParent();
		$object1->a = '123';
		$object1->b = 'abc';
		
		self::assertEquals(0, $subject->upserted($object1));
	}
	
	public function test_upserted_HaveChildren_ChildrenUpserted()
	{
		$subject = $this->subject();
		
		$child1 = new ByPropertiesChild();
		$child2 = new ByPropertiesChild();
		
		$object1 = new ByPropertiesParent();
		$object1->a = '123';
		$object1->b = 'abc';
		$object1->child = $child1;
		
		$object2 = new ByPropertiesParent();
		$object2->a = 'nnn';
		$object2->b = 'mmm';
		$object2->child = $child2;
		
		$this->connector
			->expects($this->once())
			->method('upsert')
			->with([$child1, $child2])
			->willReturn(2);
		
		$subject->upserted([$object1, $object2]);
	}
	
	public function test_upserted_HaveChildren_CountFromConnectorReturned()
	{
		$subject = $this->subject();
		
		$object = new ByPropertiesParent();
		$object->a = '123';
		$object->child = new ByPropertiesChild();
		
		$this->connector
			->method('upsert')
			->willReturn(123);
		
		self::assertEquals(123, $subject->upserted([$object]));
	}
	
	public function test_upserted_FalseReturnedByConnector_FalseReturned()
	{
		$subject = $this->subject();
		
		$object = new ByPropertiesParent();
		$object->a = '123';
		$object->child = new ByPropertiesChild();
		
		$this->connector
			->method('upsert')
			->willReturn(false);
		
		self::assertFalse($subject->upserted($object));
	}
	
	public function test_upserted_ChildPropertiesSet()
	{
		$subject = $this->subject();
		
		$object = new ByPropertiesParent();
		$object->a = 'ac';
		$object->b = 'dc';
		$object->child = new ByPropertiesChild();
		
		$subject->upserted([$object]);
		
		self::assertEquals($object->a, $object->child->a);
		self::assertEquals($object->b, $object->child->c);
	}
	
	
	public function test_updated_NoChildren_Return0()
	{
		$subject = $this->subject();
		
		$object1 = new ByPropertiesParent();
		$object1->a = '123';
		$object1->b = 'abc';
		
		self::assertEquals(0, $subject->updated($object1));
	}
	
	public function test_updated_HaveChildren_ChildrenUpdated()
	{
		$subject = $this->subject();
		
		$child1 = new ByPropertiesChild();
		
		$object1 = new ByPropertiesParent();
		$object1->a = '123';
		$object1->b = 'abc';
		$object1->child = $child1;
		
		$this->connector
			->expects($this->once())
			->method('update')
			->with($child1)
			->willReturn(2);
		
		$subject->updated($object1);
	}
	
	public function test_updated_HaveChildren_CountFromConnectorReturned()
	{
		$subject = $this->subject();
		
		$object = new ByPropertiesParent();
		$object->a = '123';
		$object->child = new ByPropertiesChild();
		
		$this->connector
			->method('update')
			->willReturn(123);
		
		self::assertEquals(123, $subject->updated($object));
	}
	
	public function test_updated_FalseReturnedByConnector_FalseReturned()
	{
		$subject = $this->subject();
		
		$object = new ByPropertiesParent();
		$object->a = '123';
		$object->child = new ByPropertiesChild();
		
		$this->connector
			->method('update')
			->willReturn(false);
		
		self::assertFalse($subject->updated($object));
	}
	
	public function test_updated_ChildPropertiesSet()
	{
		$subject = $this->subject();
		
		$object = new ByPropertiesParent();
		$object->a = 'ac';
		$object->b = 'dc';
		$object->child = new ByPropertiesChild();
		
		$subject->updated($object);
		
		self::assertEquals($object->a, $object->child->a);
		self::assertEquals($object->b, $object->child->c);
	}

	/**
	 * @expectedException \Squid\Exceptions\SquidUsageException
	 */
	public function test_save_ConnectorDoesNotHAveSaveMethod_ExceptionThrown()
	{
		$subject = $this->subject();
		
		$object = new ByPropertiesParent();
		$object->a = 'ac';
		$object->b = 'dc';
		$object->child = new ByPropertiesChild();
		
		$subject->saved($object);
	}
	
	public function test_save_NoChildren_Return0()
	{
		$connector = $this->getMockBuilder(IGenericIdConnector::class)->getMock();
		$subject = $this->subject();
		$subject->setConnector($connector);
		
		$object = new ByPropertiesParent();
		$object->a = 'ac';
		$object->b = 'dc';
		
		self::assertEquals(0, $subject->saved($object));
	}
	
	public function test_save_HaveChildren_SaveCalledForChild()
	{
		$connector = $this->getMockBuilder(IGenericIdConnector::class)->getMock();
		$subject = $this->subject();
		$subject->setConnector($connector);
		
		$object = new ByPropertiesParent();
		$object->a = 'ac';
		$object->b = 'dc';
		$object->child = new ByPropertiesChild();
		
		$connector
			->expects($this->once())
			->method('save')
			->with([$object->child]);
		
		$subject->saved($object);
	}
	
	public function test_save_HaveChildren_ContReturned()
	{
		$connector = $this->getMockBuilder(IGenericIdConnector::class)->getMock();
		$subject = $this->subject();
		$subject->setConnector($connector);
		
		$object = new ByPropertiesParent();
		$object->a = 'ac';
		$object->b = 'dc';
		$object->child = new ByPropertiesChild();
		
		$connector
			->method('save')
			->willReturn(123);
		
		self:self::assertEquals(123, $subject->saved($object));
	}
}


class ByPropertiesChild extends LiteObject
{
	/**
	 * @return array
	 */
	protected function _setup()
	{
		return [
			'a'	=> LiteSetup::createString(),
			'c' => LiteSetup::createString(),
			'd' => LiteSetup::createString(),
		];
	}
}

class ByPropertiesParent extends LiteObject
{
	/**
	 * @return array
	 */
	protected function _setup()
	{
		return [
			'a'	=> LiteSetup::createString(),
			'b' => LiteSetup::createString(),
			'child' => LiteSetup::createInstanceOf(ByPropertiesChild::class)
		];
	}
}