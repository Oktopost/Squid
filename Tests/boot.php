<?php
use lib\DataSet;


require_once __DIR__ . '/../vendor/autoload.php';

foreach (glob(__DIR__ . '/lib/*') as $item)
{
	if (is_file($item))
	{
		require_once $item;
	}
}

try {
    DataSet::setup();
} catch (\Throwable $e) {
    echo "ERROR during DataSet setup: " . $e->getMessage() . "\n";
    echo "In file: " . $e->getFile() . " at line " . $e->getLine() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}