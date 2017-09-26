<?php

namespace tad\FunctionMocker\MatchingStrategy;

class MatchingStrategyFactory
{

    protected $times;
    protected $isGreaterThan;

    public static function make($times)
    {
        \Arg::_($times, 'Times')->is_int()->_or()->is_string();

        if (is_numeric($times)) {
            $times = (int)$times;

            return EqualMatchingStrategy::on($times);
        }

        $matches = array();
        if (!preg_match("/(>|>=|<|<=|==|!=)*(\\d)+/uU", $times, $matches)) {

            throw new \InvalidArgumentException('If times is a string it must follow the pattern [==|!=|<=|<|>=|>]*\d+');
        }

        $prefix = $matches[1];
        $times = (int)$matches[2];

        switch ($prefix) {
            case '>':
                $matchingStrategy = GreaterThanMatchingStrategy::on($times);
                break;
            case '>=':
                $matchingStrategy = AtLeastMatchingStrategy::on($times);
                break;
            case '==':
                $matchingStrategy = EqualMatchingStrategy::on($times);
                break;
            case '<':
                $matchingStrategy = LessThanMatchingStrategy::on($times);
                break;
            case '<=':
                $matchingStrategy = AtMostMatchingStrategy::on($times);
                break;
            case '!=':
                $matchingStrategy = NotEqualMatchingStrategy::on($times);
                break;
            default:
                $matchingStrategy = EqualMatchingStrategy::on($times);
                break;
        }

        return $matchingStrategy;
    }
}
