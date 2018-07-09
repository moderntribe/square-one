<?php

namespace Tribe\Project\Components_Docs;

use Tribe\Project\Facade\Items\Request;

class Router {

	public function add_rewrite_rule() {
		add_rewrite_tag( '%current_component%', '([^&]+)' );
		add_rewrite_rule( '^components_docs/([^/]*)/?', 'index.php?current_component=$matches[1]', 'top' );
	}

	public function show_components_docs_page( $template ) {
		if ( ! $this->is_docs_page() ) {
			return $template;
		}

		return dirname( __FILE__ ) . '/Templates/components_docs.php';
	}

	public function is_docs_page(): bool {
		return ! empty( Request::query()->query_vars['current_component'] );
	}

	public static function plugin_dir( string $file = null ): string {
		return sprintf( '%s/%s', untrailingslashit( dirname( __FILE__ ) ), $file );
	}

	public static function plugin_url( string $file = null ): string {
		return sprintf( '%s/%s', untrailingslashit( plugin_dir_url( __FILE__ ) ), $file );
	}
}