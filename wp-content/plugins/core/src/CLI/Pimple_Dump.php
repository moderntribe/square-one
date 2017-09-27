<?php

namespace Tribe\Project\CLI;

use Pimple\Container;
use JBZoo\PimpleDumper\PimpleDumper;

class Pimple_Dump extends \WP_CLI_Command {

	private $container;

	public function __construct( Container $container ) {
		$this->container = $container;
		parent::__construct();
	}

	/**
	 * Dumps the files needed to autocomplete Pimple names
	 * to be able to use this plugin: https://plugins.jetbrains.com/plugin/7809-silex-pimple-plugin
	 *
	 * ## EXAMPLES
	 *
	 *     wp pimple dump
	 *
	 * @when after_wp_load
	 */
	public function dump() {
		$dumper = new PimpleDumper();
		$dumper->dumpPimple( $this->container );
		$dumper->dumpPhpstorm( $this->container );
		\WP_CLI::success( 'Done!' );
	}

}