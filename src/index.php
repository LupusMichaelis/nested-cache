<?php declare(strict_types=1);

$loader = require_once __DIR__ . '/../vendor/autoload.php';
$stats = new \LupusMichaelis\NestedCache\Stats;
$stats = new \LupusMichaelis\NestedCache\Stats\Json($stats);
echo json_encode($stats);
