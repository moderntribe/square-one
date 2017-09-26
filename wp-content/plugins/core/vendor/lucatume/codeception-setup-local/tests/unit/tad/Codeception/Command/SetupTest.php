<?php

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamFile;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use tad\Codeception\Command\SearchReplace;
use tad\Codeception\Command\Setup;

class SetupTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * it should use the setup.yml file from the cwd if config file is not specified
     */
    public function it_should_use_the_setup_yml_file_from_the_cwd_if_config_file_is_not_specified()
    {
        $application = new Application();
        $application->add(new Setup());

        $command = $application->find('setup');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'command' => $command->getName(),
        ]);

        $this->assertContains('setup.yml] does not exist', $commandTester->getDisplay());
    }

    /**
     * @test
     * it should throw if specified configuration file does not exist
     */
    public function it_should_throw_if_specified_configuration_file_does_not_exist()
    {
        $dir = vfsStream::setup();

        $application = new Application();
        $application->add(new Setup());

        $command = $application->find('setup');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'command' => $command->getName(),
            'config' => $dir->url() . '/conf.yaml'
        ]);

        $this->assertRegExp('/Configuration file.*does not exist/', $commandTester->getDisplay());
    }

    /**
     * @test
     * it should allow for the .yml extension to be omitted from the configuration file name
     */
    public function it_should_allow_for_the_yml_extension_to_be_omitted_from_the_configuration_file_name()
    {
        $configFile = codecept_root_dir('local.yml');
        $configFileContent = <<< YAML
foo:
    message: Hello world!
YAML;
        file_put_contents($configFile, $configFileContent);

        $application = new Application();
        $application->add(new Setup());

        $command = $application->find('setup');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'command' => $command->getName(),
            'config' => 'local'
        ]);

        $display = $commandTester->getDisplay();
        $this->assertContains('Hello world!', $display);

        if (file_exists($configFile)) {
            unlink($configFile);
        }
    }

    /**
     * @test
     * it should allow specifying a variable in the configuration file
     */
    public function it_should_allow_specifying_a_variable_in_the_configuration_file()
    {
        $dir = vfsStream::setup();
        $configFile = new vfsStreamFile('conf.yaml');
        $configFileContent = <<< YAML
foo:
    var:
        name: first
        question: first var value?
        default: 23
    message: Var value is \$first
YAML;

        $configFile->setContent($configFileContent);
        $dir->addChild($configFile);

        $application = new Application();
        $application->add(new Setup());

        $command = $application->find('setup');
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("Test\n"));

        $commandTester->execute([
            'command' => $command->getName(),
            'config' => $dir->url() . '/conf.yaml'
        ]);

        $display = $commandTester->getDisplay();
        $this->assertContains('Configuring "foo"', $display);
        $this->assertContains('first var value? (23)', $display);
        $this->assertContains('Var value is Test', $display);
    }

    /**
     * @test
     * it should allow running commands from the configuration file
     */
    public function it_should_allow_running_commands_from_the_configuration_file()
    {
        $dir = vfsStream::setup();
        $sourceFile = new vfsStreamFile('source.txt');
        $outputFile = new vfsStreamFile('out.txt');
        $sourceFile->setContent('foo baz bar');

        $sourceFilePath = $dir->url() . '/source.txt';
        $outputFilePath = $dir->url() . '/out.txt';

        $configFile = new vfsStreamFile('conf.yaml');
        $configFileContent = <<< YAML
foo:
    var:
        name: first
        question: first var value?
        default: 23
    command: search-replace foo \$first $sourceFilePath $outputFilePath
YAML;

        $configFile->setContent($configFileContent);
        $dir->addChild($configFile);
        $dir->addChild($sourceFile);
        $dir->addChild($outputFile);

        $application = new Application();
        $application->add(new Setup());

        $command = $application->find('setup');
        $application->add(new SearchReplace());
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("hello\n"));

        $commandTester->execute([
            'command' => $command->getName(),
            'config' => $dir->url() . '/conf.yaml'
        ]);

        $display = $commandTester->getDisplay();
        $this->assertContains('Configuring "foo"', $display);
        $this->assertContains('first var value? (23)', $display);

        $this->assertStringEqualsFile($outputFilePath, 'hello baz bar');
    }

    /**
     * @test
     * it should allow executing scripts
     */
    public function it_should_allow_executing_scripts()
    {
        $dir = vfsStream::setup();
        $configFile = new vfsStreamFile('conf.yaml');
        $outputFile = __DIR__ . DIRECTORY_SEPARATOR . 'out.txt';
        $configFileContent = <<< YAML
foo:
    exec: touch {$outputFile}
YAML;

        $configFile->setContent($configFileContent);
        $dir->addChild($configFile);

        $application = new Application();
        $application->add(new Setup());

        $command = $application->find('setup');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'command' => $command->getName(),
            'config' => $dir->url() . '/conf.yaml'
        ]);

        $this->assertFileExists($outputFile);
        unlink($outputFile);
    }

    /**
     * @test
     * it should allow for vars in scripts
     */
    public function it_should_allow_for_vars_in_scripts()
    {
        $dir = vfsStream::setup();
        $configFile = new vfsStreamFile('conf.yaml');
        $outputFile = __DIR__ . DIRECTORY_SEPARATOR . 'out.txt';
        $configFileContent = <<< YAML
foo:
    var:
        name: first
        question: first var value?
        default: 12
    exec: touch {$outputFile} && echo "\$first" > {$outputFile}
YAML;

        $configFile->setContent($configFileContent);
        $dir->addChild($configFile);

        $application = new Application();
        $application->add(new Setup());

        $command = $application->find('setup');
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("hello\n"));

        $commandTester->execute([
            'command' => $command->getName(),
            'config' => $dir->url() . '/conf.yaml'
        ]);

        $this->assertFileExists($outputFile);
        // let's take the simulated input aberration into account
        $this->assertStringEqualsFile($outputFile, "hello\n");
        unlink($outputFile);
    }

    /**
     * @test
     * it should allow displaying messages conditionally
     */
    public function it_should_allow_displaying_messages_conditionally()
    {
        $dir = vfsStream::setup();
        $configFile = new vfsStreamFile('conf.yaml');
        $configFileContent = <<< YAML
foo:
    var:
        name: display
        question: mode?
        default: on
    message:
        if: display is on
        value: Now you see me
YAML;

        $configFile->setContent($configFileContent);
        $dir->addChild($configFile);

        $application = new Application();
        $application->add(new Setup());

        $command = $application->find('setup');
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("on\n"));

        $commandTester->execute([
            'command' => $command->getName(),
            'config' => $dir->url() . '/conf.yaml'
        ]);

        $display = $commandTester->getDisplay();
        $this->assertContains('mode?', $display);
        $this->assertContains('Now you see me', $display);
    }

    /**
     * @test
     * it should allow not displaying messages conditionally
     */
    public function it_should_allow_not_displaying_messages_conditionally()
    {
        $dir = vfsStream::setup();
        $configFile = new vfsStreamFile('conf.yaml');
        $configFileContent = <<< YAML
foo:
    var:
        name: display
        question: mode?
        default: on
    message:
        if: display is on
        value: Now you see me
YAML;

        $configFile->setContent($configFileContent);
        $dir->addChild($configFile);

        $application = new Application();
        $application->add(new Setup());

        $command = $application->find('setup');
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("off\n"));

        $commandTester->execute([
            'command' => $command->getName(),
            'config' => $dir->url() . '/conf.yaml'
        ]);

        $display = $commandTester->getDisplay();
        $this->assertContains('mode?', $display);
        $this->assertNotContains('Now you see me', $display);
    }

    /**
     * @test
     * it should allow to conditionally request a var
     */
    public function it_should_allow_to_conditionally_request_a_var()
    {
        $dir = vfsStream::setup();
        $configFile = new vfsStreamFile('conf.yaml');
        $configFileContent = <<< YAML
foo:
    var:
        name: display
        question: mode?
        default: on
    var:
        if: display is on
        name: foo
        question: foo var value?
        default: some value
YAML;

        $configFile->setContent($configFileContent);
        $dir->addChild($configFile);

        $application = new Application();
        $application->add(new Setup());

        $command = $application->find('setup');
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("on\n23\n"));

        $commandTester->execute([
            'command' => $command->getName(),
            'config' => $dir->url() . '/conf.yaml'
        ]);

        $display = $commandTester->getDisplay();
        $this->assertContains('foo var value?', $display);
    }

    /**
     * @test
     * it should allow to conditionally not ask for a var
     */
    public function it_should_allow_to_conditionally_not_ask_for_a_var()
    {
        $dir = vfsStream::setup();
        $configFile = new vfsStreamFile('conf.yaml');
        $configFileContent = <<< YAML
foo:
    var:
        name: display
        question: mode?
        default: on
    var:
        unless: display is on
        name: foo
        question: foo var value?
        default: some value
YAML;

        $configFile->setContent($configFileContent);
        $dir->addChild($configFile);

        $application = new Application();
        $application->add(new Setup());

        $command = $application->find('setup');
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("on\n"));

        $commandTester->execute([
            'command' => $command->getName(),
            'config' => $dir->url() . '/conf.yaml'
        ]);

        $display = $commandTester->getDisplay();
        $this->assertNotContains('foo var value?', $display);
    }

    /**
     * @test
     * it should allow to conditionally run a command
     */
    public function it_should_allow_to_conditionally_run_a_command()
    {
        $dir = vfsStream::setup();
        $sourceFile = new vfsStreamFile('source.txt');
        $outputFile = new vfsStreamFile('out.txt');
        $sourceFile->setContent('foo baz bar');

        $sourceFilePath = $dir->url() . '/source.txt';
        $outputFilePath = $dir->url() . '/out.txt';

        $configFile = new vfsStreamFile('conf.yaml');
        $configFileContent = <<< YAML
foo:
    var:
        name: first
        question: first var value?
        default: 23
    command: 
        if: first is hello
        value: search-replace foo \$first $sourceFilePath $outputFilePath
YAML;

        $configFile->setContent($configFileContent);
        $dir->addChild($configFile);
        $dir->addChild($sourceFile);
        $dir->addChild($outputFile);

        $application = new Application();
        $application->add(new Setup());

        $command = $application->find('setup');
        $application->add(new SearchReplace());
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("hello\n"));

        $commandTester->execute([
            'command' => $command->getName(),
            'config' => $dir->url() . '/conf.yaml'
        ]);

        $display = $commandTester->getDisplay();
        $this->assertContains('Configuring "foo"', $display);
        $this->assertContains('first var value? (23)', $display);

        $this->assertStringEqualsFile($outputFilePath, 'hello baz bar');
    }

    /**
     * @test
     * it should allow to conditionally not run a command
     */
    public function it_should_allow_to_conditionally_not_run_a_command()
    {
        $dir = vfsStream::setup();
        $sourceFile = new vfsStreamFile('source.txt');
        $outputFile = new vfsStreamFile('out.txt');
        $sourceFile->setContent('foo baz bar');

        $sourceFilePath = $dir->url() . '/source.txt';
        $outputFilePath = $dir->url() . '/out.txt';

        $configFile = new vfsStreamFile('conf.yaml');
        $configFileContent = <<< YAML
foo:
    var:
        name: first
        question: first var value?
        default: 23
    command: 
        unless: first is hello
        value: search-replace foo \$first $sourceFilePath $outputFilePath
YAML;

        $configFile->setContent($configFileContent);
        $dir->addChild($configFile);
        $dir->addChild($sourceFile);
        $dir->addChild($outputFile);

        $application = new Application();
        $application->add(new Setup());

        $command = $application->find('setup');
        $application->add(new SearchReplace());
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("hello\n"));

        $commandTester->execute([
            'command' => $command->getName(),
            'config' => $dir->url() . '/conf.yaml'
        ]);

        $display = $commandTester->getDisplay();
        $this->assertContains('Configuring "foo"', $display);
        $this->assertContains('first var value? (23)', $display);

        $this->assertStringNotEqualsFile($outputFilePath, 'hello baz bar');
    }

    /**
     * @test
     * it should allow to conditionally execute a script
     */
    public function it_should_allow_to_conditionally_execute_a_script()
    {
        $dir = vfsStream::setup();
        $configFile = new vfsStreamFile('conf.yaml');
        $outputFile = __DIR__ . DIRECTORY_SEPARATOR . 'out.txt';
        $configFileContent = <<< YAML
foo:
    var:
        name: first
        question: foo var value?
        default: 23
    exec: 
        if: first is hello
        value: touch {$outputFile}
YAML;

        $configFile->setContent($configFileContent);
        $dir->addChild($configFile);

        $application = new Application();
        $application->add(new Setup());

        $command = $application->find('setup');
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("hello\n"));

        $commandTester->execute([
            'command' => $command->getName(),
            'config' => $dir->url() . '/conf.yaml'
        ]);

        $this->assertFileExists($outputFile);
        unlink($outputFile);
    }

    /**
     * @test
     * it should allow to conditionally not execute a script
     */
    public function it_should_allow_to_conditionally_not_execute_a_script()
    {
        $dir = vfsStream::setup();
        $configFile = new vfsStreamFile('conf.yaml');
        $outputFile = __DIR__ . DIRECTORY_SEPARATOR . 'out.txt';
        $configFileContent = <<< YAML
foo:
    var:
        name: first
        question: foo var value?
        default: 23
    exec: 
        unless: first is hello
        value: touch {$outputFile}
YAML;

        $configFile->setContent($configFileContent);
        $dir->addChild($configFile);

        $application = new Application();
        $application->add(new Setup());

        $command = $application->find('setup');
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("hello\n"));

        $commandTester->execute([
            'command' => $command->getName(),
            'config' => $dir->url() . '/conf.yaml'
        ]);

        $this->assertFileNotExists($outputFile);
    }

    /**
     * @test
     * it should allow for int variable validation and ask until var validates
     */
    public function it_should_allow_for_int_variable_validation_and_ask_until_var_validates()
    {
        $dir = vfsStream::setup();
        $configFile = new vfsStreamFile('conf.yaml');
        $configFileContent = <<< YAML
foo:
    var:
        name: first
        question: first var value?
        validate: int
        default: 23
    message: Var value is \$first
YAML;

        $configFile->setContent($configFileContent);
        $dir->addChild($configFile);

        $application = new Application();
        $application->add(new Setup());

        $command = $application->find('setup');
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("Test\nfoo\n12\n"));

        $commandTester->execute([
            'command' => $command->getName(),
            'config' => $dir->url() . '/conf.yaml'
        ]);

        $display = $commandTester->getDisplay();
        $this->assertContains('Configuring "foo"', $display);
        $this->assertContains('first var value? (23)', $display);
        $this->assertContains('Var value is 12', $display);
    }

    /**
     * @test
     * it should allow for float variable validation and ask until var validates
     */
    public function it_should_allow_for_float_variable_validation_and_ask_until_var_validates()
    {
        $dir = vfsStream::setup();
        $configFile = new vfsStreamFile('conf.yaml');
        $configFileContent = <<< YAML
foo:
    var:
        name: first
        question: first var value?
        validate: float
        default: 23
    message: Var value is \$first
YAML;

        $configFile->setContent($configFileContent);
        $dir->addChild($configFile);

        $application = new Application();
        $application->add(new Setup());

        $command = $application->find('setup');
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("Test\nfoo\n12.5\n"));

        $commandTester->execute([
            'command' => $command->getName(),
            'config' => $dir->url() . '/conf.yaml'
        ]);

        $display = $commandTester->getDisplay();
        $this->assertContains('Configuring "foo"', $display);
        $this->assertContains('first var value? (23)', $display);
        $this->assertContains('Var value is 12.5', $display);
    }

    /**
     * @test
     * it should allow for bool variable validation and ask until var validates
     */
    public function it_should_allow_for_bool_variable_validation_and_ask_until_var_validates()
    {
        $dir = vfsStream::setup();
        $configFile = new vfsStreamFile('conf.yaml');
        $configFileContent = <<< YAML
foo:
    var:
        name: first
        question: first var value?
        validate: bool
        default: 23
    message: Var value is \$first
YAML;

        $configFile->setContent($configFileContent);
        $dir->addChild($configFile);

        $application = new Application();
        $application->add(new Setup());

        $command = $application->find('setup');
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("Test\nfoo\ntrue\n"));

        $commandTester->execute([
            'command' => $command->getName(),
            'config' => $dir->url() . '/conf.yaml'
        ]);

        $display = $commandTester->getDisplay();
        $this->assertContains('Configuring "foo"', $display);
        $this->assertContains('first var value? (23)', $display);
        $this->assertContains('Var value is true', $display);
    }

    /**
     * @test
     * it should allow for url variable validation and ask until var validates
     */
    public function it_should_allow_for_url_variable_validation_and_ask_until_var_validates()
    {
        $dir = vfsStream::setup();
        $configFile = new vfsStreamFile('conf.yaml');
        $configFileContent = <<< YAML
foo:
    var:
        name: first
        question: first var value?
        validate: url
        default: 23
    message: Var value is \$first
YAML;

        $configFile->setContent($configFileContent);
        $dir->addChild($configFile);

        $application = new Application();
        $application->add(new Setup());

        $command = $application->find('setup');
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("Test\nfoo\nhttp://wp.dev\n"));

        $commandTester->execute([
            'command' => $command->getName(),
            'config' => $dir->url() . '/conf.yaml'
        ]);

        $display = $commandTester->getDisplay();
        $this->assertContains('Configuring "foo"', $display);
        $this->assertContains('first var value? (23)', $display);
        $this->assertContains('Var value is http://wp.dev', $display);
    }

    /**
     * @test
     * it should allow for email variable validation and ask until var validates
     */
    public function it_should_allow_for_email_variable_validation_and_ask_until_var_validates()
    {
        $dir = vfsStream::setup();
        $configFile = new vfsStreamFile('conf.yaml');
        $configFileContent = <<< YAML
foo:
    var:
        name: first
        question: first var value?
        validate: email
        default: 23
    message: Var value is \$first
YAML;

        $configFile->setContent($configFileContent);
        $dir->addChild($configFile);

        $application = new Application();
        $application->add(new Setup());

        $command = $application->find('setup');
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("Test\nfoo\nluca@email.com\n"));

        $commandTester->execute([
            'command' => $command->getName(),
            'config' => $dir->url() . '/conf.yaml'
        ]);

        $display = $commandTester->getDisplay();
        $this->assertContains('Configuring "foo"', $display);
        $this->assertContains('first var value? (23)', $display);
        $this->assertContains('Var value is luca@email.com', $display);
    }

    public function yesNoUserInputProvider()
    {
        return [
            ["\n", "showMessage value is yes"],
            ["yes\n", "showMessage value is yes"],
            ["y\n", "showMessage value is yes"],
            ["Y\n", "showMessage value is yes"],
            ["YES\n", "showMessage value is yes"],
            ["Yes\n", "showMessage value is yes"],
            ["no\n", ""],
            ["n\n", ""],
            ["N\n", ""],
            ["NO\n", ""],
            ["nO\n", ""],
        ];
    }

    /**
     * @test
     * it should allow validating yes/no values
     * @dataProvider yesNoUserInputProvider
     */
    public function it_should_allow_validating_yes_no_values($userInput, $expectedMessage)
    {
        $dir = vfsStream::setup();
        $configFile = new vfsStreamFile('conf.yaml');
        $configFileContent = <<< YAML
foo:
    var:
        name: showMessage
        question: show the message?
        default: yes
        validate: yesno
    message:
        unless: showMessage is no
        value: showMessage value is \$showMessage
YAML;

        $configFile->setContent($configFileContent);
        $dir->addChild($configFile);

        $application = new Application();
        $application->add(new Setup());

        $command = $application->find('setup');
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream($userInput));

        $commandTester->execute([
            'command' => $command->getName(),
            'config' => $dir->url() . '/conf.yaml'
        ]);

        $display = $commandTester->getDisplay();
        $this->assertContains('Configuring "foo"', $display);
        $this->assertContains('show the message? (yes)', $display);
        if (!empty($expectedMessage)) {
            $this->assertContains($expectedMessage, $display);
        } else {
            $this->assertNotContains('showMessage value is', $display);
        }
    }

    /**
     * @test
     * it should support the not condition
     */
    public function it_should_support_the_not_condition()
    {
        $dir = vfsStream::setup();
        $configFile = new vfsStreamFile('conf.yaml');
        $configFileContent = <<< YAML
foo:
    var:
        name: showMessage
        question: show the message?
        default: yes
        validate: yesno
    message:
        if: showMessage not no
        value: showMessage value is \$showMessage
YAML;

        $configFile->setContent($configFileContent);
        $dir->addChild($configFile);

        $application = new Application();
        $application->add(new Setup());

        $command = $application->find('setup');
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("yes\n"));

        $commandTester->execute([
            'command' => $command->getName(),
            'config' => $dir->url() . '/conf.yaml'
        ]);

        $display = $commandTester->getDisplay();
        $this->assertContains('Configuring "foo"', $display);
        $this->assertContains('show the message? (yes)', $display);
        $this->assertContains('showMessage value is yes', $display);
    }

    /**
     * @test
     * it should allow checking if existence condition
     */
    public function it_should_allow_checking_if_existence_condition()
    {
        $dir = vfsStream::setup();
        $configFile = new vfsStreamFile('conf.yaml');
        $configFileContent = <<< YAML
foo:
    var:
        name: myName
        question: name?
    message:
        if: myName
        value: Hello \$myName!
YAML;

        $configFile->setContent($configFileContent);
        $dir->addChild($configFile);

        $application = new Application();
        $application->add(new Setup());

        $command = $application->find('setup');
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("\n"));

        $commandTester->execute([
            'command' => $command->getName(),
            'config' => $dir->url() . '/conf.yaml'
        ]);

        $display = $commandTester->getDisplay();
        $this->assertContains('Configuring "foo"', $display);
        $this->assertContains('name?', $display);
        $this->assertNotContains('Hello', $display);
    }

    /**
     * @test
     * it should allow checking positive existence condition
     */
    public function it_should_allow_checking_positive_existence_condition()
    {
        $dir = vfsStream::setup();
        $configFile = new vfsStreamFile('conf.yaml');
        $configFileContent = <<< YAML
foo:
    var:
        name: myName
        question: name?
    message:
        if: myName
        value: Hello \$myName!
YAML;

        $configFile->setContent($configFileContent);
        $dir->addChild($configFile);

        $application = new Application();
        $application->add(new Setup());

        $command = $application->find('setup');
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("Luca\n"));

        $commandTester->execute([
            'command' => $command->getName(),
            'config' => $dir->url() . '/conf.yaml'
        ]);

        $display = $commandTester->getDisplay();
        $this->assertContains('Configuring "foo"', $display);
        $this->assertContains('name?', $display);
        $this->assertContains('Hello Luca!', $display);
    }

    /**
     * @test
     * it should support loops in messages
     */
    public function it_should_support_loops_in_messages()
    {
        $dir = vfsStream::setup();
        $configFile = new vfsStreamFile('conf.yaml');
        $configFileContent = <<< YAML
foo:
    var:
        name: times
        question: times?
        validate: int
        default: 3
    message:
        if: times
        for: time in times
        value: loop \$time
YAML;

        $configFile->setContent($configFileContent);
        $dir->addChild($configFile);

        $application = new Application();
        $application->add(new Setup());

        $command = $application->find('setup');
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("3\n"));

        $commandTester->execute([
            'command' => $command->getName(),
            'config' => $dir->url() . '/conf.yaml'
        ]);

        $display = $commandTester->getDisplay();
        $this->assertContains('times?', $display);
        $this->assertContains('loop 1', $display);
        $this->assertContains('loop 2', $display);
        $this->assertContains('loop 3', $display);
    }

    /**
     * @test
     * it should support looping over user defined array of strings
     */
    public function it_should_support_looping_over_user_defined_array_of_strings()
    {
        $dir = vfsStream::setup();
        $configFile = new vfsStreamFile('conf.yaml');
        $configFileContent = <<< YAML
foo:
    message:
        for: var in foo,baz,bar
        value: var value is \$var
YAML;

        $configFile->setContent($configFileContent);
        $dir->addChild($configFile);

        $application = new Application();
        $application->add(new Setup());

        $command = $application->find('setup');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'command' => $command->getName(),
            'config' => $dir->url() . '/conf.yaml'
        ]);

        $display = $commandTester->getDisplay();
        $this->assertContains('var value is foo', $display);
        $this->assertContains('var value is bar', $display);
        $this->assertContains('var value is baz', $display);
    }

    /**
     * @test
     * it should allow looping over user defined array of numbers
     */
    public function it_should_allow_looping_over_user_defined_array_of_numbers()
    {
        $dir = vfsStream::setup();
        $configFile = new vfsStreamFile('conf.yaml');
        $configFileContent = <<< YAML
foo:
    message:
        for: var in 12,23,71
        value: var value is \$var
YAML;

        $configFile->setContent($configFileContent);
        $dir->addChild($configFile);

        $application = new Application();
        $application->add(new Setup());

        $command = $application->find('setup');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'command' => $command->getName(),
            'config' => $dir->url() . '/conf.yaml'
        ]);

        $display = $commandTester->getDisplay();
        $this->assertContains('var value is 12', $display);
        $this->assertContains('var value is 23', $display);
        $this->assertContains('var value is 71', $display);
    }

    /**
     * @test
     * it should allow looping over user defined array of strings, ints and vars
     */
    public function it_should_allow_looping_over_user_defined_array_of_strings_ints_and_vars()
    {
        $dir = vfsStream::setup();
        $configFile = new vfsStreamFile('conf.yaml');
        $configFileContent = <<< YAML
foo:
    var:
        name: varOne
        question: var one?
        validate: int
        default: 3
    message:
        for: loopVar in 45,foo,\$varOne 
        value: var value is \$loopVar
YAML;

        $configFile->setContent($configFileContent);
        $dir->addChild($configFile);

        $application = new Application();
        $application->add(new Setup());

        $command = $application->find('setup');
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("12\n"));

        $commandTester->execute([
            'command' => $command->getName(),
            'config' => $dir->url() . '/conf.yaml'
        ]);

        $display = $commandTester->getDisplay();
        $this->assertContains('var one?', $display);
        $this->assertContains('var value is 45', $display);
        $this->assertContains('var value is foo', $display);
        $this->assertContains('var value is 12', $display);
    }

    /**
     * @test
     * it should support loops in commands
     */
    public function it_should_support_loops_in_commands()
    {
        $dir = vfsStream::setup();
        $configFile = new vfsStreamFile('conf.yaml');
        $files = [
            codecept_root_dir('one.txt'),
            codecept_root_dir('two.txt'),
            codecept_root_dir('three.txt')
        ];
        $configFileContent = <<< YAML
foo:
    command:
        for: loopVar in one,two,three 
        value: search-replace foo bar \$loopVar.txt \$loopVar.foo.txt
YAML;

        foreach ($files as $file) {
            file_put_contents($file, 'Foo is foo');
        }

        $configFile->setContent($configFileContent);
        $dir->addChild($configFile);

        $application = new Application();
        $application->add(new Setup());
        $application->add(new SearchReplace());

        $command = $application->find('setup');
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("12\n"));

        $commandTester->execute([
            'command' => $command->getName(),
            'config' => $dir->url() . '/conf.yaml'
        ]);

        $expectedFiles = [
            codecept_root_dir('one.foo.txt'),
            codecept_root_dir('two.foo.txt'),
            codecept_root_dir('three.foo.txt')
        ];

        foreach ($expectedFiles as $expectedFile) {
            $this->assertFileExists($expectedFile);
            $this->assertStringEqualsFile($expectedFile, 'Foo is bar');
        }

        foreach (array_merge($files, $expectedFiles) as $file) {
            unlink($file);
        }
    }

    /**
     * @test
     * it should support loops in execs
     */
    public function it_should_support_loops_in_execs()
    {
        $dir = vfsStream::setup();
        $configFile = new vfsStreamFile('conf.yaml');
        $configFileContent = <<< YAML
foo:
    exec:
        for: loopVar in one,two,three 
        value: touch \$loopVar.txt
YAML;

        $configFile->setContent($configFileContent);
        $dir->addChild($configFile);

        $application = new Application();
        $application->add(new Setup());

        $command = $application->find('setup');
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("12\n"));

        $commandTester->execute([
            'command' => $command->getName(),
            'config' => $dir->url() . '/conf.yaml'
        ]);

        $expectedFiles = [
            codecept_root_dir('one.txt'),
            codecept_root_dir('two.txt'),
            codecept_root_dir('three.txt')
        ];

        foreach ($expectedFiles as $expectedFile) {
            $this->assertFileExists($expectedFile);
        }

        foreach ($expectedFiles as $file) {
            unlink($file);
        }
    }

    /**
     * @test
     * it should support regex validation of vars
     */
    public function it_should_support_regex_validation_of_vars()
    {
        $dir = vfsStream::setup();
        $configFile = new vfsStreamFile('conf.yaml');
        $configFileContent = <<< YAML
foo:
    var:
        name: varOne
        question: var one?
        validate: regexp
        regexp: /^foo/
    message:
        value: varOne value is \$varOne
YAML;

        $configFile->setContent($configFileContent);
        $dir->addChild($configFile);

        $application = new Application();
        $application->add(new Setup());

        $command = $application->find('setup');
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("12\nbar\nfoobar"));

        $commandTester->execute([
            'command' => $command->getName(),
            'config' => $dir->url() . '/conf.yaml'
        ]);

        $display = $commandTester->getDisplay();
        $this->assertContains('var one?', $display);
        $this->assertContains('varOne value is foobar', $display);
    }

    /**
     * @test
     * it should support the break instruction
     */
    public function it_should_support_the_break_instruction()
    {

        $dir = vfsStream::setup();
        $configFile = new vfsStreamFile('conf.yaml');
        $configFileContent = <<< YAML
foo:
    var:
        name: stop
        question: stop?
        validate: yesno
    break:
        if: stop
        value: Stopped.
    message: Should not see me.
YAML;

        $configFile->setContent($configFileContent);
        $dir->addChild($configFile);

        $application = new Application();
        $application->add(new Setup());

        $command = $application->find('setup');
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("yes\n"));

        $commandTester->execute([
            'command' => $command->getName(),
            'config' => $dir->url() . '/conf.yaml'
        ]);

        $display = $commandTester->getDisplay();
        $this->assertContains('stop?', $display);
        $this->assertContains('Stopped', $display);
        $this->assertNotContains('Should not see me', $display);
    }

    /**
     * @test
     * it should allow setting var values
     */
    public function it_should_allow_setting_var_values()
    {
        $dir = vfsStream::setup();
        $configFile = new vfsStreamFile('conf.yaml');
        $configFileContent = <<< YAML
foo:
    var:
        name: one
        value: 23
    message: One is \$one
YAML;

        $configFile->setContent($configFileContent);
        $dir->addChild($configFile);

        $application = new Application();
        $application->add(new Setup());

        $command = $application->find('setup');
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("yes\n"));

        $commandTester->execute([
            'command' => $command->getName(),
            'config' => $dir->url() . '/conf.yaml'
        ]);

        $display = $commandTester->getDisplay();
        $this->assertContains('One is 23', $display);
    }

    private function getInputStream($input)
    {
        $stream = fopen('php://memory', 'r+', false);
        fputs($stream, $input);
        rewind($stream);

        return $stream;
    }
}
