<?php

namespace tad\Codeception\Command;


use Codeception\CustomCommandInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ExtendingBaseCommand extends BaseCommand implements CustomCommandInterface
{
    protected function configure()
    {
        $this->setName('dummyExtending');
        $this->addOption('one', null, InputOption::VALUE_OPTIONAL)
            ->addOption('two', null, InputOption::VALUE_OPTIONAL)
            ->addOption('three', null, InputOption::VALUE_OPTIONAL);
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $one = $this->getOption('one', $input);
        if ($one) {
            $output->writeln('Option one has a value of ' . $one);
        }
        $two = $this->getOption('two', $input);
        if ($two) {
            $output->writeln('Option two has a value of ' . $two);
        }
        $three = $this->getOption('three', $input);
        if ($three) {
            $output->writeln('Option three has a value of ' . $three);
        }

        parent::execute($input, $output);
    }

    /**
     * returns the name of the command
     *
     * @return string
     */
    public static function getCommandName() {
        return 'test:extending';
    }
}