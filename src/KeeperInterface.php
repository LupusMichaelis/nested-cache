<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache;

interface KeeperInterface
{
	function set_persistent_cache_class(string $class_name): void;
	function add_group(string $name, bool $is_persistent): void;
}
