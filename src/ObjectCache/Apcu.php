<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\ObjectCache;

use \LupusMichaelis\NestedCache as LMNC;

class Apcu
	implements LMNC\ObjectCacheInterface
{
	public function __construct()
	{
		if(!apcu_enabled())
			throw new \Exception('ACPU not enabled');

		$this->stats = new LMNC\Stats\Apcu;
	}

	public function get_stats(): LMNC\StatsInterface
	{
		$this->stats->refresh();
		return $this->stats;
	}

	public function get(LMNC\Key\Cut $key)
	{
		$success = false;
		$value = \apcu_fetch("$key", $success);

		if(false === $success)
			throw new LMNC\NotFound($key);

		return $value;
	}

	public function set(LMNC\Key\Cut $key, $value): void
	{
		$success = \apcu_store("$key", is_scalar($value) ? $value : clone $value);

		if(false === $success)
			throw new \Exception(sprintf('Error occured on setting \'%s\'', $key));
	}

	public function add(LMNC\Key\Cut $key, $value): void
	{
		$key = (string) $key;
		$success = \apcu_add($key, is_scalar($value) ? $value : clone $value);

		if(false === $success)
			throw new LMNC\AlreadyCached($key);
	}

	public function replace(LMNC\Key\Cut $key, $value): void
	{
		if(!apcu_exits("$key"))
			throw new LMNC\NotFound($key);

		/// @todo Race condition: if the value is deleted in between, the replace contract will be broken

		$this->set($key, $value);
	}

	public function increment(LMNC\Key\Cut $key, int $bump): int
	{
		$success = false;
		$value = \apcu_inc("$key", $bump, $success);

		if(false === $success)
		{
			$value = LMNC\WordPress\ObjectCacheInterface::default_incrementable_floor;
			$this->set($key, $value);
		}

		return $value;
	}

	public function decrement(LMNC\Key\Cut $key, int $bump): int
	{
		$success = false;
		$value = \apcu_dec("$key", $bump, $success);

		if(false === $success)
		{
			$value = LMNC\WordPress\ObjectCacheInterface::default_incrementable_floor;
			$this->set($key, $value);
		}

		return $value;
	}

	public function delete(LMNC\Key\Cut $key): void
	{
		apcu_delete("$key");
	}

	public function flush(): void
	{
		apcu_clear_cache();
	}

	private $stats; ///< @property \LupusMichaelis\NestedCache\StatInterface
}
