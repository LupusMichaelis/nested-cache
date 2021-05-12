<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache;

use \LupusMichaelis\NestedCache as LMNC;

interface SerializerInterface
{
	function cast($value);
	function uncast($value);
}
