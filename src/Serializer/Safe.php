<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\Serializer;

use \LupusMichaelis\NestedCache as LMNC;

class Safe
	implements LMNC\SerializerInterface
{
	public function cast($value)
	{
		if(is_object($value))
		{
			$glimpse = new \ReflectionObject($value);
			if($glimpse->isCloneable())
				$value = clone $value;
			else
				$value = serialize($value);
		}

		return $value;
	}

	public function uncast($cached)
	{
		$value =
			is_string($cached)
				? @unserialize($cached)
				: $cached;

		return false === $value ? $cached : $value;
	}
}
