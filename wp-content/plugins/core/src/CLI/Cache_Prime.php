<?php
namespace Tribe\Project\CLI;

use Sunra\PhpSimple\HtmlDomParser;

/**
 * A WP-CLI command to manage cache operations. In it's most basic form, it will retrieve URLs on a given page an request them priming page or object caches that may exist.
 * @package Tribe\Project\CLI
 */
class Cache_Prime extends Command {

	protected function command() {
		return 'cache-prime';
	}

	protected function description() {
		return __( 'Primes the object cache by crawling a single URL or all anchor refs on home page', 'tribe' );
	}

	protected function arguments() {
		return [
			[
				'type'        => 'assoc',
				'name'        => 'target-url',
				'optional'    => true,
				'description' => __( 'If provided, cache priming will target the given URL. If omitted, the home_url() will be used.', 'tribe' ),
			],
		];
	}

	public function run_command( $args, $assoc_args ) {

		if ( empty( $assoc_args['target-url'] ) ) {
			$url = home_url();
		} else {
			$url = $assoc_args['target-url'];
		}

		if ( ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
			\WP_CLI::error( __( 'If you pass the --target-url argument, it must be a valid URL. Note, if you omit this argument, the home page URL will be used.', 'tribe' ) );
		}

		$request = wp_remote_get( $url );
		if ( 200 !== wp_remote_retrieve_response_code( $request ) ) {
			\WP_CLI::error( __( 'URL does not appear to be valid', 'tribe' ) );
		}
		$html = HtmlDomParser::str_get_html( wp_remote_retrieve_body( $request ) );

		foreach ( $html->find( 'a' ) as $anchor ) {
			$response = wp_remote_get( $anchor->href );
			$http_code = (int) wp_remote_retrieve_response_code( $response );
			if ( 200 === $http_code ) {
				\WP_CLI::success( sprintf( esc_attr__( '%s has been primed', 'tribe' ), esc_url( $anchor->href ) ) );
			}
		}

		\WP_CLI::log( $this->get_optimus_prime() );
	}

	public function get_optimus_prime() {
		ob_start();
		?>

		───────────▄▄▄▄▄▄▄▄▄───────────
		────────▄█████████████▄────────
		█████──█████████████████──█████
		▐████▌─▀███▄───────▄███▀─▐████▌
		─█████▄──▀███▄───▄███▀──▄█████─
		─▐██▀███▄──▀███▄███▀──▄███▀██▌─
		──███▄▀███▄──▀███▀──▄███▀▄███──
		──▐█▄▀█▄▀███─▄─▀─▄─███▀▄█▀▄█▌──
		───███▄▀█▄██─██▄██─██▄█▀▄███───
		────▀███▄▀██─█████─██▀▄███▀────
		───█▄─▀█████─█████─█████▀─▄█───
		───███────────███────────███───
		───███▄────▄█─███─█▄────▄███───
		───█████─▄███─███─███▄─█████───
		───█████─████─███─████─█████───
		───█████─████─███─████─█████───
		───█████─████─███─████─█████───
		───█████─████▄▄▄▄▄████─█████───
		────▀███─█████████████─███▀────
		──────▀█─███─▄▄▄▄▄─███─█▀──────
		─────────▀█▌▐█████▌▐█▀─────────
		────────────███████────────────
		<?php
		return ob_get_clean();
	}
}