<?php

namespace tests\tad\FunctionMocker;


use PHPUnit\Framework\TestCase;
use tad\FunctionMocker\FunctionMocker;

class PatchworkConfigurationTest extends TestCase {

	protected $toRemove = [];

	protected function setUp() {
		$this->removeFiles();
	}

	protected function tearDown() {
		$this->removeFiles();
	}

	/**
	 * It should write to specified destination
	 *
	 * @test
	 */
	public function should_write_to_specified_destination() {
		$configContents   = ['whitelist' => __DIR__];
		$encoded          = json_encode(FunctionMocker::getPatchworkConfiguration($configContents, __DIR__));
		$checksum         = md5($encoded);
		$configFile       = __DIR__ . '/patchwork.json';
		$checksumFile     = __DIR__ . "/pw-cs-{$checksum}.yml";
		$this->toRemove[] = $configFile;
		$this->toRemove[] = $checksumFile;

		$this->assertTrue(FunctionMocker::writePatchworkConfig($configContents, __DIR__));

		$this->assertFileExists($configFile);
		$this->assertFileExists($checksumFile);
		$this->assertJsonStringEqualsJsonFile($configFile, $encoded);
	}

	/**
	 * It should not write the configuration file again if written and matching
	 *
	 * @test
	 */
	public function should_not_write_the_configuration_file_again_if_written_and_matching() {
		$configContents   = ['whitelist' => __DIR__];
		$encoded          = json_encode(FunctionMocker::getPatchworkConfiguration($configContents, __DIR__));
		$checksum         = md5($encoded);
		$configFile       = __DIR__ . '/patchwork.json';
		$checksumFile     = __DIR__ . "/pw-cs-{$checksum}.yml";
		$this->toRemove[] = $configFile;
		$this->toRemove[] = $checksumFile;
		file_put_contents($configFile, 'foo');
		file_put_contents($checksumFile, 'bar');

		$this->assertFalse(FunctionMocker::writePatchworkConfig($configContents, __DIR__));

		$this->assertFileExists($configFile);
		$this->assertFileExists($checksumFile);
		$this->assertStringEqualsFile($configFile, 'foo');
	}

	/**
	 * It should rewrite configuration file if changed
	 *
	 * @test
	 */
	public function should_rewrite_configuration_file_if_changed() {
		$previousConfigContents = ['blacklist' => __DIR__];
		$encoded                = json_encode(FunctionMocker::getPatchworkConfiguration($previousConfigContents, __DIR__));
		$previousChecksum       = md5($encoded);
		$configFile             = __DIR__ . '/patchwork.json';
		$previousChecksumFile   = __DIR__ . "/pw-cs-{$previousChecksum}.yml";
		$this->toRemove[]       = $configFile;
		$this->toRemove[]       = $previousChecksumFile;
		file_put_contents($configFile, $encoded);
		file_put_contents($previousChecksumFile, 'bar');

		$newConfigContents = ['whitelist' => [__DIR__, dirname(__DIR__)], 'cache-path' => dirname(__DIR__)];
		$this->assertTrue(FunctionMocker::writePatchworkConfig($newConfigContents, __DIR__));

		$this->assertFileExists($configFile);
		$this->assertFileNotExists($previousChecksumFile);
		$newChecksum     = md5(json_encode(FunctionMocker::getPatchworkConfiguration($newConfigContents, __DIR__)));
		$newChecksumFile = __DIR__ . "/pw-cs-{$newChecksum}.yml";
		$this->assertFileExists($newChecksumFile);
	}

	protected function removeFiles() {
		foreach ($this->toRemove as $item) {
			if (!file_exists($item)) {
				continue;
			}
			if (is_dir($item)) {
				`rmdir -rf $item`;
			}
			unlink($item);
		}
	}

}
