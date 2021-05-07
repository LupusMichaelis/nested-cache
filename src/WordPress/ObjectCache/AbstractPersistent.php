<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\WordPress\ObjectCache;

use LupusMichaelis\NestedCache\WordPress\ObjectCacheInterface;

/**
 * Implement base logic of persistent caching
 */
abstract class AbstractPersistent
	implements ObjectCacheInterface
{
	abstract public function get($key, string $group = self::default_group_name, bool $force = false, &$found = null);
	abstract public function get_multiple(array $keys, $group = self::default_group_name, bool $force = false): array;

	abstract public function set($key, $data, string $group = self::default_group_name, int $expires = self::default_expires_in): bool;
	abstract public function add($key, $data, string $group = self::default_group_name, int $expires = self::default_expires_in): bool;

	abstract public function replace($key, $data, string $group = self::default_group_name, int $expires = self::default_expires_in): bool;

	abstract public function incr($key, int $bump = 1, string $group = self::default_group_name);
	abstract public function decr($key, int $bump = 1, string $group = self::default_group_name);

	abstract public function delete($key, string $group = self::default_group_name): bool;
	abstract public function flush(): bool;

	abstract public function switch_to_blog(int $blog_id): int;
	abstract public function close(): bool;

	abstract public function stats(): string;

	public function add_global_groups($groups): void
	{
		if(!is_iterable($groups))
			$groups = (array) $groups;

		foreach($groups as $group)
			$this->global_group_list[(string) $group] = true;
	}

	public function add_non_persistent_groups($groups): void
	{
		if(!is_iterable($groups))
			$groups = (array) $groups;

		foreach($groups as $group)
			$this->non_persistent_group_list[(string) $group] = true;
	}

	private $non_persistent_group_list = []; ///< @property bool[string]
	private $global_group_list = []; ///< @property bool[string]
}
