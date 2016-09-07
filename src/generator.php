<?php

include_once __DIR__ . '/../vendor/autoload.php';

use cedrichaase\DotGen\DotGen;

$gen = new DotGen($argv[1]);
$gen->generate();