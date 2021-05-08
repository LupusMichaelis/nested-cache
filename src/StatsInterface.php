<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache;

interface StatsInterface
{
	function get_misses(): int;
	function set_misses(int $misses): void;

	function get_hits(): int;
	function set_hits(int $hist): void;
}
