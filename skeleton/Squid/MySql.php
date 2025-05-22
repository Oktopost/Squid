<?php
use Squid\MySql\Impl\Connectors\Internal;
use Squid\MySql\Connectors\IMySqlObjectConnector;
use Squid\MySql\Connectors\IMySqlAutoIncrementConnector;

Squid::skeleton()->set(IMySqlObjectConnector::class,		\Squid\MySql\Impl\Connectors\MySqlObjectConnector::class);
Squid::skeleton()->set(IMySqlAutoIncrementConnector::class,	\Squid\MySql\Impl\Connectors\MySqlAutoIncrementConnector::class);
