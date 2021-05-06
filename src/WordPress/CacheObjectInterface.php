<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\WordPress;

/**
 * Supposed public interface of WP_Object_Cache
 *
 * @todo determine WordPress version this interface is compatible with
 * @see https://developer.wordpress.org/reference/classes/wp_object_cache/
 */
interface CacheObjectInterface
{
	const default_expires_in = 0; ///< int Life time in second of the key, 0 is for never
	const default_group_name = 'default'; ///< string
	const default_incrementable_floor = 0; ///< int
	const default_value = null; ///< mixed

	/**
	 * In multi-site set up, an indirection to separate keys
	 *
	 * @param $blog_id int		@see https://developer.wordpress.org/reference/functions/get_current_blog_id/
	 */
	function switch_to_blog(int $blog_id): int;

	/**
	 * Get the value
	 *
	 * @see https://developer.wordpress.org/reference/classes/wp_object_cache/get/
	 *
	 * @param $key int|string	The key we're getting the value from
	 * @param $group string		Cache group name
	 * @param $force bool		Force to next cache level
	 * @param $found bool|null	If referenced, we'll be set to true when found, false otherwise
	 *
	 * @return mixed The value in cache, self::default_value ortherwise
	 */
	function get($key, string $group = self::default_group_name, bool $force = false, &$found = null);

	/**
	 * Get many values
	 *
	 * @see https://developer.wordpress.org/reference/classes/wp_object_cache/get_multiple/
	 *
	 * @param $keys int[]|string[]
	 *
	 * @return mixed The value in cache, self::default_value ortherwise
	 */
	function get_multiple(array $keys, $group = self::default_group_name, bool $force = false): array;

	/**
	 * @see https://developer.wordpress.org/reference/classes/wp_object_cache/set/
	 *
	 * @param $key int|string
	 * @param $data mixed
	 * @param $group string
	 * @param $expires int		Time delta in second
	 *
	 * @return bool	Always true
	 */
	function set($key, $data, string $group = self::default_group_name, int $expires = self::default_expires_in): bool;

	/**
	 * @see https://developer.wordpress.org/reference/classes/wp_object_cache/add/
	 *
	 * @param $key int|string
	 * @param $data mixed
	 * @param $group string
	 * @param $expires int		Time delta in second
	 *
	 * @return bool	If they were no value for $key, the function succeeds and returns true, false otherwise
	 */
	function add($key, $data, string $group = self::default_group_name, int $expires = self::default_expires_in): bool;

	/**
	 * A global group is a cache that spans accross all blogs of this instance
	 *
	 * @see https://developer.wordpress.org/reference/classes/wp_object_cache/add_global_groups/
	 *
	 * @param $groups string|string[]
	 * @return bool
	 */
	function add_global_groups($groups): void;

	/**
	 * Specify groups that shouldn't linger passed the run
	 *
	 * @param $groups string|string[]
	 */
	function add_non_persistent_groups($groups): void;

	/**
	 * @see https://developer.wordpress.org/reference/classes/wp_object_cache/add_non_persistent_groups/
	 *
	 * @param $key int|string
	 * @param $data mixed
	 * @param $group string
	 * @param $expires int		Time delta in second
	 *
	 * @return bool	If they were a $key to replace, the function succeeds and returns true, false otherwise
	 */
	function replace($key, $data, string $group = self::default_group_name, int $expires = self::default_expires_in): bool;

	/**
	 * @see https://developer.wordpress.org/reference/classes/wp_object_cache/incr/
	 *
	 * @param $key int|string
	 * @param $bump int			How much the value should be increased
	 * @param $group string
	 *
	 * @return false|int		False on failure, the new value otherwise
	 */
	function incr($key, int $bump = 1, string $group = self::default_group_name);

	/**
	 * @see https://developer.wordpress.org/reference/classes/wp_object_cache/decr/
	 *
	 * @param $key int|string
	 * @param $bump int			How much the value should be decreased
	 * @param $group string
	 *
	 * @return false|int		False on failure, the new value otherwise
	 */
	function decr($key, int $bump = 1, string $group = self::default_group_name);

	/**
	 * @see https://developer.wordpress.org/reference/classes/wp_object_cache/delete/
	 *
	 * @param $key int|string
	 * @param $group string
	 *
	 * @return bool		Whether the key actually's been deleted
	 */
	function delete($key, string $group = self::default_group_name): bool;

	/**
	 * @see https://developer.wordpress.org/reference/classes/wp_object_cache/flush/
	 *
	 * @return bool		Whether the cache's been flushed
	 */
	function flush(): bool;

	/**
	 * @see https://developer.wordpress.org/reference/classes/wp_object_cache/close/
	 *
	 * @return bool		Whether we severed cache's connection from this run
	 */
	function close(): bool;

	/**
	 * @see https://developer.wordpress.org/reference/classes/wp_object_cache/stats/
	 *
	 * @return string	Produce a report on cache behaviour, should be HTML formatted.
	 */
	function stats(): string;
}
