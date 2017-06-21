<?php
namespace Squid\MySql\Connectors;


use Squid\MySql\Connectors\Generic;


interface IGenericCRUDConnector
{
	public function select(): Generic\ISelectConnector;
	public function delete(): Generic\IDeleteConnector;
	public function update(): Generic\IUpdateConnector;
	public function upsert(): Generic\IUpsertConnector;
	public function insert(): Generic\IInsertConnector;
	public function count(): Generic\ICountConnector;
}