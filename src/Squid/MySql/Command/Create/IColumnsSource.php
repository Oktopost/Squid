<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 27/11/16
 * Time: 07:44
 */

namespace Squid\MySql\Command\Create;


interface IColumnsSource
{
	/**
	 * @param IColumn $column
	 */
	public function add(IColumn $column);
}