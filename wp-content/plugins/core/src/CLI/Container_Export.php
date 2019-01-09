<?php

namespace Tribe\Project\CLI;

use Tribe\Project\Container\Exporter;
use Tribe\Project\Core;

class Container_Export extends Command {

	private $project;

	public function __construct( Core $project ) {
		$this->project = $project;
		parent::__construct();
	}

	protected function command() {
		return 'container export';
	}

	protected function description() {
		return 'Exports the files needed to autocomplete container names and methods in PhpStorm';
	}

	protected function arguments() {
		return [];
	}

	public function run_command( $args, $assoc_args ) {
		$reflection = new \ReflectionObject( $this->project );
		$providers = $reflection->getProperty( 'providers' );
		$providers->setAccessible( true );
		$exporter = new Exporter( $providers->getValue( $this->project ) );
		$exporter->dumpPhpstorm( $this->project->container() );
		\WP_CLI::success( 'Done!' );
	}

}