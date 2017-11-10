<?php

namespace Tribe\Project\CLI;

use Pimple\Container;
use JBZoo\PimpleDumper\PimpleDumper;

class Pimple_Dump extends Square_One_Command {

	private $container;

	public function __construct( Container $container ) {
		$this->container = $container;
		parent::__construct();
	}

	protected function command() {
		return 'pimple';
	}

	protected function callback() {
		return [ $this, 'dump' ];
	}

	protected function description() {
		return 'Dumps the files needed to autocomplete Pimple names to be able to use this plugin: https://plugins.jetbrains.com/plugin/7809-silex-pimple-plugin';
	}

	protected function arguments() {
		return [];
	}

	public function dump() {
		$dumper = new PimpleDumper();
		$dumper->dumpPimple( $this->container );
		$dumper->dumpPhpstorm( $this->container );
		\WP_CLI::success( 'Done!' );
	}

}