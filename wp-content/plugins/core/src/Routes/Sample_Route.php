<?php

namespace Tribe\Project\Routes;

use Tribe\Libs\Routes\Abstract_Route;

class Sample_Route extends Abstract_Route {
	/**
	 * Registers routes.
	 *
	 * @return void
	 */
	public function register() : void {
		add_filter( 'core_js_config', [ $this, 'js_config' ] );
	}

	/**
	 * Javascript configuration for this route.
	 *
	 * @param array $data The current core JS configuration.
	 * @return array      Modified core JS configuration.
	 */
	public function js_config( array $data = [] ) : array {
		$data['FormSubmitEndpoint'] = rest_url( '/mt-trial/v1/submit-form/' );
		return $data;
	}

	/**
	 * The request methods that are authorized on this route. Only GET
	 * is authorized by default.
	 *
	 * @return array Acceptable request methods for this route.
	 */
	public function get_request_methods() : array {
		return [
			'GET',
		];
	}

	/**
	 * Returns the name for the route.
	 *
	 * @return string The name for the route.
	 */
	public function get_name() : string {
		return 'sample';
	}

	/**
	 * Returns the pattern for the route.
	 *
	 * @return string The pattern for the route.
	 */
	public function get_pattern() : string {
		return '^sample$';
	}

	/**
	 * Returns matches for the route.
	 *
	 * @return array Matches for the route.
	 */
	public function get_matches() : array {
		return [];
	}

	/**
	 * Returns query var names for the route.
	 *
	 * @return array Query var names for the route.
	 */
	public function get_query_var_names() : array {
		return array_keys( $this->get_matches() );
	}

	/**
	 * The template to use for the route.
	 *
	 * @return string The template name for the route.
	 */
	public function get_template() : string {
		return locate_template( 'page-templates/sample.php' );
	}

	/**
	 * Filter the title tag.
	 *
	 * @return string Title for the page.
	 */
	public function get_title() : string {
		return esc_html__( 'Sample | Project', 'project' );
	}
}
