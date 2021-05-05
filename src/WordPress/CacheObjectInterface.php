<?php

declare(strict_types=1);

namespace LupusMichaelis\NestedCache\WordPress;

interface CacheObjectInterface
{
	const default_expires_in = 0;
	const default_group_name = 'default';
	const default_incrementable_floor = 0;
	const default_value = null;

	function __construct();
	function get($key, string $group = self::default_group_name, bool $force = false, &$found = null);
	function get_multiple(array $keys, $group = self::default_group_name, bool $force = false): array;

	function set($key, $data, string $group = self::default_group_name, int $expires = self::default_expires_in): bool;
	function add($key, $data, string $group = self::default_group_name, int $expires = self::default_expires_in): bool;

	/**
	 * A global group is a cache that spans accross all blogs of this instance
	 *
	 * @param strng|string[]
	 * @return bool
	 */
	function add_global_groups($groups);

	/**
	 * Specify groups that shouldn't linger passed the end of this run
	 *
	 * @param strng|string[]
	 */
	function add_non_persistent_groups($groups);

	function replace($key, $data, string $group = self::default_group_name, int $expires = self::default_expires_in): bool;

	function incr($key, int $bump = 1, string $group = self::default_group_name);
	function decr($key, int $bump = 1, string $group = self::default_group_name);

	function delete($key, string $group = self::default_group_name): bool;
	function flush(): bool;

	function switch_to_blog(int $blog_id): int;
	function close(): bool;
}
