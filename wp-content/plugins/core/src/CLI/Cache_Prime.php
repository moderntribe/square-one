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
				'type'        => 'optional',
				'name'        => 'blog_id',
				'optional'    => true,
				'description' => __( 'For Multisite installs, pass the blog ID of the site you wish to prime.', 'tribe' ),
			],
			[
				'type'        => 'optional',
				'name'        => 'url',
				'optional'    => true,
				'description' => __( 'If provided, cache priming will target the given URL. If omitted, the home_url() will be used.', 'tribe' ),
			],
		];
	}

	public function run_command( $args, $assoc_args ) {

		if( is_multisite() ) {
			$blog_id = ( ! empty( $assoc_args['blog_id'] ) ) ? (int) $assoc_args['blog_id'] : 1;
			switch_to_blog( $blog_id );
		}

		$url = '';

		if( empty( $assoc_args['url'] ) ) {
			$url = home_url();
		} else {
			$url = $assoc_args['url'];
		}

		if( ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
			\WP_CLI::error( __( 'If you pass the --url argument, it must be a valid URL. Note, if you omit this argument, the home page URL will be used.', 'tribe' ) );
		}


		$html = HtmlDomParser::file_get_html( esc_url( $url ) );

		foreach( $html->find( 'a' ) as $anchor ) {
			$response = wp_remote_get( $anchor->href );
			$http_code = (int) wp_remote_retrieve_response_code( $response );
			if( 200 === $http_code ) {
				\WP_CLI::success( esc_attr__( sprintf( '%s has been primed', esc_url( $anchor->href ) ), 'tribe' ) );
			}
		}

		if( is_multisite() ) {
			restore_current_blog();
		}

		\WP_CLI::success( $this->get_optimus_prime() );
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