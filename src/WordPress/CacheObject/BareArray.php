<?php

namespace LupusMichaelis\NestedCache\WordPress\CacheObject;
use LupusMichaelis\NestedCache\WordPress\CacheObjectInterface;

class BareArray
	implements CacheObjectInterface
{
	const default_incrementable_floor = 0;
	const default_value = null;

	public function __construct()
	{
	}

	public function get($key, string $group = self::default_group_name, bool $force = false, &$found = null)
	{
		return $this->get_value_or_default($group, $key, self::default_value, $found);
	}

	public function get_multi(string $groups)
	{
	}

	public function add($key, $data, string $group = self::default_group_name, int $expires = self::default_expires_in): bool
	{
		$cache = $this->get_cache_for_group($group);

		if(isset($cache[$key]))
			return false;

		$cache[$key] = is_scalar($data) ? $data : clone $data;

		return true;
	}

	public function add_global_groups(array $groups): bool
	{
	}

	public function add_non_persistent_groups(array $groups): bool
	{
	}

	public function replace($key, $data, string $group = self::default_group_name, int $expires = self::default_expires_in): bool
	{
	}

	public function incr($key, int $bump = 1, string $group = self::default_group_name)
	{
		$value = $this->get_value_or_default($group, $key, self::default_incrementable_floor);
		$value += $bump;

		$cache = $this->get_cache_for_group($group);
		return $cache[$key] = $value;
	}

	public function decr($key, int $bump = 1, string $group = self::default_group_name)
	{
		$value = $this->get_value_or_default($group, $key, self::default_incrementable_floor);
		$value -= $bump;

		$cache = $this->get_cache_for_group($group);
		return $cache[$key] = max(self::default_incrementable_floor, $value);
	}

	public function delete($key, string $group = self::default_group_name): bool
	{
		$cache = $this->get_cache_for_group($group);
		unset($cache[$key]);
		return true;
	}

	public function flush(): bool
	{
		$this->cache = [];
		return true;
	}

	public function switch_to_blog(int $blog_id): int
	{
		return $this->blog_id = $blog_id;
	}

	public function close(): bool
	{
		return $this->flush();
	}

	private function get_cache_for_group(string $group)
	{
		if(empty($group))
			$group = self::default_group_name;

		if(!isset($this->cache[$this->blog_id]))
			$this->cache[$this->blog_id] = [];

		if(!isset($this->cache[$this->blog_id][$group]))
			// Use \ArrayObject to avoid reference problems with bare array
			$this->cache[$this->blog_id][$group] = new \ArrayObject;

		return $this->cache[$this->blog_id][$group];
	}

	private function get_value_or_default(string $group, $key, $default, bool &$found = null)
	{
		$cache = $this->get_cache_for_group($group);
		$found = isset($cache[$key]);
		return $found ? $cache[$key] : $default;
	}

	private $blog_id = 0; ///< @property int $blog_id
	private $cache = []; ///< @property array $cache
}
