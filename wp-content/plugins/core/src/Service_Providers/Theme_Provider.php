<?php


namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\Request\Request;
use Tribe\Project\Request\Server;
use Tribe\Project\Theme\Body_Classes;
use Tribe\Project\Theme\Full_Size_Gif;
use Tribe\Project\Theme\Image_Sizes;
use Tribe\Project\Theme\Image_Wrap;
use Tribe\Project\Theme\Gravity_Forms_Filter;
use Tribe\Project\Theme\Nav\Nav_Attribute_Filters;
use Tribe\Project\Theme\Oembed_Filter;
use Tribe\Project\Theme\Resources\Editor_Styles;
use Tribe\Project\Theme\Resources\Editor_Formats;
use Tribe\Project\Theme\Resources\Emoji_Disabler;
use Tribe\Project\Theme\Resources\Fonts;
use Tribe\Project\Theme\Resources\Legacy_Check;
use Tribe\Project\Theme\Resources\Login_Resources;
use Tribe\Project\Theme\Resources\Scripts;
use Tribe\Project\Theme\Resources\Styles;
use Tribe\Project\Theme\Supports;
use Tribe\Project\Theme\WP_Responsive_Image_Disabler;

class Theme_Provider implements ServiceProviderInterface {

	private $typekit_id   = '';
	private $google_fonts = [];

	/**
	 * Custom (self-hosted) fonts are sourced/imported in the theme: wp-content/themes/core/pcss/base/_fonts.pcss
	 * Declare them here if they require webfont event support (loading, loaded, etc).
	 */
	private $custom_fonts = [];

	public function register( Container $container ) {
		$this->request( $container );
		$this->body_classes( $container );
		// $this->full_size_gif( $container ); Uncomment to require full size gifs
		$this->image_sizes( $container );
		$this->image_wrap( $container );
		$this->image_links( $container );
		$this->disable_responsive_images( $container );
		$this->oembed( $container );
		$this->supports( $container );

		$this->login_resources( $container );
		$this->legacy_resources( $container );
		$this->disable_emoji( $container );

		$this->fonts( $container );

		$this->scripts( $container );
		$this->styles( $container );
		$this->editor_styles( $container );
		//$this->editor_formats( $container );

		$this->nav_attributes( $container );

		$this->gravity_forms( $container );
	}

	private function request( Container $container ) {
		$container['server'] = function ( Container $container ) {
			return new Server();
		};

		$container['request'] = function ( Container $container ) {
			return new Request( $container['server'] );
		};
	}

	private function body_classes( Container $container ) {
		$container[ 'theme.body_classes' ] = function ( Container $container ) {
			return new Body_Classes();
		};
		add_filter( 'body_class', function ( $classes ) use ( $container ) {
			return $container[ 'theme.body_classes' ]->body_classes( $classes );
		}, 10, 1 );
	}

	private function full_size_gif( Container $container ) {
		$container[ 'theme.full_size_gif' ] = function ( Container $container ) {
			return new Full_Size_Gif();
		};
		add_filter( 'image_downsize', function( $data, $id, $size ) use ( $container ) {
			return $container[ 'theme.full_size_gif' ]->full_size_only_gif( $data, $id, $size );
		}, 10, 3 );
	}

	private function image_sizes( Container $container ) {
		$container[ 'theme.images.sizes' ] = function ( Container $container ) {
			return new Image_Sizes();
		};
		add_action( 'after_setup_theme', function () use ( $container ) {
			$container[ 'theme.images.sizes' ]->register_sizes();
		}, 10, 0 );
		add_filter( 'wpseo_opengraph_image_size', function ( $size ) use ( $container ) {
			return $container[ 'theme.images.sizes' ]->customize_wpseo_image_size( $size );
		}, 10, 1 );
	}

	private function image_wrap( Container $container ) {
		$container[ 'theme.images.wrap' ] = function ( Container $container ) {
			return new Image_Wrap();
		};
		add_filter( 'the_content', function ( $html ) use ( $container ) {
			return $container[ 'theme.images.wrap' ]->customize_wp_image_non_captioned_output( $html );
		}, 12, 1 );
		add_filter( 'the_content', function ( $html ) use ( $container ) {
			return $container[ 'theme.images.wrap' ]->customize_wp_image_captioned_output( $html );
		}, 12, 1 );
	}

	private function image_links( Container $container ) {
		add_filter( 'pre_option_image_default_link_type', function () {
			return 'none';
		}, 10, 1 );
	}

	private function disable_responsive_images( Container $container ) {
		$container[ 'theme.images.responsive_disabler' ] = function ( Container $container ) {
			return new WP_Responsive_Image_Disabler();
		};
		add_action( 'init', function () use ( $container ) {
			$container[ 'theme.images.responsive_disabler' ]->hook();
		} );
	}

	private function oembed( Container $container ) {
		$container[ 'theme.oembed' ] = function ( Container $container ) {
			return new Oembed_Filter( [
				Oembed_Filter::PROVIDER_VIMEO,
				Oembed_Filter::PROVIDER_YOUTUBE,
			] );
		};

		add_filter( 'oembed_dataparse', function( $html, $data, $url ) use ( $container ) {
			return $container['theme.oembed']->get_video_component( $html, $data, $url );
		}, 999, 3 );

		add_filter( 'embed_oembed_html', function ( $html, $url, $attr, $post_id ) use ( $container ) {
			return $container[ 'theme.oembed' ]->filter_frontend_html_from_cache( $html, $url, $attr, $post_id );
		}, 1, 4 );

		add_filter( 'embed_oembed_html', function ( $html, $url, $attr, $post_id ) use ( $container ) {
			return $container[ 'theme.oembed' ]->wrap_admin_oembed( $html, $url, $attr, $post_id );
		}, 99, 4 );
	}

	private function supports( Container $container ) {
		$container[ 'theme.supports' ] = function ( Container $container ) {
			return new Supports();
		};

		add_action( 'after_setup_theme', function () use ( $container ) {
			$container[ 'theme.supports' ]->add_theme_supports();
		}, 10, 0 );
	}

	private function login_resources( Container $container ) {
		$container[ 'theme.resources.login' ] = function ( Container $container ) {
			return new Login_Resources();
		};
		add_action( 'login_enqueue_scripts', function () use ( $container ) {
			$container[ 'theme.resources.login' ]->login_styles();
		}, 10, 0 );
	}

	private function legacy_resources( Container $container ) {
		$container[ 'theme.resources.legacy' ] = function ( Container $container ) {
			return new Legacy_Check();
		};

		add_action( 'wp_head', function () use ( $container ) {
			$container[ 'theme.resources.legacy' ]->old_browsers();
		}, 0, 0 );

		add_action( 'init', function() use ( $container ) {
			$container[ 'theme.resources.legacy' ]->add_unsupported_rewrite();
		} );

		add_filter( 'template_include', function( $template ) use ( $container ) {
			return $container['theme.resources.legacy']->load_unsupported_template( $template );
		} );
	}

	private function disable_emoji( Container $container ) {
		$container[ 'theme.resources.emoji_disabler' ] = function ( Container $container ) {
			return new Emoji_Disabler();
		};
		add_action( 'after_setup_theme', function () use ( $container ) {
			$container[ 'theme.resources.emoji_disabler' ]->remove_hooks();
		} );
	}

	private function fonts( Container $container ) {
		$container[ 'theme.resources.typekit_id' ] = $this->typekit_id;
		$container[ 'theme.resources.google_fonts' ] = $this->google_fonts;
		$container[ 'theme.resources.custom_fonts' ] = $this->custom_fonts;
		$container[ 'theme.resources.fonts' ] = function ( Container $container ) {
			return new Fonts(
				[
					'typekit' => $container[ 'theme.resources.typekit_id' ],
					'google'  => $container[ 'theme.resources.google_fonts' ],
					'custom'  => $container[ 'theme.resources.custom_fonts' ],
				]
			);
		};

		add_action( 'wp_head', function () use ( $container ) {
			$container[ 'theme.resources.fonts' ]->load_fonts();
		}, 0, 0 );
		add_action( 'tribe/unsupported_browser/head', function () use ( $container ) {
			$container[ 'theme.resources.fonts' ]->load_fonts();
		}, 0, 0 );
		add_action( 'admin_head', function () use ( $container ) {
			$container[ 'theme.resources.fonts' ]->localize_typekit_tinymce();
		}, 0, 0 );
		add_filter( 'mce_external_plugins', function ( $plugins ) use ( $container ) {
			return $container[ 'theme.resources.fonts' ]->add_typekit_to_editor( $plugins );
		} , 10, 1 );
		/* add_action( 'login_head', function() use ( $container ) {
			$container[ 'theme.resources.fonts' ]->load_fonts();
		}, 0, 0); */
	}

	private function scripts( Container $container ) {
		$container[ 'theme.resources.scripts' ] = function ( Container $container ) {
			return new Scripts();
		};
		add_action( 'wp_footer', function () use ( $container ) {
			$container[ 'theme.resources.scripts' ]->add_early_polyfills();
		}, 10, 0 );
		add_action( 'wp_enqueue_scripts', function () use ( $container ) {
			$container[ 'theme.resources.scripts' ]->enqueue_scripts();
		}, 10, 0 );
	}

	private function styles( Container $container ) {
		$container[ 'theme.resources.styles' ] = function ( Container $container ) {
			return new Styles();
		};
		add_action( 'wp_enqueue_scripts', function () use ( $container ) {
			$container[ 'theme.resources.styles' ]->enqueue_styles();
		}, 10, 0 );
	}

	private function editor_styles( Container &$container ) {
		$container[ 'theme.resources.editor_styles' ] = function ( Container $container ) {
			return new Editor_Styles();
		};
		add_action( 'after_setup_theme', function () use ( $container ) {
			$container[ 'theme.resources.editor_styles' ]->visual_editor_styles();
		}, 10, 0 );
		add_filter( 'tiny_mce_before_init', function ( $settings ) use ( $container ) {
			return $container[ 'theme.resources.editor_styles' ]->visual_editor_body_class( $settings );
		}, 10, 1 );
	}

	private function editor_formats( Container &$container ) {
		$container[ 'theme.resources.editor_formats' ] = function ( Container $container ) {
			return new Editor_Formats();
		};
		add_filter( 'mce_buttons', function ( $settings ) use ( $container ) {
			return $container[ 'theme.resources.editor_formats' ]->mce_buttons( $settings );
		}, 10, 1 );
		add_filter( 'tiny_mce_before_init', function ( $settings ) use ( $container ) {
			return $container[ 'theme.resources.editor_formats' ]->visual_editor_styles_dropdown( $settings );
		}, 10, 1 );
	}

	private function nav_attributes( Container &$container ) {
		$container[ 'theme.nav.attribute_filters' ] = function ( Container $container ) {
			return new Nav_Attribute_Filters();
		};

		add_filter( 'nav_menu_item_id', function ( $menu_id, $item, $args, $depth ) use ( $container ) {
			return $container[ 'theme.nav.attribute_filters' ]->customize_nav_item_id( $menu_id, $item, $args, $depth );
		}, 10, 4 );

		add_filter( 'nav_menu_css_class', function ( $classes, $item, $args, $depth ) use ( $container ) {
			return $container[ 'theme.nav.attribute_filters' ]->customize_nav_item_classes( $classes, $item, $args, $depth );
		}, 10, 4 );

		add_filter( 'nav_menu_link_attributes', function ( $atts, $item, $args, $depth ) use ( $container ) {
			return $container[ 'theme.nav.attribute_filters' ]->customize_nav_item_anchor_atts( $atts, $item, $args, $depth );
		}, 10, 4 );
	}

	private function gravity_forms( Container $container ) {
		$container[ 'theme.gravity_forms_filter' ] = function ( Container $container ) {
			return new Gravity_Forms_Filter();
		};
		add_action( 'init', function () use ( $container ) {
			$container[ 'theme.gravity_forms_filter' ]->hook();
		}, 10, 0 );
	}

}
