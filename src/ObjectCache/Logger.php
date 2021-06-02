<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\ObjectCache;

use \LupusMichaelis\NestedCache as LMNC;

class Logger
	implements LMNC\ObjectCacheInterface
{
	const default_name = 'cache-logger';

	public function __construct(LMNC\ObjectCacheInterface $monitored)
	{
		$this->monitored = $monitored;
		$this->monitor = new LMNC\Logger;
	}

	public function set_name(string $name): void
	{
		$this->name = $name;
	}

	public function get_name(): string
	{
		return $this->name;
	}

	private function cast($any)
	{
		return serialize($any);
	}

	private function log($file, $line, $class, $func, $fmt = '', ...$args)
	{
		$args = array_map([$this, 'cast'], $args);

		$this->monitor
			->at($file, $line)
			->debug("%s::%s $fmt", $class, $func, ...$args);
	}

	public function get_stats(): LMNC\StatsInterface
	{
		$this->log(__FILE__, __LINE__, __CLASS__, __FUNCTION__);

		return $this->monitored->get_stats();
	}

	public function get(LMNC\Key\Cut $key)
	{
		$this->log(__FILE__, __LINE__, __CLASS__, __FUNCTION__, 'key(%s)', "$key");
		return $this->monitored->get($key);
	}

	public function set(LMNC\Key\Cut $key, $value, int $expires_in): void
	{
		$this->log(__FILE__, __LINE__, __CLASS__, __FUNCTION__, 'key(%s) value(%s) expires_in (%d)'
			, "$key", $value, $expires_in);
		$this->monitored->set($key, $value, $expires_in);
	}

	public function add(LMNC\Key\Cut $key, $value, int $expires_in): void
	{
		$this->log(__FILE__, __LINE__, __CLASS__, __FUNCTION__, 'key(%s) value(%s)', "$key", $value);
		$this->monitored->add($key, $value, $expires_in);
	}

	public function replace(LMNC\Key\Cut $key, $value, int $expires_in): void
	{
		$this->log(__FILE__, __LINE__, __CLASS__, __FUNCTION__, 'key(%s) value(%s)', "$key", $value);
		$this->monitored->replace($key, $value, $expires_in);
	}

	public function delete(LMNC\Key\Cut $key): void
	{
		$this->log(__FILE__, __LINE__, __CLASS__, __FUNCTION__, 'key(%s)', "$key");
		$this->monitored->delete($key);
	}

	public function increment(LMNC\Key\Cut $key, int $bump): int
	{
		$this->log(__FILE__, __LINE__, __CLASS__, __FUNCTION__, 'key(%s) bump(%s)', "$key", "$bump");
		return $this->monitored->increment($key, $bump);
	}

	public function decrement(LMNC\Key\Cut $key, int $bump): int
	{
		$this->log(__FILE__, __LINE__, __CLASS__, __FUNCTION__, 'key(%s) bump(%s)', "$key", "$bump");
		return $this->monitored->decrement($key, $bump);
	}

	public function flush(): void
	{
		$this->log(__FILE__, __LINE__, __CLASS__, __FUNCTION__);
		$this->monitored->flush();
	}

	private $name = self::default_name; ///< @property string
	private $monitored; ///< @property LMNC\ObjectCacheInterface
}
