<?php
namespace Squid\MySql\Utils;


class ClassName
{
	use \Objection\TStaticClass;


	/**
	 * @param string $fullClassName
	 * @return string
	 */
	public static function getClassNameOnly($fullClassName)
	{
		$lastDash = strrpos($fullClassName, '\\');
		
		if ($lastDash === false)
		{
			return $fullClassName;
		}
		else
		{
			return substr($fullClassName, $lastDash + 1);
		}
	}
}