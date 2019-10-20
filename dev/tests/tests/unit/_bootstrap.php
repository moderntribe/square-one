<?php
use tad\FunctionMocker\FunctionMocker;

\Codeception\Util\Autoload::addNamespace('Tribe\\Project', __DIR__ . '/../../../../wp-content/plugins/core/src');

FunctionMocker::init();
