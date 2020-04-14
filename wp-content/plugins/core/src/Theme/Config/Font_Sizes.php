<?php
declare( strict_types=1 );

namespace Tribe\Project\Theme\Config;

class Font_Sizes {
	/**
	 * @var array
	 */
	private $sizes;

	/**
	 * Colors constructor.
	 *
	 * @param array $sizes An array of size definitions.
	 *                     Keys should be unique identifiers for the sizes.
	 *                     Values should contain an array of:
	 *                     - size  - int - the font size, in pixels
	 *                     - label - string - the translated label for the size
	 */
	public function __construct( array $sizes ) {
		$this->sizes = $sizes;
	}

	/**
	 * Get a list of size options.
	 *
	 * @return array
	 */
	public function get_sizes(): array {
		return $this->sizes;
	}

	/**
	 * Return a subset of the size list.
	 *
	 * @param string[] $size_keys
	 *
	 * @return self
	 */
	public function get_subset( array $size_keys = [] ): self {
		return new self( array_intersect_key( $this->sizes, array_flip( $size_keys ) ) );
	}

	/**
	 * Formats the sizes as `[ 'name' => <label>, 'slug' => <key>, 'size' => <size> ]`
	 *
	 * This is the format expected by the block editor.
	 *
	 * @return array
	 * @see https://developer.wordpress.org/block-editor/developers/themes/theme-support/#block-font-sizes
	 */
	public function format_for_blocks(): array {
		$sizes = [];
		foreach ( $this->sizes as $key => $value ) {
			$sizes[] = [
				'name' => $value['label'],
				'slug' => $key,
				'size' => $value['size'],
			];
		}

		return $sizes;
	}
}
