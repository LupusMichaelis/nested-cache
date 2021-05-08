<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache;

interface KeeperInterface
{
	function add_group(string $name, bool $is_persistent): void;
}
