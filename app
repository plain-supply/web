#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use PlainSupply\Web\Console\Application;

$app = new Application();
$app->run($argv);