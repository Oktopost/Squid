<?php
namespace Squid\MySql\Extensions\Enrichment;


use Squid\MySql\Command\IQuery;


interface IQueryEnrichment extends IQuery
{
	/**
	 * @param IQuery $query
	 * @return static
	 */
	public function setSource(IQuery $query);
}