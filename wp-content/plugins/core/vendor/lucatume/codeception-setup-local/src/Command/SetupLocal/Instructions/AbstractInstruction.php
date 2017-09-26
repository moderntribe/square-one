<?php

namespace tad\Codeception\Command\SetupLocal\Instructions;


use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractInstruction
{
    /**
     * @var array
     */
    protected $vars;
    /**
     * @var string|array
     */
    protected $value;

    /**
     * @var Input
     */
    protected $input;

    /**
     * @var Output
     */
    protected $output;

    /**
     * @var mixed
     */
    protected $helper;

    public function __construct($value, array $vars, InputInterface $input, OutputInterface $output, $helper)
    {
        $this->value = $value;
        $this->vars = $vars;
        $this->input = $input;
        $this->output = $output;
        $this->helper = $helper;
    }

    protected function getGo()
    {
        if (!is_array($this->value)) {
            return true;
        }

        $ifCondition = isset($this->value['if']);
        $unlessCondition = isset($this->value['unless']);

        if ($ifCondition || $unlessCondition) {
            $conditionLine = $ifCondition ? $this->value['if'] : $this->value['unless'];
            $condition = explode(' ', $conditionLine);
            if (count($condition) === 3) {
                $go = $condition[1] === 'is' ? $this->vars[$condition[0]] === $condition[2] : $this->vars[$condition[0]] !== $condition[2];
            } else if (count($condition) === 1) {
                $go = !empty($this->vars[$condition[0]]);
            }

            $go = $unlessCondition ? !$go : $go;

            return $go;
        }

        return true;
    }

    protected function replaceVarsInString($string, array $additionalVars = [])
    {
        $vars = !empty($additionalVars) ? array_merge($this->vars, $additionalVars) : $this->vars;
        array_walk($vars, function ($value, $key) use (&$string) {
            $string = str_replace('$' . $key, $value, $string);
        });
        return $string;
    }

    protected function getLoopCount()
    {
        if (!(is_array($this->value) && isset($this->value['for']))) {
            return 0;
        }

        $loopFrags = explode(' ', $this->value['for']);

        if (count($loopFrags) !== 3) {
            throw new RuntimeException('Loop argument should have the format "<varName> in <loopVarName>|[comma,separated,elements]"');
        }

        $loopVarName = $loopFrags[0];

        if (!is_string($loopVarName)) {
            throw new RuntimeException('Var [' . $loopVarName . '] is not defined.');
        }

        $isLoopInValid = 'in' === $loopFrags[1];
        if (!$isLoopInValid) {
            throw new RuntimeException('Loop argument var separator second sub-argument should have the format " in "');
        }

        $loopingOnVar = $this->validateVarString($loopFrags[2]);
        if (!($loopingOnVar || $this->validateArrayString($loopFrags[2]))) {
            throw new RuntimeException('Loop argument third sub-argument should either refer a variable in the format "$varName" or be a comma separated list of elements in the format "foo,baz,bar"');
        }

        if ($loopingOnVar) {
            $loopingVarName = $loopFrags[2];

            $loopingVarValue = $this->vars[$loopingVarName];
            if (!is_numeric($loopingVarValue)) {
                throw new RuntimeException('Loop argument third sub-argument [' . $loopFrags[2] . '] is not an integer variable.');
            }

            return [$loopVarName, range(1, intval($loopingVarValue))];
        }

        return [$loopVarName, array_map([$this, 'replaceVarsInString'], array_map('trim', explode(',', $loopFrags[2])))];
    }

    /**
     * @param $varString
     * @return int
     */
    protected function validateVarString($varString)
    {
        return is_string($varString) && isset($this->vars[$varString]);
    }

    protected function validateArrayString($arrayString)
    {
        return preg_match('/([-_A-Za-z0-9]+)+,*/', $arrayString);
    }
}