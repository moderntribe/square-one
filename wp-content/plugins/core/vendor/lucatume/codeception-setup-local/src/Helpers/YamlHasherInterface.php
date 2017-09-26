<?php

namespace tad\Codeception\Command\Helpers;

interface YamlHasherInterface
{
    public function hash($contents);
}