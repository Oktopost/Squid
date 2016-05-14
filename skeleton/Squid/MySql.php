<?php
use Squid\MySql\Impl\Connectors;
use Squid\MySql\Connectors\IMySqlObjectConnector;
use Squid\MySql\Connectors\IMySqlAutoIncrementConnector;

Squid::skeleton()->set(IMySqlObjectConnector::class,		Connectors\MySqlObjectConnector::class);
Squid::skeleton()->set(IMySqlAutoIncrementConnector::class,	Connectors\MySqlAutoIncrementConnector::class);
