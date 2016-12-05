<?php
namespace lib;


class Config
{
	public static function get() 
	{
		return [
			'db'		=> '_squid_test_',
			'user'		=> '_squid_test_u_',
			'password'	=> '_squid_test_pass_',
			'host'		=> 'localhost'
		];
	}
}