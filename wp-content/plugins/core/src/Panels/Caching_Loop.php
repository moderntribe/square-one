<?php

namespace Tribe\Project\Panels;

use ModularContent\Loop;
use ModularContent\PanelCollection;
use Tribe\Libs\Cache\Cache;

class Caching_Loop extends Loop {

	public function render() {
		$cache  = new Cache();
		$key    = $this->get_cache_key();
		$cached = $cache->get( $key, 'panels' );
		if ( $cached ) {
			return $cached;
		}
		$rendered = parent::render();
		$cache->set( $key, $rendered, 'panels', HOUR_IN_SECONDS );

		return $rendered;
	}

	private function get_cache_key() {
		$json = \json_encode( $this->collection );
		$key  = md5( $json );

		return $key;
	}

	public static function preempt_panels_loop() {
		if ( is_panel_preview() ) {
			return;
		}
		// mostly copied from \ModularContent\Plugin::loop()
		$panels       = null;
		$current_post = get_queried_object();

		if ( $current_post && isset( $current_post->post_type ) && post_type_supports( $current_post->post_type, 'modular-content' ) ) {
			if ( ! empty( $_GET['preview_id'] ) ) {
				$autosave = wp_get_post_autosave( get_the_ID() );
				if ( $autosave ) {
					$current_post = $autosave;
				}
			}

			$panels = PanelCollection::find_by_post_id( $current_post->ID );
		}

		// here's where we diverge
		$loop = new self( $panels );
		\ModularContent\Plugin::instance()->set_loop( $loop );
	}
}
