<?php

namespace Tribe\Project\CLI;

use Pimple\Container;
use JBZoo\PimpleDumper\PimpleDumper;

class Pimple_Dump extends Command {

	private $container;

	public function __construct( Container $container ) {
		$this->container = $container;
		parent::__construct();
	}

	protected function command() {
		return 'pimple';
	}

	protected function description() {
		return 'Dumps the files needed to autocomplete Pimple names to be able to use this plugin: https://plugins.jetbrains.com/plugin/7809-silex-pimple-plugin';
	}

	protected function arguments() {
		return [];
	}

	public function run_command( $args, $assoc_args ) {
		$dumper = new PimpleDumper();
		$dumper->dumpPimple( $this->container );
		$dumper->dumpPhpstorm( $this->container );
		\WP_CLI::success( 'Done!' );
	}

}
