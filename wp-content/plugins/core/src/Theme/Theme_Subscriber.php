<?php
declare( strict_types=1 );

namespace Tribe\Project\Theme;

use Psr\Container\ContainerInterface;
use Tribe\Libs\Container\Subscriber_Interface;
use Tribe\Project\Theme\Nav\Nav_Attribute_Filters;
use Tribe\Project\Theme\Resources\Editor_Formats;
use Tribe\Project\Theme\Resources\Editor_Styles;
use Tribe\Project\Theme\Resources\Emoji_Disabler;
use Tribe\Project\Theme\Resources\Fonts;
use Tribe\Project\Theme\Resources\Legacy_Check;
use Tribe\Project\Theme\Resources\Login_Resources;
use Tribe\Project\Theme\Resources\Scripts;
use Tribe\Project\Theme\Resources\Styles;
use Tribe\Project\Theme\Resources\Third_Party_Tags;

class Theme_Subscriber implements Subscriber_Interface {
	public function register( ContainerInterface $container ): void {
		$this->body_classes( $container );
		// $this->full_size_gif( $container ); Uncomment to require full size gifs
		$this->image_sizes( $container );
		$this->image_wrap( $container );
		$this->image_links();
		$this->disable_responsive_images( $container );
		$this->oembed( $container );
		$this->supports( $container );

		$this->login_resources( $container );
		$this->legacy_resources( $container );
		$this->disable_emoji( $container );

		$this->fonts( $container );

		$this->scripts( $container );
		$this->styles( $container );
		$this->third_party_tags( $container );
		$this->editor_styles( $container );
		//$this->editor_formats( $container );

		$this->nav_attributes( $container );

		$this->gravity_forms( $container );
	}

	private function body_classes( ContainerInterface $container ) {
		add_filter( 'body_class', function ( $classes ) use ( $container ) {
			return $container->get( Body_Classes::class )->body_classes( $classes );
		}, 10, 1 );
	}

	private function full_size_gif( ContainerInterface $container ) {
		add_filter( 'image_downsize', function ( $data, $id, $size ) use ( $container ) {
			return $container->get( Full_Size_Gif::class )->full_size_only_gif( $data, $id, $size );
		}, 10, 3 );
	}

	private function image_sizes( ContainerInterface $container ) {
		add_action( 'after_setup_theme', function () use ( $container ) {
			$container->get( Image_Sizes::class )->register_sizes();
		}, 10, 0 );
		add_filter( 'wpseo_opengraph_image_size', function ( $size ) use ( $container ) {
			return $container->get( Image_Sizes::class )->customize_wpseo_image_size( $size );
		}, 10, 1 );
	}

	private function image_wrap( ContainerInterface $container ) {
		add_filter( 'the_content', function ( $html ) use ( $container ) {
			return $container->get( Image_Wrap::class )->customize_wp_image_non_captioned_output( $html );
		}, 12, 1 );
		add_filter( 'the_content', function ( $html ) use ( $container ) {
			return $container->get( Image_Wrap::class )->customize_wp_image_captioned_output( $html );
		}, 12, 1 );
	}

	private function image_links() {
		add_filter( 'pre_option_image_default_link_type', function () {
			return 'none';
		}, 10, 1 );
	}

	private function disable_responsive_images( ContainerInterface $container ) {
		add_action( 'init', function () use ( $container ) {
			$container->get( WP_Responsive_Image_Disabler::class )->hook();
		} );
	}

	private function oembed( ContainerInterface $container ) {
		add_filter( 'oembed_dataparse', function ( $html, $data, $url ) use ( $container ) {
			return $container->get( Oembed_Filter::class )->get_video_component( $html, $data, $url );
		}, 999, 3 );

		add_filter( 'embed_oembed_html', function ( $html, $url, $attr, $post_id ) use ( $container ) {
			return $container->get( Oembed_Filter::class )->filter_frontend_html_from_cache( $html, $url, $attr, $post_id );
		}, 1, 4 );

		add_filter( 'embed_oembed_html', function ( $html, $url, $attr, $post_id ) use ( $container ) {
			return $container->get( Oembed_Filter::class )->wrap_admin_oembed( $html, $url, $attr, $post_id );
		}, 99, 4 );
	}

	private function supports( ContainerInterface $container ) {
		add_action( 'after_setup_theme', function () use ( $container ) {
			$container->get( Supports::class )->add_theme_supports();
		}, 10, 0 );
	}

	private function login_resources( ContainerInterface $container ) {
		add_action( 'login_enqueue_scripts', function () use ( $container ) {
			$container->get( Login_Resources::class )->login_styles();
		}, 10, 0 );
	}

	private function legacy_resources( ContainerInterface $container ) {
		add_action( 'wp_head', function () use ( $container ) {
			$container->get( Legacy_Check::class )->old_browsers();
		}, 0, 0 );

		add_action( 'init', function () use ( $container ) {
			$container->get( Legacy_Check::class )->add_unsupported_rewrite();
		} );

		add_filter( 'template_include', function ( $template ) use ( $container ) {
			return $container->get( Legacy_Check::class )->load_unsupported_template( $template );
		} );
	}

	private function disable_emoji( ContainerInterface $container ) {
		add_action( 'after_setup_theme', function () use ( $container ) {
			$container->get( Emoji_Disabler::class )->remove_hooks();
		} );
	}

	private function fonts( ContainerInterface $container ) {
		add_action( 'wp_head', function () use ( $container ) {
			$container->get( Fonts::class )->load_fonts();
		}, 0, 0 );
		add_action( 'tribe/unsupported_browser/head', function () use ( $container ) {
			$container->get( Fonts::class )->load_fonts();
		}, 0, 0 );
		add_action( 'admin_head', function () use ( $container ) {
			$container->get( Fonts::class )->localize_typekit_tinymce();
		}, 0, 0 );
		add_filter( 'mce_external_plugins', function ( $plugins ) use ( $container ) {
			return $container->get( Fonts::class )->add_typekit_to_editor( $plugins );
		}, 10, 1 );
		/* add_action( 'login_head', function() use ( $container ) {
			$container->get( Fonts::class )->load_fonts();
		}, 0, 0); */
	}

	private function scripts( ContainerInterface $container ) {
		add_action( 'wp_head', function () use ( $container ) {
			$container->get( Scripts::class )->maybe_inject_bugsnag();
		}, 0, 0 );
		add_action( 'wp_head', function () use ( $container ) {
			$container->get( Scripts::class )->set_preloading_tags();
		}, 10, 0 );
		add_action( 'wp_footer', function () use ( $container ) {
			$container->get( Scripts::class )->add_early_polyfills();
		}, 10, 0 );
		add_action( 'wp_enqueue_scripts', function () use ( $container ) {
			$container->get( Scripts::class )->enqueue_scripts();
		}, 10, 0 );
	}

	private function styles( ContainerInterface $container ) {
		add_action( 'wp_enqueue_scripts', function () use ( $container ) {
			$container->get( Styles::class )->enqueue_styles();
		}, 10, 0 );
	}

	private function third_party_tags( ContainerInterface $container ) {
		add_action( 'wp_head', function () use ( $container ) {
			$container->get( Third_Party_Tags::class )->inject_google_tag_manager_head_tag();
		} );
		add_action( 'tribe/body_opening_tag', function () use ( $container ) {
			$container->get( Third_Party_Tags::class )->inject_google_tag_manager_body_tag();
		} );
	}

	private function editor_styles( ContainerInterface $container ) {
		add_action( 'admin_init', function () use ( $container ) {
			$container->get( Editor_Styles::class )->block_editor_styles();
		}, 10, 0 );
		add_filter( 'tiny_mce_before_init', function ( $settings ) use ( $container ) {
			return $container->get( Editor_Styles::class )->visual_editor_body_class( $settings );
		}, 10, 1 );
		add_filter( 'editor_stylesheets', function( $styles ) use ( $container ) {
			return $container->get( Editor_Styles::class )->visual_editor_styles( $styles );
		}, 10, 1 );
	}

	private function editor_formats( ContainerInterface $container ) {
		add_filter( 'mce_buttons', function ( $settings ) use ( $container ) {
			return $container->get( Editor_Formats::class )->mce_buttons( $settings );
		}, 10, 1 );
		add_filter( 'tiny_mce_before_init', function ( $settings ) use ( $container ) {
			return $container->get( Editor_Formats::class )->visual_editor_styles_dropdown( $settings );
		}, 10, 1 );
	}

	private function nav_attributes( ContainerInterface $container ) {
		add_filter( 'nav_menu_item_id', function ( $menu_id, $item, $args, $depth ) use ( $container ) {
			return $container->get( Nav_Attribute_Filters::class )->customize_nav_item_id( $menu_id, $item, $args, $depth );
		}, 10, 4 );

		add_filter( 'nav_menu_css_class', function ( $classes, $item, $args, $depth ) use ( $container ) {
			return $container->get( Nav_Attribute_Filters::class )->customize_nav_item_classes( $classes, $item, $args, $depth );
		}, 10, 4 );

		add_filter( 'nav_menu_link_attributes', function ( $atts, $item, $args, $depth ) use ( $container ) {
			return $container->get( Nav_Attribute_Filters::class )->customize_nav_item_anchor_atts( $atts, $item, $args, $depth );
		}, 10, 4 );
	}

	private function gravity_forms( ContainerInterface $container ) {
		add_action( 'init', function () use ( $container ) {
			$container->get( Gravity_Forms_Filter::class )->hook();
		}, 10, 0 );
	}

}
