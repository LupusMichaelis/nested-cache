<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache;

interface KeeperInterface
{
	function get_stats(): Stats;
}
