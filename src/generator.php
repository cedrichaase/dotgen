<?php

include_once __DIR__ . '/../vendor/autoload.php';

use cedrichaase\DotGen\DotfileGenerator\DotfileGenerator;

$generator = new DotfileGenerator();
$generator->renderDotfiles();