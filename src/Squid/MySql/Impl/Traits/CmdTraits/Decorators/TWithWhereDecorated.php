<?php
namespace Squid\MySql\Impl\Traits\CmdTraits\Decorators;


use Squid\MySql\Command\ICmdSelect;
use Squid\MySql\Command\IWithWhere;


trait TWithWhereDecorated
{
	protected abstract function getChild(): IWithWhere;
	
	
	public function byField($field, $value) { $this->getChild()->byField($field, $value); return $this; }
	public function byFields($fields, $values = null) { $this->getChild()->byFields($fields, $values); return $this; }
	public function whereIn($field, $values, $negate = false) { $this->getChild()->whereIn($field, $values, $negate); return $this; }
	public function whereNotIn($field, $values) { $this->getChild()->whereNotIn($field, $values); return $this; }
	public function whereExists(ICmdSelect $select, $negate = false) { $this->getChild()->whereExists($select, $negate); return $this; }
	public function whereNotExists(ICmdSelect $select) { $this->getChild()->whereNotExists($select); return $this; }
}