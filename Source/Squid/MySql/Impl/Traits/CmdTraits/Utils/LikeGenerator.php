<?php
namespace Squid\MySql\Impl\Traits\CmdTraits\Utils;


use Squid\Exceptions\SquidException;
use Squid\MySql\Command\IWithWhere;
use Structura\Strings;
use Traitor\TStaticClass;


class LikeGenerator
{
	use TStaticClass;
	
	
	private static function escapeLikeString(string $value, string $escape): string
	{
		return str_replace([
				$escape,
				'%',
				'_'
			],
			[
				"{$escape}{$escape}",
				"{$escape}%",
				"{$escape}_"
			],
			$value);
	}
	
	private static function getEscapeString(string $escapeChar): string
	{
		if ($escapeChar == "'" || $escapeChar == '\\')
			$escapeChar = "\\$escapeChar";
				
		return "ESCAPE '$escapeChar'";
	}
	
	private static function getEscapeCharacter(?string $escape, string $default): ?string
	{
		if ($escape == '') 
		{
			return null;
		}
		else if ($escape)
		{
			if (strlen($escape) > 1)
			{
				throw new SquidException("Escape character must be of size 1, got: '$escape'");
			}
			
			return $escape;
		}
		else
		{
			return $default;
		}
	}
	
	public static function generateLike(
		IWithWhere $cmd,
		string $defaultEscapeChar,
		string $exp, 
		string $likeString, 
		?string $escape,
		$value)
	{
		$value		= (string)$value;
		$command	= "$exp $likeString ?";
		$escapeChar	= self::getEscapeCharacter($escape, $defaultEscapeChar);
		
		if ($escapeChar && Strings::contains($value, $escapeChar))
		{
			$command .= ' ' . self::getEscapeString($escapeChar);
		}
		
		$cmd->where($command, $value);
	}
	
	public static function generateEscapedLike(
		IWithWhere $cmd,
		string $escapeChar,
		string $exp, 
		string $prefix, 
		string $value,
		string $suffix, 
		bool $negate = false
	)
	{
		$like = $negate ? 'NOT LIKE' : 'LIKE';
		
		if (!Strings::contains($value, '_') && !Strings::contains($value, '%'))
		{
			$cmd->where("$exp $like ?", $value);
		}
		else
		{
			$escapeString = self::getEscapeString($escapeChar);
			
			$value = self::escapeLikeString($value, $escapeChar);
			$value = $prefix . $value . $suffix;
			
			$cmd->where("$exp $like ? $escapeString", $value);
		}
	}
}