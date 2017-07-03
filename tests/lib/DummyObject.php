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
			'a'	=> LiteSetup::createInt(),
			'b'	=> LiteSetup::createInt(),
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