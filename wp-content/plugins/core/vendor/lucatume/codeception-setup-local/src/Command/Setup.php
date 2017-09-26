<?php

namespace tad\Codeception\Command;


use Codeception\CustomCommandInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use tad\Codeception\Command\Helpers\YamlHasher;
use tad\Codeception\Command\Helpers\YamlHasherInterface;
use tad\Codeception\Command\SetupLocal\Instructions\BreakInstruction;
use tad\Codeception\Command\SetupLocal\Instructions\CommandInstruction;
use tad\Codeception\Command\SetupLocal\Instructions\ExecInstruction;
use tad\Codeception\Command\SetupLocal\Instructions\MessageInstruction;
use tad\Codeception\Command\SetupLocal\Instructions\VarInstruction;

class Setup extends BaseCommand implements CustomCommandInterface {

    /**
     * @var array
     */
    protected $vars;

    /**
     * @var YamlHasherInterface
     */
    protected $yamlHasher;

    public function __construct($name = 'setup:local', YamlHasherInterface $yamlHasher = null) {
        parent::__construct($name);
        $this->yamlHasher = $yamlHasher ? $yamlHasher : new YamlHasher();
    }

    /**
     * returns the name of the command
     *
     * @return string
     */
    public static function getCommandName() {
        return 'util:setup';
    }

    protected function configure() {
        $this->vars = [];
        $this->setName('setup')
             ->setDescription('Sets up the local testing environment according to rules stored in a configuration file.')
             ->addArgument('config', InputArgument::OPTIONAL, 'If set, the specified configuration file will be used.', 'setup.yml');

        parent::configure();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return bool
     * @throws \Symfony\Component\Console\Exception\ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $configFile = $input->getArgument('config');
        if ( ! empty($configFile) && ! file_exists($configFile)) {
            $configFileFallbackCandidate = getcwd() . DIRECTORY_SEPARATOR . str_replace('.yml', '', $configFile) . '.yml';
            $configFile = file_exists($configFileFallbackCandidate) ? $configFileFallbackCandidate : false;
        }
        $configFile = empty($configFile) ? getcwd() . DIRECTORY_SEPARATOR . 'setup.yml' : $configFile;

        if ( ! file_exists($configFile)) {
            $output->writeln('<error>Configuration file [' . $configFile . '] does not exist.</error>');

            return false;
        }

        $config = Yaml::parse($this->yamlHasher->hash(file_get_contents($configFile)));

        $helper = $this->getHelper('question');

        foreach ($config as $sectionTitle => $sectionConfig) {
            $output->writeln('Configuring "' . $sectionTitle . '"');
            foreach ($sectionConfig as $key => $value) {

                $key = explode('-', $key);
                $key = $key[0];

                switch ($key) {
                    case 'var':
                        $instruction = new VarInstruction($value, $this->vars, $input, $output, $helper);
                        break;
                    case 'message':
                        $instruction = new MessageInstruction($value, $this->vars, $input, $output, $helper);
                        break;
                    case 'command';
                        $instruction = new CommandInstruction($value, $this->vars, $input, $output, $helper, $this);
                        break;
                    case 'exec':
                        $instruction = new ExecInstruction($value, $this->vars, $input, $output, $helper);
                        break;
                    case 'break':
                        $instruction = new BreakInstruction($value, $this->vars, $input, $output, $helper);
                        break;
                    default:
                        break;
                }

                if ( ! empty($instruction)) {
                    $executionResult = $instruction->execute();

                    if (false === $executionResult) {
                        break;
                    }

                    $this->vars = $executionResult;
                }
            }
        }

        parent::execute($input, $output);

        return true;
    }
}
