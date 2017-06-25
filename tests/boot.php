<?php
use lib\DataSet;


require_once __DIR__ . '/../vendor/autoload.php';


foreach (glob(__DIR__ . '/lib/*') as $item)
{
	if (is_file($item))
	{
		/** @noinspection PhpIncludeInspection */
		require_once $item;
	}
}


DataSet::setup();