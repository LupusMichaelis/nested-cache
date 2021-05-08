<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache;

interface StatsInterface
{
	function get_misses(): int;
	function get_hits(): int;
}
