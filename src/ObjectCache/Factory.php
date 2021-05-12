<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache\ObjectCache;

use LupusMichaelis\NestedCache as LMNC;

class Factory
{
	const default_options =
		[ 'cache_list' =>
			[ 'persistent' =>
				[ 'class' => Apcu::class
				, 'log' => false
				, 'serializer' => 'php'
				]
			, 'ephemeral' =>
				[ 'class' => BareArray::class
				, 'log' => false
				, 'serializer' => 'php'
				]
			]
		, 'serializer_list' =>
			[ 'php' =>
				[ 'class' => LMNC\Serializer\Php::class
				]
			, 'noop' =>
				[ 'class' => LMNC\Serializer\Noop::class
				]
			]
		, 'default_serializer' => 'noop'
		];

	public function __construct(array $options = self::default_options)
	{
		$this->set_options($options);
	}

	public function set_options(array $options)
	{
		foreach(['serializer_list', 'cache_list'] as $option_entry)
			if(isset($options[$option_entry]))
				$this->{"set_$option_entry"}($options[$option_entry]);
			else
				$this->{"set_$option_entry"}(self::default_options[$option_entry]);
	}

	public function set_cache_list(array $cache_list)
	{
		foreach(array_replace_recursive(self::default_options['cache_list'], $cache_list) as $target => $target_options)
			$this->add_cache($target, $target_options);
	}

	private function get_serializer_for_target_from_options_or_default($target, array $options): string
	{
		if(isset($options['serializer']))
			$serializer_name = $options['serializer'];
		else
			if(isset(self::default_options['cache_list'][$target]['serializer']))
				$serializer_name = self::default_options['cache_list'][$target]['serializer'];
			else
				$serializer_name = self::default_options['default_serializer'];

		return $serializer_name;
	}

	public function add_cache($target, array $options)
	{
		$cache_class = isset($options['class'])
			? $options['class']
			: self::default_options['cache_list'][$target]['class']
			;

		$serializer_name = $this->get_serializer_for_target_from_options_or_default($target, $options);

		if(!isset($this->serializer_list[$serializer_name]))
			throw new \Exception(sprintf('Serializer \'%s\' not supported', $serializer_name));

		$is_log_enabled = isset($options['log']) && (bool)$options['log'];

		if(!\class_exists($cache_class)
			|| !\is_a($cache_class, LMNC\ObjectCacheInterface::class, true))
			throw new \Exception(sprintf('Class \'%s\' not supported', $cache_class));

		$this->invokator_list[$target] = function ()
			use($cache_class, $is_log_enabled)
			: LMNC\ObjectCacheInterface
		{
			$cache = new $cache_class;

			if($is_log_enabled)
				$cache = new LMNC\ObjectCache\Logger($cache);

			return $cache;
		};
	}

	public function get_cache($target)
	{
		if(!isset($this->invokator_list[$target]))
			throw new \Exception(sprintf('Unknown target \'%s\'', $target));

		return ($this->invokator_list[$target])();
	}

	public function set_serializer_list(array $serializer_list)
	{
		foreach(array_replace_recursive(self::default_options['serializer_list'], $serializer_list) as $target => $target_options)
			$this->add_serializer($target, $target_options);
	}

	public function add_serializer(string $target, array $target_options)
	{
		$this->serializer_list[$target] = new $target_options['class'];
	}

	private $invokator_list = []; ///< @property $invokator_list array
	private $serializer_list = []; ///< @property $serializer_list array
}
