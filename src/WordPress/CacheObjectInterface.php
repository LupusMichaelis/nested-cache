<?php

namespace LupusMichaelis\NestedCache\WordPress;

interface CacheObjectInterface
{
	const default_group_name = 'default';
	const default_expires_in = 0;

	function __construct();
	function get($key, $group = self::default_group_name, $force = false, &$found = null);
	function get_multi($groups);

	function add($key, $data, $group = self::default_group_name, $expires = self::default_expires_in);
	function add_global_groups($groups);
	function add_non_persistent_groups($groups);

	function replace($key, $data, $group = self::default_group_name, $expires = self::default_expires_in);

	function incr($key, $bump = 1, $group = self::default_group_name);
	function decr($key, $bump = 1, $group = self::default_group_name);

	function delete($key, $group = self::default_group_name);
	function flush();

	function switch_to_blog($blog_id);
	function close();
}
