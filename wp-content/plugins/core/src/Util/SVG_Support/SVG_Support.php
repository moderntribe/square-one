<?php

namespace Tribe\Project\Util\SVG_Support;

use enshrined\svgSanitize\Sanitizer;
use Tribe\Project\Util\SVG_Support\Includes\Safe_SVG_Attributes;
use Tribe\Project\Util\SVG_Support\Includes\Safe_SVG_Tags;

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
		add_filter( 'wp_handle_upload_prefilter', [ $this, 'check_for_svg' ] );
		// Testing purposes only
		add_filter( 'test_svg_upload_prefilter', [ $this, 'check_for_svg' ] );
	}

	/**
	 * @action after_setup_theme
	 */
	public function theme_prefix_setup() {
		$existing = get_theme_support( 'custom-logo' );
		if ( $existing ) {
			$existing                = current( $existing );
			$existing['flex-width']  = true;
			$existing['flex-height'] = true;
			add_theme_support( 'custom-logo', $existing );
		}
	}

	/**
	 * @action admin_init
	 */
	public function add_svg_upload() {
		ob_start();
		add_action( 'wp_ajax_adminlc_mce_svg.css', [ $this, 'tinyMCE_svg_css' ] );
		add_filter( 'image_send_to_editor', [ $this, 'remove_dimensions_svg' ], 10 );
		add_filter( 'upload_mimes', [ $this, 'filter_mimes' ] );
		add_action( 'shutdown', [ $this, 'on_shutdown' ], 0 );
		add_filter( 'final_output', [ $this, 'fix_template' ] );
	}

	/**
	 * @action load-post.php
	 * @action load-post-new.php
	 */
	public function add_editor_styles() {
		add_filter( 'mce_css', [ $this, 'filter_mce_css' ] );
	}

	/**
	 * @filter mce_css
	 */
	public function filter_mce_css( $mce_css ) {
		$mce_css .= ', ' . '/wp-admin/admin-ajax.php?action=adminlc_mce_svg.css';

		return $mce_css;
	}

	/**
	 * @filter image_send_to_editor (admin_init)
	 */
	public function remove_dimensions_svg( $html = '' ) {
		return str_ireplace( [ " width=\"1\"", " height=\"1\"" ], "", $html );
	}

	/**
	 * @action wp_ajax_adminlc_mce_svg.css (admin_init)
	 */
	public function tinyMCE_svg_css() {
		header( 'Content-type: text/css' );
		echo 'img[src$=".svg"] { width: 100%; height: auto; }';
		exit();
	}

	/**
	 * @filter upload_mimes (admin_init)
	 */
	public function filter_mimes( $mimes = [] ) {
		$mimes['svg'] = 'image/svg+xml';

		return $mimes;
	}

	/**
	 * @action shutdown (admin_init)
	 */
	public function on_shutdown() {
		$final     = '';
		$ob_levels = ob_get_level();
		for ( $i = 0; $i < $ob_levels; $i++ ) {
			$final .= ob_get_clean();
		}
		echo apply_filters( 'final_output', $final );
	}

	/**
	 * @filter final_output (admin_init)
	 */
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

	/**
	 * Checks if the file is an SVG. if so, sanitizes and save it in the temporary file
	 * to be later processed and moved to the permanent location by WordPress.
	 *
	 * @param $file
	 *
	 * @filter wp_handle_upload_prefilter
	 * @return mixed
	 * @see _wp_handle_upload()
	 */
	public function check_for_svg( $file ) {
		if ( $file['type'] === 'image/svg+xml' ) {
			if ( ! $this->sanitize( $file['tmp_name'] ) ) {
				$file['error'] = __( "Sorry, this file couldn't be sanitized so for security reasons wasn't uploaded",
					'tribe' );
			}
		}

		return $file;
	}

	/**
	 * Sanitize the SVG
	 *
	 * @param $file
	 *
	 * @see https://packagist.org/packages/enshrined/svg-sanitize
	 * @return bool|int
	 */
	protected function sanitize( $file ) {
		$sanitizer = new Sanitizer();

		$dirty = file_get_contents( $file );

		// Is the SVG gzipped? If so we try and decode the string
		if ( $is_zipped = $this->is_gzipped( $dirty ) ) {
			$dirty = gzdecode( $dirty );

			// If decoding fails, bail as we're not secure
			if ( $dirty === false ) {
				return false;
			}
		}

		/**
		 * This sanitizer works similarly to wp_kses(), whitelisting certain tags and attributes
		 * and stripping everything else.
		 */
		$sanitizer->setAllowedTags( new Safe_SVG_Tags() );
		$sanitizer->setAllowedAttrs( new Safe_SVG_Attributes() );

		$clean = $sanitizer->sanitize( $dirty );

		if ( $clean === false ) {
			return false;
		}

		// If we were gzipped, we need to re-zip
		if ( $is_zipped ) {
			$clean = gzencode( $clean );
		}

		file_put_contents( $file, $clean );

		return true;
	}

	/**
	 * Check if the contents are gzipped
	 *
	 * @see http://www.gzip.org/zlib/rfc-gzip.html#member-format
	 *
	 * @param $contents
	 *
	 * @return bool
	 */
	protected function is_gzipped( $contents ) {
		if ( function_exists( 'mb_strpos' ) ) {
			return 0 === mb_strpos( $contents, "\x1f" . "\x8b" . "\x08" );
		} else {
			return 0 === strpos( $contents, "\x1f" . "\x8b" . "\x08" );
		}
	}
}
