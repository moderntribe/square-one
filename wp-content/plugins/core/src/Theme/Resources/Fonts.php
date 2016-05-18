<?php


namespace Tribe\Project\Theme\Resources;


class Fonts {
	private $typekit_id = '';

	public function __construct( $typekit_id = '' ) {
		$this->typekit_id = $typekit_id;
	}

	public function hook() {
		add_action( 'wp_head', [ $this, 'load_fonts' ], 0, 0 );
		add_action( 'login_head', [ $this, 'load_fonts' ], 0, 0 );
	}
	
	/**
	 * Add any required fonts
	 */
	public function load_fonts() {
		?>

		<script>
			var modern_tribe = window.modern_tribe || {};
			modern_tribe.fonts = {
				state: {
					loading: true,
					active: false
				},
				events: {
					trigger: function ( event_type, event_data, el ) {
						var event;
						try {
							event = new CustomEvent( event_type, { detail: event_data } );
						} catch ( e ) {
							event = document.createEvent( 'CustomEvent' );
							event.initCustomEvent( event_type, true, true, event_data );
						}
						el.dispatchEvent( event );
					}
				}
			};
			var WebFontConfig = {
				typekit: {
					id: '<?php echo $this->typekit_id; ?>'
				},
				loading: function () {
					modern_tribe.fonts.state.loading = true;
					modern_tribe.fonts.state.active = false;
					modern_tribe.fonts.events.trigger( 'modern_tribe/fonts_loading', {}, document );
				},
				active: function () {
					modern_tribe.fonts.state.loading = false;
					modern_tribe.fonts.state.active = true;
					modern_tribe.fonts.events.trigger( 'modern_tribe/fonts_loaded', {}, document );
				},
				inactive: function () {
					modern_tribe.fonts.state.loading = false;
					modern_tribe.fonts.state.active = false;
					modern_tribe.fonts.events.trigger( 'modern_tribe/fonts_failed', {}, document );
				}
			};
			(function ( d ) {
				var wf = d.createElement( 'script' ), s = d.scripts[ 0 ];
				wf.src = '<?php echo $this->get_webfont_src(); ?>';
				s.parentNode.insertBefore( wf, s );
			})( document );
		</script>

		<?php

	}

	private function get_webfont_src() {
		return trailingslashit( get_template_directory_uri() ) . 'js/vendor/webfontloader.js';
	}
}