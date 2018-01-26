<?php
namespace Tribe\Project\CLI;

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

		$url = '';

		if( empty( $assoc_args['target-url'] ) ) {
			$url = home_url();
		} else {
			$url = $assoc_args['target-url'];
		}


		if( ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
			\WP_CLI::error( __( 'If you pass the --target-url argument, it must be a valid URL. Note, if you omit this argument, the home page URL will be used.', 'tribe' ) );
		}

		// file_get_contents() has a long-known SSL implementation bug. So instead, we use a HTTP request with sslverify off
        $request = wp_remote_get( $url , [ 'sslverify' => false ] );
		if( is_wp_error( $request ) ) {
		    \WP_CLI::error( __( $request->get_error_message(), 'tribe' ) );
        }

        $html = wp_remote_retrieve_body( $request );
		if( empty( $html ) ) {
		    \WP_CLI::error( __( 'Cannot parse HRML', 'tribe' ) );
        }

		$dom = new \DOMDocument();
		$dom->loadHTML( $html, LIBXML_NOWARNING );

		foreach( $dom->getElementsByTagName( 'a' ) as $anchor ) {
		    if( empty( $anchor->getAttribute( 'href') ) ) {
		        continue;
            }

			$response = wp_remote_get( $anchor->getAttribute( 'href'), [ 'blocking' => false] );
			$http_code = (int) wp_remote_retrieve_response_code( $response );
			if( 200 === $http_code ) {
				\WP_CLI::success( esc_attr__( sprintf( '%s has been primed', esc_url( $anchor->getAttribute( 'href') ) ), 'tribe' ) );
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