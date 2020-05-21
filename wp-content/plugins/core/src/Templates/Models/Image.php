<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Models;

class Image {
	private const CACHE_GROUP = 'tribe_image_model';

	/** @var \WP_Post $attachment */
	private $attachment;

	/** @var string */
	private $title;

	/** @var string */
	private $alt;

	/** @var int */
	private $width;

	/** @var int */
	private $height;

	/** @var Image_Derivative[] */
	private $sizes = [];

	public function __construct( \WP_Post $attachment, string $title, string $alt, int $width, int $height, array $sizes ) {
		$this->attachment = $attachment;
		$this->title      = $title;
		$this->alt        = $alt;
		$this->width      = $width;
		$this->height     = $height;
		$this->sizes      = $sizes;
	}

	public function has_size( $size ): bool {
		return array_key_exists( $size, $this->sizes );
	}

	public function get_size( $size ): Image_Derivative {
		return $this->has_size( $size ) ? $this->sizes[ $size ] : new Image_Derivative();
	}

	public function width(): int {
		return $this->width;
	}

	public function height(): int {
		return $this->height;
	}

	public function get_attachment_post(): \WP_Post {
		return $this->attachment;
	}

	public function title(): string {
		return $this->title;
	}

	public function alt(): string {
		return $this->alt;
	}

	public static function factory( int $attachment_id ): self {
		$attachment = get_post( $attachment_id );
		if ( ! $attachment || $attachment->post_type !== 'attachment' ) {
			throw new \InvalidArgumentException( 'Invalid attachment ID' );
		}
		$title = get_the_title( $attachment_id );
		$alt   = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );

		$metadata = wp_get_attachment_metadata( $attachment_id );
		$sizes    = self::build_image_derivatives( $attachment_id );

		return new self( $attachment, $title, $alt, $metadata['width'], $metadata['height'], $sizes );
	}

	private static function build_image_derivatives( $attachment_id ): array {
		$cached           = wp_cache_get( $attachment_id, self::CACHE_GROUP );
		$sizes            = $cached ? unserialize( $cached, [ 'allowed_classes' => [ Image_Derivative::class ] ] ) : [];
		if ( is_array( $sizes ) && ! empty( $sizes ) ) {
			return $sizes;
		}
		$registered_sizes = wp_get_additional_image_sizes();
		foreach ( array_merge( get_intermediate_image_sizes(), [ 'full' ] ) as $size ) {
			$src = wp_get_attachment_image_src( $attachment_id, $size );
			if ( ! $src ) {
				continue;
			}
			if ( array_key_exists( $size, $registered_sizes ) ) {
				if ( $registered_sizes[ $size ]['crop'] ) {
					$match = ( $src[1] == $registered_sizes[ $size ]['width'] && $src[2] == $registered_sizes[ $size ]['height'] );
				} else {
					$match = ( $src[1] == $registered_sizes[ $size ]['width'] || $src[2] == $registered_sizes[ $size ]['height'] );
				}
			} else {
				$match = false;
			}

			$sizes[ $size ] = new Image_Derivative(
				$size,
				$src[0], // src
				$src[1], // width
				$src[2], // height
				(bool) $src[3], // is_intermediate
				$match
			);
		}

		wp_cache_set( $attachment_id, serialize( $sizes ), self::CACHE_GROUP, HOUR_IN_SECONDS );

		return $sizes;
	}
}
