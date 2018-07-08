<?php

namespace Tribe\Project\Components_Docs;

use Tribe\Project\Facade\Items\Request;

class Router {

	public function add_rewrite_rule() {
		add_rewrite_tag( '%current_component%', '([^&]+)' );
		add_rewrite_rule( '^components_docs/([^/]*)/?', 'index.php?current_component=$matches[1]', 'top' );
	}

	public function show_components_docs_page( $template ) {
		$current = Request::query()->query_vars['current_component'];

		if ( empty( $current ) ) {
			return $template;
		}

		return dirname( __FILE__ ) . '/Templates/components_docs.php';
	}

}