<?php

namespace tad\Codeception\Command\SetupLocal\Instructions;


use Symfony\Component\Console\Exception\RuntimeException;

class MessageInstruction extends AbstractInstruction implements InstructionInterface
{

    public function execute()
    {
        $go = $this->getGo();

        if (!$go) {
            return $this->vars;
        }

        list($loopVarName, $loopCount) = $this->getLoopCount();

        if ($loopCount) {
            foreach ($loopCount as $loopVarValue) {
                $value = is_array($this->value) ? $this->value['value'] : $this->value;

                $message = $this->replaceVarsInString($value, [$loopVarName => $loopVarValue]);

                $this->output->writeln('<info>' . $message . '</info>');
            }
        } else {
            $value = is_array($this->value) ? $this->value['value'] : $this->value;

            $message = $this->replaceVarsInString($value);
            $this->output->writeln('<info>' . $message . '</info>');
        }

        return $this->vars;
    }


}