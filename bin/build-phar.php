#!/usr/bin/env php
<?php

$build_dir = './build';
$target = $build_dir . '/object-cache.phar';

try
{
	if(!@mkdir($build_dir, 0755, true))
		if(!is_dir($build_dir))
			throw new \exception(sprintf('Can\'t write directory \'%s\'', $build_dir));

	if(!@chmod($target, 0660))
		if(is_file($target))
			throw new \exception(sprintf('File \'%s\' can not be marked writable', $target));

	if(!@unlink($target))
		if(is_file($target))
			throw new \exception(sprintf('File \'%s\' can not be unlink', $target));

	$phar = new \Phar($target);
	$phar->startBuffering();

	foreach(['src', 'vendor'] as $included_path)
	{
		$base_to_strip = __DIR__ . '/..';
		$it = new RecursiveDirectoryIterator("$base_to_strip/$included_path");
		$it = new RecursiveIteratorIterator($it);
		$phar->buildFromIterator($it, $base_to_strip);
		unset($it);
	}

	$stub = $phar->createDefaultStub('src/wordpress.php');
	$stub = "#!/usr/bin/env php \n$stub";
	$phar->setStub($stub);

	$phar->stopBuffering();
	$phar->compressFiles(\Phar::GZ);
	unset($phar);

	if(!@chmod($target, 0550))
		throw new \exception(sprintf('Can\'t change file mod for \'%s\'', $target));
}
catch(\exception $e)
{
	die($e->getMessage() . "\n");
}
