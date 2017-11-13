<?php

namespace Tribe\Project\CLI;
use WP_CLI;

abstract class Command extends \WP_CLI_Command {

	public function register() {
		if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
			return;
		}

		WP_CLI::add_command( 's1 ' . $this->command(), $this->run_command(), [
			'shortdesc' => $this->description(),
			'synopsis'  => $this->arguments(),
		] );
	}

	abstract protected function command();
	abstract protected function description();
	abstract protected function arguments();
	abstract protected function run_command( $args, $assoc_args );

	public function ucwords( $slug ) {
		$uc_words = array_map( function( $word ) {
			return ucfirst( $word );
		}, explode( '_', $slug ) );
		return implode( '_', $uc_words );
	}

	protected function sanitize_slug( $args ) {
		list( $slug ) = $args;

		return str_replace( '-', '_', sanitize_title( $slug ) );
	}
}