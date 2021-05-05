<?php

namespace LupusMichaelis\NestedCache;

interface StatsInterface
{
	function increment_misses(): void;
	function increment_hits(): void;
	function get_misses(): int;
	function get_hits(): int;
}
