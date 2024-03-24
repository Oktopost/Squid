<?php
require_once __DIR__ . '/../vendor/autoload.php';


foreach (glob(__DIR__ . '/Source/*') as $item)
{
	if (is_file($item))
	{
		require_once $item;
	}
}


\SquidTest\Config::setup();
\SquidTest\TestDB::setup();