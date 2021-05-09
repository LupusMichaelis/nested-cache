<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache;

class Logger
	implements LoggerInterface
{
	public function at(string $filename, int $line)
	{
		return new class($filename, $line) extends Logger
		{
			private $filename;
			private $line;

			public function __construct(string $filename, int $line)
			{
				$this->line = $line;
				$this->filename = $filename;
			}

			protected function message(string $fmt, ...$params): string
			{
				$fmt = "{$this->filename}:{$this->line}:{$fmt}";
				return parent::message($fmt, ...$params);
			}

		};
	}

	public function info(string $fmt, ...$params): void
	{
		\trigger_error($this->message($fmt, ...$params), \E_USER_NOTICE);

	}

	public function debug(string $fmt, ...$params): void
	{
		\error_log($this->message($fmt, ...$params));
	}

	public function error(string $fmt, ...$params): void
	{
		\trigger_error($this->message($fmt, ...$params), \E_USER_ERROR);
	}

	protected function warning(string $fmt, ...$params): string
	{
		\trigger_error($this->message($fmt, ...$params), \E_USER_WARNING);
	}

	protected function message(string $fmt, ...$params): string
	{
		return \vsprintf($fmt, $params);
	}
}
