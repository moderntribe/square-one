<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components;

class Head extends Component {

	public function init() {
		$this->data['name']         = get_bloginfo( 'name' );
		$this->data['pingback_url'] = get_bloginfo( 'pingback_url' );
		$this->data['title']        = $this->get_page_title();
	}

	protected function get_page_title() {
		if ( is_front_page() ) {
			return '';
		}

		// Blog
		if ( is_home() ) {
			return __( 'Blog', 'tribe' );
		}

		// Search
		if ( is_search() ) {
			return __( 'Search Results', 'tribe' );
		}

		// 404
		if ( is_404() ) {
			return __( 'Page Not Found (404)', 'tribe' );
		}

		// Singular
		if ( is_singular() ) {
			return get_the_title();
		}

		// Archives
		return get_the_archive_title();
	}

	public function render(): void {
		?>
		<head>

			{# // TITLE: Handled by WP #}

			{# // MISC Meta #}
			<meta charset="utf-8">
			<meta name="author" content="{{ name|esc_attr }}">
			<link rel="pingback" href="{{ pingback_url|esc_url }}">

			{# // MOBILE META #}
			<meta name="HandheldFriendly" content="True">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">

			{# // PLATFORM META: iOS & Android #}
			<meta name="apple-mobile-web-app-title" content="{{ page_title|esc_attr }}">

			{# // PLATFORM META: IE #}
			<meta name="application-name" content="{{ name|esc_attr }}">

			{{ do_action( 'wp_head' ) }}

		</head>
		<?php
	}
}
