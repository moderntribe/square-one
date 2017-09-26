<?php
/**
 * Created by PhpStorm.
 * User: Luca
 * Date: 09/11/14
 * Time: 07:58
 */

namespace tad\FunctionMocker\MatchingStrategy;


interface MatchingStrategyInterface
{

    public static function on($times);

    public function matches($times);
}
