<?php

namespace Tribe\Project\CLI;

use WP_CLI;

abstract class Command extends \WP_CLI_Command {

	protected $file_system = null;
	protected $templates_path = '';
	protected $src_path = '';

	public function __construct( File_System $file_system = null ) {
		$this->file_system = $file_system;
		$this->templates_path = trailingslashit( trailingslashit( dirname( __DIR__, 2 ) ) . 'assets/templates/cli' );
		$this->src_path = trailingslashit( dirname( __DIR__, 1 ) );
		parent::__construct();
	}

	public function register() {
		if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
			return;
		}

		WP_CLI::add_command(
			's1 ' . $this->command(), [ $this, 'run_command' ], [
				'shortdesc' => $this->description(),
				'synopsis'  => $this->arguments(),
			]
		);
	}

	abstract protected function command();
	abstract protected function description();
	abstract protected function arguments();
	abstract protected function run_command( $args, $assoc_args );

	/**
	 * converts a multi-word lowercase _ separated slug in
	 * multi-word upper case first format.
	 *
	 * multi_word_slug becomes Multi_Word_Slug
	 *
	 * @param string $slug lowercase words separated by _.
	 *
	 * @return string
	 */
	public function ucwords( $slug ) {
		$uc_words = array_map(
			function( $word ) {
					return ucfirst( $word );
			}, explode( '_', $slug )
		);
		return implode( '_', $uc_words );
	}

	protected function sanitize_slug( $args ) {
		list( $slug ) = $args;

		return str_replace( '-', '_', sanitize_title( $slug ) );
	}
}