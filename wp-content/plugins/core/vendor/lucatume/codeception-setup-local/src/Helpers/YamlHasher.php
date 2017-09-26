<?php

namespace tad\Codeception\Command\Helpers;


class YamlHasher implements YamlHasherInterface
{

    public function hash($contents)
    {
        return preg_replace_callback("/(\\t|\\s{2,4})(var|message|command|exec|break)/uim", function ($match) {
            return $match[0] . '-' . md5(microtime());
        }, $contents);
    }
}