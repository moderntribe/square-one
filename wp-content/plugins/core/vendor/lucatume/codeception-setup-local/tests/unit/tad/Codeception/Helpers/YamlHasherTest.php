<?php

use Symfony\Component\Yaml\Yaml;
use tad\Codeception\Command\Helpers\YamlHasher;
use tad\Codeception\Command\Helpers\YamlHasherInterface;

class YamlHasherTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * it should be instantiatable
     */
    public function it_should_be_instantiatable()
    {
        $sut = $this->make_instance();

        $this->assertInstanceOf('tad\Codeception\Command\Helpers\YamlHasher', $sut);
    }

    /**
     * @test
     * it should hash multiple occurrences of the var type instruction
     */
    public function it_should_hash_multiple_occurrences_of_the_var_type_instruction()
    {
        $sut = $this->make_instance();
        $contents = <<< YAML
foo:
    var:
        name: display
        question: mode?
        default: on
    var:
        if: display is on
        question: foo var value?
        default: some value
YAML;

        $output = $sut->hash($contents);
        $parsedOutput = Yaml::parse($output);

        $this->assertArrayHasKey('foo', $parsedOutput);
        $this->assertCount(2, $parsedOutput['foo']);
    }

    /**
     * @test
     * it should hash multiple instances of the the message instruction
     */
    public function it_should_hash_multiple_instances_of_the_the_message_instruction()
    {
        $sut = $this->make_instance();
        $contents = <<< YAML
foo:
    message: Some foo message
    message: Some bar message
YAML;

        $output = $sut->hash($contents);
        $parsedOutput = Yaml::parse($output);

        $this->assertArrayHasKey('foo', $parsedOutput);
        $this->assertCount(2, $parsedOutput['foo']);
    }

    /**
     * @test
     * it should hash multiple instances of the command instruction
     */
    public function it_should_hash_multiple_instances_of_the_command_instruction()
    {
        $sut = $this->make_instance();
        $contents = <<< YAML
foo:
    command: someCommand
    command: someCommand
YAML;

        $output = $sut->hash($contents);
        $parsedOutput = Yaml::parse($output);

        $this->assertArrayHasKey('foo', $parsedOutput);
        $this->assertCount(2, $parsedOutput['foo']);
    }

    /**
     * @test
     * it should hash multiple instances of the exec instruction
     */
    public function it_should_hash_multiple_instances_of_the_exec_instruction()
    {
        $sut = $this->make_instance();
        $contents = <<< YAML
foo:
    exec: someScript
    exec: someScript
YAML;

        $output = $sut->hash($contents);
        $parsedOutput = Yaml::parse($output);

        $this->assertArrayHasKey('foo', $parsedOutput);
        $this->assertCount(2, $parsedOutput['foo']);
    }

    /**
     * @test
     * it should hash conditional multiple instances of the var instruction
     */
    public function it_should_hash_conditional_multiple_instances_of_the_var_instruction()
    {
        $sut = $this->make_instance();
        $contents = <<< YAML
foo:
    var: 
        if: someScript
        name: one
        question: one?
        default: 1
    var: 
        unless: someScript
        name: two
        question: two?
        default: 2
YAML;

        $output = $sut->hash($contents);
        $parsedOutput = Yaml::parse($output);

        $this->assertArrayHasKey('foo', $parsedOutput);
        $this->assertCount(2, $parsedOutput['foo']);
    }

    /**
     * @test
     * it should hash multiple conditional instances of the message instruction
     */
    public function it_should_hash_multiple_conditional_instances_of_the_message_instruction()
    {
        $sut = $this->make_instance();
        $contents = <<< YAML
foo:
    message: 
        if: someScript
        value: some message
    message: 
        unless: someScript
        value: some message
YAML;

        $output = $sut->hash($contents);
        $parsedOutput = Yaml::parse($output);

        $this->assertArrayHasKey('foo', $parsedOutput);
        $this->assertCount(2, $parsedOutput['foo']);
    }

    /**
     * @test
     * it should hash multiple conditional instances of the command instruction
     */
    public function it_should_hash_multiple_conditional_instances_of_the_command_instruction()
    {
        $sut = $this->make_instance();
        $contents = <<< YAML
foo:
    command: 
        if: someScript
        value: someCommand
    command: 
        unless: someScript
        value: someCommand
YAML;

        $output = $sut->hash($contents);
        $parsedOutput = Yaml::parse($output);

        $this->assertArrayHasKey('foo', $parsedOutput);
        $this->assertCount(2, $parsedOutput['foo']);
    }

    /**
     * @test
     * it should hash multiple conditional instances of the exec instruction
     */
    public function it_should_hash_multiple_conditional_instances_of_the_exec_instruction()
    {
        $sut = $this->make_instance();
        $contents = <<< YAML
foo:
    exec: 
        if: one
        value: someScript
    exec: 
        unless: one
        value: someScript
YAML;

        $output = $sut->hash($contents);
        $parsedOutput = Yaml::parse($output);

        $this->assertArrayHasKey('foo', $parsedOutput);
        $this->assertCount(2, $parsedOutput['foo']);
    }

    /**
     * @return YamlHasherInterface
     */
    private function make_instance()
    {
        return new YamlHasher();
    }
}
