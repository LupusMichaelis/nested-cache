<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\Serializer;

use \LupusMichaelis\NestedCache as LMNC;

class Php
	implements LMNC\SerializerInterface
{
	public function cast($value)
	{
		return \serialize($value);
	}

	public function uncast($value)
	{
		return \unserialize($value);
	}
}
