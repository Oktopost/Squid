<?php
require_once __DIR__ . '/../vendor/autoload.php';


spl_autoload_register(
	function($name) 
	{
		if (strpos($name, 'lib/') === 0) 
		{
			/** @noinspection PhpIncludeInspection */
			require_once __DIR__ . '/' . str_replace('/', '\\', $name) . '.php';
		}
	}
);