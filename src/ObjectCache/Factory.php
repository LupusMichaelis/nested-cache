<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\ObjectCache;

use LupusMichaelis\NestedCache as LMNC;

class Factory
{
	const default_options =
		[ 'persistent' =>
			[ 'class' => Apcu::class
			, 'log' => false
			]
		, 'ephemeral' =>
			[ 'class' => BareArray::class
			, 'log' => false
			]
		];

	public function __construct(array $options = [])
	{
		$this->set_options($options);
	}

	public function set_options(array $options)
	{
		foreach(array_replace_recursive(self::default_options, $options) as $target => $target_options)
			$this->set($target, $target_options);
	}

	public function set($target, array $options)
	{
		$cache_class = isset($options['class'])
			? $options['class']
			: self::default_options[$target]['class']
			;

		$is_log_enabled = isset($options['log']) && (bool)$options['log'];

		if(!\class_exists($cache_class)
			|| !\is_a($cache_class, LMNC\ObjectCacheInterface::class, true))
			throw new \Exception(sprintf('Class \'%s\' not supported', $cache_class));

		$this->invokators[$target] = function ()
			use($cache_class, $is_log_enabled)
			: LMNC\ObjectCacheInterface
		{
			$cache = new $cache_class;

			if($is_log_enabled)
				$cache = new LMNC\ObjectCache\Logger($cache);

			return $cache;
		};
	}

	public function get($target)
	{
		if(!isset($this->invokators[$target]))
			throw new \Exception(sprintf('Unknown target \'%s\'', $target));

		return ($this->invokators[$target])();
	}

	private $invokators = []; ///< @property $invokators array
}
