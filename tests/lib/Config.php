<?php
namespace lib;


class Config
{
	public static function get() 
	{
		return [
			'db'		=> '_squid_test_',
			'user'		=> 'root',
			'passowrd'	=> '',
			'host'		=> 'localhost'
		];
	}
}