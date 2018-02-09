<?php

namespace Tribe\Project\Util;

class SVG_Support {

	/**
	 * Adds hooks for adding post type support
	 * @action init
	 */
	public function hook() {
		add_action( 'admin_init', [ $this, 'add_svg_upload' ] );
		add_action( 'load-post.php', [ $this, 'add_editor_styles' ] );
		add_action( 'load-post-new.php', [ $this, 'add_editor_styles' ] );
		add_action( 'after_setup_theme', [ $this, 'theme_prefix_setup' ], 99 );
	}

	public function theme_prefix_setup() {
		$existing = get_theme_support( 'custom-logo' );
		if ( $existing ) {
			$existing                = current( $existing );
			$existing['flex-width']  = true;
			$existing['flex-height'] = true;
			add_theme_support( 'custom-logo', $existing );
		}
	}

	public function add_svg_upload() {
		ob_start();
		add_action( 'wp_ajax_adminlc_mce_svg.css', [ $this, 'tinyMCE_svg_css' ] );
		add_filter( 'image_send_to_editor', [ $this, 'remove_dimensions_svg' ], 10 );
		add_filter( 'upload_mimes', [ $this, 'filter_mimes' ] );
		add_action( 'shutdown', [ $this, 'on_shutdown' ], 0 );
		add_filter( 'final_output', [ $this, 'fix_template' ] );
	}

	public function add_editor_styles() {
		add_filter( 'mce_css', [ $this, 'filter_mce_css' ] );
	}

	public function filter_mce_css( $mce_css ) {
		$mce_css .= ', ' . '/wp-admin/admin-ajax.php?action=adminlc_mce_svg.css';
		return $mce_css;
	}

	public function remove_dimensions_svg( $html = '' ) {
		return str_ireplace( [ " width=\"1\"", " height=\"1\"" ], "", $html );
	}

	public function tinyMCE_svg_css() {
		header( 'Content-type: text/css' );
		echo 'img[src$=".svg"] { width: 100%; height: auto; }';
		exit();
	}

	public function filter_mimes( $mimes = [] ) {
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}

	public function on_shutdown() {
		$final     = '';
		$ob_levels = count( ob_get_level() );
		for ( $i = 0; $i < $ob_levels; $i++ ) {
			$final .= ob_get_clean();
		}
		echo apply_filters( 'final_output', $final );
	}

	public function fix_template( $content = '' ) {
		$content = str_replace(
			'<# } else if ( \'image\' === data.type && data.sizes && data.sizes.full ) { #>',
			'<# } else if ( \'svg+xml\' === data.subtype ) { #>
				<img class="details-image" src="{{ data.url }}" draggable="false" />
			<# } else if ( \'image\' === data.type && data.sizes && data.sizes.full ) { #>',
			$content
		);
		$content = str_replace(
			'<# } else if ( \'image\' === data.type && data.sizes ) { #>',
			'<# } else if ( \'svg+xml\' === data.subtype ) { #>
				<div class="centered">
					<img src="{{ data.url }}" class="thumbnail" draggable="false" />
				</div>
			<# } else if ( \'image\' === data.type && data.sizes ) { #>',
			$content
		);
		return $content;
	}
}
