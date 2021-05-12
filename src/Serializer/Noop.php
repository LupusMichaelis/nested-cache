<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\Serializer;

use \LupusMichaelis\NestedCache as LMNC;

class Noop
	implements LMNC\SerializerInterface
{
	public function cast($value)
	{
		return $value;
	}

	public function uncast($value)
	{
		return $value;
	}
}
