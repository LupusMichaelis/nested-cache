<?php

declare(strict_types=1);

namespace LupusMichaelis\NestedCache\WordPress;

interface CacheObjectInterface
{
	const default_group_name = 'default';
	const default_expires_in = 0;

	function __construct();
	function get($key, string $group = self::default_group_name, bool $force = false, &$found = null);
	function get_multi(string $groups);

	function add($key, $data, string $group = self::default_group_name, int $expires = self::default_expires_in): bool;
	function add_global_groups(array $groups): bool;
	function add_non_persistent_groups(array $groups): bool;

	function replace($key, $data, string $group = self::default_group_name, int $expires = self::default_expires_in): bool;

	function incr($key, int $bump = 1, string $group = self::default_group_name);
	function decr($key, int $bump = 1, string $group = self::default_group_name);

	function delete($key, string $group = self::default_group_name): bool;
	function flush(): bool;

	function switch_to_blog(int $blog_id): int;
	function close(): bool;
}
