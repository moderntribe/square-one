<?php declare(strict_types=1);

namespace Tribe\Project\Theme\Config;

/**
 * @TODO rename this to a proper class name.
 */
class Gradients {

	/**
	 * @var string[][]
	 */
	private array $gradients;

	/**
	 * Colors constructor.
	 *
	 * @param array $gradients An array of gradient definitions.
	 *                         Keys should be unique identifiers for the gradients.
	 *                         Values should contain an array of:
	 *                         - gradient - string - a CSS gradient definition
	 *                         - label    - string - the translated label for the gradient
	 */
	public function __construct( array $gradients ) {
		$this->gradients = $gradients;
	}

	/**
	 * Get a list of gradient options.
	 *
	 * @return string[][]
	 */
	public function get_gradients(): array {
		return $this->gradients;
	}

	/**
	 * Return a subset of the gradient list.
	 *
	 * @param string[] $gradient_keys
	 *
	 * @return self
	 */
	public function get_subset( array $gradient_keys = [] ): self {
		return new self( array_intersect_key( $this->gradients, array_flip( $gradient_keys ) ) );
	}

	/**
	 * Formats the gradients as `[ 'name' => <label>, 'slug' => <key>, 'gradient' => <gradient> ]`
	 *
	 * This is the format expected by the block editor.
	 *
	 * @return array<int, array<string, (int|string)>>
	 *
	 * @see https://developer.wordpress.org/block-editor/developers/themes/theme-support/#block-gradient-presets
	 */
	public function format_for_blocks(): array {
		$gradients = [];
		foreach ( $this->gradients as $key => $value ) {
			$gradients[] = [
				'name'     => $value['label'],
				'slug'     => $key,
				'gradient' => $value['gradient'],
			];
		}

		return $gradients;
	}

}
