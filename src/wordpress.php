<?php declare(strict_types=1);

/// Global namespace

use \LupusMichaelis\NestedCache as LMNC;

function wp_cache_init()
{
	global $wp_object_cache;

	$loader = require_once __DIR__ . '/../vendor/autoload.php';
	$wp_object_cache = new \LupusMichaelis\NestedCache\WordPress\ObjectCache;
}

function wp_cache_switch_to_blog(int $blog_id): int
{
	global $wp_object_cache;
	$args = func_get_args();
	return call_user_func_array([$wp_object_cache, 'switch_to_blog'], $args);
}

function wp_cache_add_global_groups($groups): void
{
	global $wp_object_cache;
	$args = func_get_args();
	call_user_func_array([$wp_object_cache, 'add_global_groups'], $args);
}

function wp_cache_add_non_persistent_groups($groups): void
{
	global $wp_object_cache;
	$args = func_get_args();
	call_user_func_array([$wp_object_cache, 'add_non_persistent_groups'], $args);
}

function wp_cache_get($key, string $group = LMNC\WordPress\ObjectCacheInterface::default_group_name, bool $force = false, &$found = null)
{
	global $wp_object_cache;
	$args = func_get_args();
	$args = LMNC\function_parameters_as_map(__FUNCTION__, $args);
	return call_user_func_array([$wp_object_cache, 'get'], $args);
}

function wp_cache_get_multiple(array $keys, $group = LMNC\WordPress\ObjectCacheInterface::default_group_name, bool $force = false): array
{
	global $wp_object_cache;
	$args = func_get_args();
	$args = LMNC\function_parameters_as_map(__FUNCTION__, $args);
	return call_user_func_array([$wp_object_cache, 'get_multiple'], $args);
}

function wp_cache_set($key, $data, string $group = LMNC\WordPress\ObjectCacheInterface::default_group_name, int $expires = LMNC\WordPress\ObjectCacheInterface::default_expires_in): bool

{
	global $wp_object_cache;
	$args = func_get_args();
	$args = LMNC\function_parameters_as_map(__FUNCTION__, $args);
	return call_user_func_array([$wp_object_cache, 'set'], $args);
}

function wp_cache_add($key, $data, string $group = LMNC\WordPress\ObjectCacheInterface::default_group_name, int $expires = LMNC\WordPress\ObjectCacheInterface::default_expires_in): bool
{
	global $wp_object_cache;
	$args = func_get_args();
	$args = LMNC\function_parameters_as_map(__FUNCTION__, $args);
	return call_user_func_array([$wp_object_cache, 'add'], $args);
}

function wp_cache_replace($key, $data, string $group = LMNC\WordPress\ObjectCacheInterface::default_group_name, int $expires = LMNC\WordPress\ObjectCacheInterface::default_expires_in): bool
{
	global $wp_object_cache;
	$args = func_get_args();
	$args = LMNC\function_parameters_as_map(__FUNCTION__, $args);
	return call_user_func_array([$wp_object_cache, 'replace'], $args);
}

function wp_cache_incr($key, int $bump = 1, string $group = LMNC\WordPress\ObjectCacheInterface::default_group_name)
{
	global $wp_object_cache;
	$args = func_get_args();
	$args = LMNC\function_parameters_as_map(__FUNCTION__, $args);
	return call_user_func_array([$wp_object_cache, 'incr'], $args);
}

function wp_cache_decr($key, int $bump = 1, string $group = LMNC\WordPress\ObjectCacheInterface::default_group_name)
{
	global $wp_object_cache;
	$args = func_get_args();
	$args = LMNC\function_parameters_as_map(__FUNCTION__, $args);
	return call_user_func_array([$wp_object_cache, 'decr'], $args);
}

function wp_cache_delete($key, string $group = LMNC\WordPress\ObjectCacheInterface::default_group_name): bool
{
	global $wp_object_cache;
	$args = func_get_args();
	$args = LMNC\function_parameters_as_map(__FUNCTION__, $args);
	return call_user_func_array([$wp_object_cache, 'delete'], $args);
}

function wp_cache_flush(): bool
{
	global $wp_object_cache;
	return $wp_object_cache->flush();
}

function wp_cache_close(): bool
{
	global $wp_object_cache;
	$wp_object_cache = null;
	return true;
}
