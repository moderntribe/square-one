<?php
namespace tad\Codeception\Command\SetupLocal\Instructions;

class BreakInstruction extends AbstractInstruction implements InstructionInterface
{

    public function execute()
    {
        $go = $this->getGo();

        if (!$go) {
            return $this->vars;
        }

        if (isset($this->value['value'])) {
            $this->output->writeln('<info>' . $this->value['value'] . '</info>');
        }

        return false;
    }
}