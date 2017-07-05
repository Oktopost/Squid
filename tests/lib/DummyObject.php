<?php
namespace lib;


use Objection\LiteObject;
use Objection\LiteSetup;


class DummyObject extends LiteObject
{
	/**
	 * @return array
	 */
	protected function _setup()
	{
		return [
			'a'	=> LiteSetup::createString(null),
			'b'	=> LiteSetup::createString()
		];
	}
	
	
	public function __construct(array $data = [])
	{
		parent::__construct();
		
		if ($data)
		{
			$this->fromArray($data);
		}
	}
}

class DummyObjectB extends LiteObject
{
	/**
	 * @return array
	 */
	protected function _setup()
	{
		return [
			'a'	=> LiteSetup::createString(null),
			'b'	=> LiteSetup::createString(),
			'c'	=> LiteSetup::createString()
		];
	}
	
	
	public function __construct(array $data = [])
	{
		parent::__construct();
		
		if ($data)
		{
			$this->fromArray($data);
		}
	}
}