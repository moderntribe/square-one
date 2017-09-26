<?php
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Yaml\Yaml;
use tad\Codeception\Command\BaseCommand;
use tad\Codeception\Command\ExtendingBaseCommand;

require_once codecept_data_dir('classes/ExtendingBaseCommand.php');

class BaseCommandTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * it should be instantiatable
     */
    public function it_should_be_instantiatable()
    {
        $sut = $this->make_instance();

        $this->assertInstanceOf('tad\Codeception\Command\ExtendingBaseCommand', $sut);
    }

    /**
     * @test
     * it should not write anything to file if --save-config option is not set
     */
    public function it_should_not_write_anything_to_file_if_save_config_option_is_not_set()
    {
        $application = new Application();
        $application->add(new ExtendingBaseCommand());

        $command = $application->find('dummyExtending');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        $this->assertFileNotExists($command->getLocalConfigFilePath());
    }

    protected function setUp()
    {
        BaseCommand::_resetConfig();
    }

    /**
     * @test
     * it should save any option argument to local config file if --save-config is specified
     */
    public function it_should_save_any_option_argument_to_local_config_file_if_save_config_is_specified()
    {
        $application = new Application();
        $application->add(new ExtendingBaseCommand());

        $command = $application->find('dummyExtending');
        $commandTester = new CommandTester($command);
        $commandName = $command->getName();
        $commandTester->execute([
            'command' => $commandName,
            '--one' => 'one',
            '--two' => 2,
            '--three' => '3',
            '--save-config' => true
        ]);

        $configFile = $command->getLocalConfigFilePath();

        $this->assertFileExists($configFile);
        $config = Yaml::parse(file_get_contents($configFile));

        unlink($configFile);

        $this->assertArrayHasKey($commandName, $config);
        $commandConfig = $config[$commandName];
        $this->assertCount(3, $commandConfig);
        $this->assertArrayHasKey('one', $commandConfig);
        $this->assertEquals('one', $commandConfig['one']);
        $this->assertArrayHasKey('two', $commandConfig);
        $this->assertEquals(2, $commandConfig['two']);
        $this->assertArrayHasKey('three', $commandConfig);
        $this->assertEquals('3', $commandConfig['three']);

        $this->assertContains('configuration saved', $commandTester->getDisplay());
    }

    /**
     * @test
     * it should overwrite existing values with new ones
     */
    public function it_should_overwrite_existing_values_with_new_ones()
    {
        $application = new Application();
        $application->add(new ExtendingBaseCommand());

        $command = $application->find('dummyExtending');
        $commandName = $command->getName();

        $configFile = $command->getLocalConfigFilePath();
        $existingConfig = Yaml::dump([$commandName => ['one' => 'foo', 'two' => 'baz', 'three' => 'bar']]);
        file_put_contents($configFile, $existingConfig);

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $commandName,
            '--one' => 'one',
            '--two' => 2,
            '--three' => '3',
            '--save-config' => true
        ]);


        $this->assertFileExists($configFile);
        $config = Yaml::parse(file_get_contents($configFile));

        unlink($configFile);

        $this->assertArrayHasKey($commandName, $config);
        $commandConfig = $config[$commandName];
        $this->assertCount(3, $commandConfig);
        $this->assertArrayHasKey('one', $commandConfig);
        $this->assertEquals('one', $commandConfig['one']);
        $this->assertArrayHasKey('two', $commandConfig);
        $this->assertEquals(2, $commandConfig['two']);
        $this->assertArrayHasKey('three', $commandConfig);
        $this->assertEquals('3', $commandConfig['three']);
    }

    /**
     * @test
     * it should add new values for command without overwriting other commands settings
     */
    public function it_should_add_new_values_for_command_without_overwriting_other_commands_settings()
    {
        $application = new Application();
        $application->add(new ExtendingBaseCommand());

        $command = $application->find('dummyExtending');
        $commandName = $command->getName();

        $configFile = $command->getLocalConfigFilePath();
        $existingConfig = Yaml::dump([$commandName => ['one' => 'foo', 'two' => 'baz', 'three' => 'bar'], 'anotherCommand' => ['some' => 'value', 'foo' => 'here']]);
        file_put_contents($configFile, $existingConfig);

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $commandName,
            '--one' => 'one',
            '--two' => 2,
            '--three' => '3',
            '--save-config' => true
        ]);


        $this->assertFileExists($configFile);
        $config = Yaml::parse(file_get_contents($configFile));

        unlink($configFile);

        $this->assertArrayHasKey($commandName, $config);
        $this->assertArrayHasKey('anotherCommand', $config);

        $commandConfig = $config[$commandName];
        $this->assertCount(3, $commandConfig);
        $this->assertArrayHasKey('one', $commandConfig);
        $this->assertEquals('one', $commandConfig['one']);
        $this->assertArrayHasKey('two', $commandConfig);
        $this->assertEquals(2, $commandConfig['two']);
        $this->assertArrayHasKey('three', $commandConfig);
        $this->assertEquals('3', $commandConfig['three']);

        $anotherCommandConfig = $config['anotherCommand'];
        $this->assertCount(2, $anotherCommandConfig);
        $this->assertArrayHasKey('some', $anotherCommandConfig);
        $this->assertEquals('value', $anotherCommandConfig['some']);
        $this->assertArrayHasKey('foo', $anotherCommandConfig);
        $this->assertEquals('here', $anotherCommandConfig['foo']);
    }

    /**
     * @test
     * it should read unspecified option values from config file if present
     */
    public function it_should_read_unspecified_option_values_from_config_file_if_present()
    {
        $application = new Application();
        $application->add(new ExtendingBaseCommand());

        $command = $application->find('dummyExtending');
        $commandName = $command->getName();

        $configFile = $command->getLocalConfigFilePath();
        $existingConfig = Yaml::dump([$commandName => ['one' => 'foo', 'two' => 'baz', 'three' => 'bar']]);
        file_put_contents($configFile, $existingConfig);

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $commandName
        ]);

        unlink($configFile);

        $display = $commandTester->getDisplay();
        $this->assertContains('Option one has a value of foo', $display);
        $this->assertContains('Option two has a value of baz', $display);
        $this->assertContains('Option three has a value of bar', $display);
    }

    private function make_instance()
    {
        return new ExtendingBaseCommand();
    }
}
