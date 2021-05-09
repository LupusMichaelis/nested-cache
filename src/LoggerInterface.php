<?php declare(strict_types=1);

namespace LupusMichaelis\NestedCache;

interface LoggerInterface
{
	function info(string $fmt, ...$params): void;
	function error(string $fmt, ...$params): void;
	function debug(string $fmt, ...$params): void;
}
