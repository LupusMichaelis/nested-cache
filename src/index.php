<?php declare(strict_types=1);

#wp_cache_init();

#var_dump($wp_object_cache);

function wp_cache_init()
{
	global $wp_object_cache;

	$loader = require_once __DIR__ . '/../vendor/autoload.php';
	$wp_object_cache = new \LupusMichaelis\NestedCache\WordPress\ObjectCache\BareArray;
}

function wp_cache_switch_to_blog()
{
	global $wp_object_cache;
	$args = func_get_args();
	return call_user_func_array([$wp_object_cache, 'switch_to_blog'], $args);
}

function wp_cache_add_global_groups()
{
	global $wp_object_cache;
	$args = func_get_args();
	return call_user_func_array([$wp_object_cache, 'add_global_groups'], $args);
}

function wp_cache_add_non_persistent_groups()
{
	global $wp_object_cache;
	$args = func_get_args();
	return call_user_func_array([$wp_object_cache, 'add_non_persistent_groups'], $args);
}

function wp_cache_get()
{
	global $wp_object_cache;
	$args = func_get_args();
	return call_user_func_array([$wp_object_cache, 'get'], $args);
}

function wp_cache_get_multi()
{
	global $wp_object_cache;
	$args = func_get_args();
	return call_user_func_array([$wp_object_cache, 'get_multiple'], $args);
}

function wp_cache_set()
{
	global $wp_object_cache;
	$args = func_get_args();
	return call_user_func_array([$wp_object_cache, 'set'], $args);
}

function wp_cache_add()
{
	global $wp_object_cache;
	$args = func_get_args();
	return call_user_func_array([$wp_object_cache, 'add'], $args);
}

function wp_cache_replace()
{
	global $wp_object_cache;
	$args = func_get_args();
	return call_user_func_array([$wp_object_cache, 'replace'], $args);
}

function wp_cache_incr()
{
	global $wp_object_cache;
	$args = func_get_args();
	return call_user_func_array([$wp_object_cache, 'incr'], $args);
}

function wp_cache_decr()
{
	global $wp_object_cache;
	$args = func_get_args();
	return call_user_func_array([$wp_object_cache, 'decr'], $args);
}

function wp_cache_delete()
{
	global $wp_object_cache;
	$args = func_get_args();
	return call_user_func_array([$wp_object_cache, 'delete'], $args);
}

function wp_cache_flush()
{
	global $wp_object_cache;
	return $wp_object_cache->flush();
}

function wp_cache_close()
{
	global $wp_object_cache;
	return $wp_object_cache->close();
}
