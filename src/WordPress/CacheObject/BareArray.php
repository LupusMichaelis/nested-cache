<?php

namespace LupusMichaelis\NestedCache\WordPress\CacheObject;
use LupusMichaelis\NestedCache\WordPress\CacheObjectInterface;

class BareArray
	implements CacheObjectInterface
{
	public function __construct()
	{
	}

	public function get($key, $group = self::default_group_name, $force = false, &$found = null)
	{
	}

	public function get_multi($groups)
	{
	}

	public function add($key, $data, $group = self::default_group_name, $expires = self::default_expires_in)
	{
	}

	public function add_global_groups($groups)
	{
	}

	public function add_non_persistent_groups($groups)
	{
	}

	public function replace($key, $data, $group = self::default_group_name, $expires = self::default_expires_in)
	{
	}

	public function incr($key, $bump = 1, $group = self::default_group_name)
	{
		return isset($this->cache[$key])
			? $this->cache[$key] += $bump
			: $this->cache[$key] = $bump
			;
	}

	public function decr($key, $bump = 1, $group = self::default_group_name)
	{
	}

	public function delete($key, $group = self::default_group_name)
	{
	}

	public function flush()
	{
	}

	public function switch_to_blog($blog_id)
	{
	}

	public function close()
	{
	}

	private $cache = [];
}
