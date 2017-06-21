<?php
namespace Squid\MySql\Connectors\Object\CRUD;


interface IObjectCRUD extends
	IObjectInsert,
	IObjectDelete,
	IObjectUpdate,
	IObjectUpsert
{
	
}