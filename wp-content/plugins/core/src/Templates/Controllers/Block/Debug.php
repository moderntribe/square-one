<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Block;

class Debug extends Block_Controller {
	public function render( string $path = '' ): string {
		return sprintf(
			'<div class="placeholder-block-%s">
				<h2>%s</h2>
				<h3>Attributes</h3>
				<pre>%s</pre>
				<h3>Content</h3>
				<pre>%s</pre>
			</div>',
			sanitize_html_class( $this->block_type->name() ),
			esc_html( $this->block_type->name() ),
			str_replace( '[', '&#91;', esc_html( print_r( $this->attributes, true ) ) ), // replace brackets with entity to escape shortcode-like strings
			esc_html( $this->content )
		);
	}
}
