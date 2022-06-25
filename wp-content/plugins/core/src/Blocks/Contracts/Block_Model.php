<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Contracts;

use Tribe\Libs\Field_Models\Field_Model;

/**
 * @TODO add an interface for this.
 * @TODO move to square1-field-models or tribe-libs if we get it to php7.4.
 */
class Block_Model extends Field_Model {

	/**
	 * The assigned block ID by ACf.
	 */
	public string $id = '';

	/**
	 * Any classes entered into the block editor.
	 */
	public string $classes = '';

	/**
	 * The current mode, e.g. 'preview'
	 */
	public string $mode = '';

	/**
	 * Any anchor link added.
	 */
	public string $anchor = '';

	/**
	 * The raw block attributes.
	 *
	 * @var string[]
	 */
	public array $attrs = [];

	/**
	 * @param mixed[] $fields The collection of ACF block fields.
	 */
	public function __construct( array $fields = [] ) {

		$fields['classes'] ??= $fields['className'] ?? '';

		$fields = (array) apply_filters( 'tribe/project/blocks/block_model/fields', $fields );

		parent::__construct( $fields );
	}

	/**
	 * @TODO I don't actually think we need this anymore.
	 *
	 * @param int|string $key
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public function get( $key, $default = false ) {
		$value = get_field( $key );

		//check to support nullable type properties in components.
		// ACF will in some cases return and empty string when we may want it to be null.
		// This allows us to always determine the default.
		return ! empty( $value )
			? $value
			: $default;
	}

	/**
	 * Get the "Additional Class Names" value from the block editor.
	 *
	 * @return string[]
	 */
	public function get_classes(): array {
		return explode( ' ', $this->classes );
	}

	/**
	 * Get any block attributes from the block editor.
	 *
	 * @return array|string[]
	 */
	public function get_attrs(): array {
		$attrs = [];

		// "HTML Anchor" attribute
		if ( ! empty( $this->anchor ) ) {
			$attrs['id'] = $this->anchor;
		}

		return $attrs;
	}

	public function is_preview(): bool {
		return $this->mode === 'preview';
	}

}
