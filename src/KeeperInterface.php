<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache;

interface KeeperInterface
{
	function get_stats(): Stats;
	function add_group(string $name, bool $is_persistent): void;

	function get(Key\Cut $key);
	function set(Key\Cut $key, $value): void;
	function add(Key\Cut $key, $value): void;
	function replace(Key\Cut $key, $value): void;
	function delete(Key\Cut $key): void;

	function increment(Key\Cut $key, int $bump): int;
	function decrement(Key\Cut $key, int $bump): int;

	function flush(): void;
}
