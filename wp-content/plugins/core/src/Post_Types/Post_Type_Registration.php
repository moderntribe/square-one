<?php

namespace Tribe\Project\Post_Types;

use Tribe\Project\Post_Types\Meta_Box_Handlers\Meta_Box_Handler_Interface;

class Post_Type_Registration {

	/**
	 * Registers a post type
	 *
	 * @param Post_Type_Config           $config
	 * @param Meta_Box_Handler_Interface $meta_box_handler
	 */
	public static function register( Post_Type_Config $config, Meta_Box_Handler_Interface $meta_box_handler = null ) {
		$callback         = self::build_registration_callback( $config, $meta_box_handler );

		// do not register until init
		if ( did_action( 'init' ) ) {
			$callback();
		} else {
			add_action( 'init', $callback, 10, 0 );
		}
	}

	/**
	 * Build the callback that will register a post type using the given config
	 *
	 * @param Post_Type_Config           $config
	 * @param Meta_Box_Handler_Interface $meta_box_handler
	 *
	 * @return \Closure
	 */
	private static function build_registration_callback( Post_Type_Config $config, Meta_Box_Handler_Interface $meta_box_handler = null ) {
		return function () use ( $config, $meta_box_handler ) {
			if ( empty( $config->post_type() ) ) {
				throw new \RuntimeException( 'Invalid configuration. Specify a post type.' );
			}

			$args = $config->get_args();
			$labels = empty( $args[ 'labels' ] ) ? [] : $args[ 'labels' ];
			//allow adding more than 3 labels to get_labels
			$args[ 'labels' ] = wp_parse_args( $labels, $config->get_labels() );
			register_extended_post_type( $config->post_type(), $args, $config->get_labels() );


			// The meta box handler is supposed to handle any hooking, meta box and field registration...
			if ( $meta_box_handler !== null ) {
				$meta_box_handler->register_meta_boxes( $config );
			} else {
				// ...otherwise we'll cover the bases with CMB2 and ACF
				add_filter( 'cmb2_meta_boxes', function ( $meta_boxes ) use ( $config ) {
					$post_type_meta_boxes = $config->get_meta_boxes();
					$post_type_meta_boxes = apply_filters( "tribe_{$config->post_type()}_meta_boxes", $post_type_meta_boxes );
					$meta_boxes           = array_merge( $meta_boxes, $post_type_meta_boxes );

					return $meta_boxes;
				} );

				// acf fires `acf/include_fields` too early to use a callback here, at init:5
				if ( function_exists( 'acf_add_local_field_group' ) ) {
					$meta_boxes = $config->get_acf_fields();
					foreach ( $meta_boxes as $box ) {
						acf_add_local_field_group( $box );
					}
				}
			}
		};
	}
}
