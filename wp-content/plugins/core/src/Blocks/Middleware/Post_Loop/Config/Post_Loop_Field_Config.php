<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Post_Loop\Config;

use Tribe\Libs\Field_Models\Field_Model;
use Tribe\Project\Post_Types\Post\Post;
use Tribe\Project\Taxonomies\Category\Category;
use Tribe\Project\Taxonomies\Post_Tag\Post_Tag;
use UnexpectedValueException;

/**
 * Developer configuration for the Post Loop field.
 *
 * @note The values of these properties are their defaults.
 *
 * @property array $hide_fields
 */
class Post_Loop_Field_Config extends Field_Model {

	public const OPTION_BOTH   = 'both';
	public const OPTION_MANUAL = 'manual';
	public const OPTION_QUERY  = 'query';

	public const QUERY_TYPES = [
		self::OPTION_BOTH   => true,
		self::OPTION_MANUAL => true,
		self::OPTION_QUERY  => true,
	];

	/**
	 * Automatically populated with the $block::NAME constant value when
	 * the middleware is processed to build ACF keys.
	 */
	public string $block_name = '';

	/**
	 * The ACF group/field/section where the Post Loop Fields will appear
	 * under.
	 */
	public string $group = '';

	/**
	 * The name of the field, generally a constant you created in a
	 * Block_Config.
	 */
	public string $field_name = '';

	/**
	 * Which query types are available for the user to select.
	 */
	public string $available_types = self::OPTION_BOTH;

	/**
	 * The user can only dynamically query by the following
	 * post types.
	 *
	 * @var string[]
	 */
	public array $post_types = [
		Post::NAME,
	];

	/**
	 * The user can only select manual posts from these
	 * post types.
	 *
	 * @var string[]
	 */
	public array $post_types_manual = [
		Post::NAME,
	];

	/**
	 * The taxonomies available to filter by when in query mode.
	 *
	 * @var string[]
	 */
	public array $taxonomies = [
		Post_Tag::NAME,
		Category::NAME,
	];

	/**
	 * The minimum number of posts a user can limit the display to in
	 * dynamic query or manual post creation modes.
	 */
	public int $limit_min = 1;

	/**
	 * The maximum number of posts a user can limit the display to in
	 * dynamic query or manual post creation modes.
	 */
	public int $limit_max = 5;

	/**
	 * The default number of posts to display when in dynamic
	 * query mode or number of manual post repeaters in manual mode.
	 */
	public int $query_limit = 5;

	/**
	 * The button label to add a new manual row.
	 */
	public string $button_label = '';

	/**
	 * The recommended image size instructions for the Image field.
	 */
	public string $image_instructions = '';

	/**
	 * Hides fields in this list.
	 *
	 * @example [ Post_Loop_Field_Middleware::MANUAL_EXCERPT, ]
	 *
	 * @var array<string, int> After the array has been flipped.
	 */
	protected array $hide_fields = [];

	/**
	 * Set any extra defaults and validate the data before passing on
	 * to the parent instance.
	 *
	 * @param array $parameters
	 *
	 * @throws \UnexpectedValueException
	 */
	public function __construct( array $parameters = [] ) {
		$parameters = $this->set_defaults( $parameters );
		parent::__construct( $parameters );
		$this->validate_data();
	}

	/**
	 * Set default parameters where the property needs additional processing.
	 *
	 * @param array $parameters
	 *
	 * @return array
	 */
	protected function set_defaults( array $parameters ): array {
		$parameters['button_label'] ??= esc_html__( 'Add Post', 'tribe' );

		return $parameters;
	}

	/**
	 * Validate enums for the correct data.
	 *
	 * @throws \UnexpectedValueException
	 */
	protected function validate_data(): void {
		$query_type = self::QUERY_TYPES[ $this->available_types ] ?? false;

		if ( ! $query_type ) {
			throw new UnexpectedValueException(
				sprintf( 'Invalid query type: %s', $this->available_types )
			);
		}
	}

	/**
	 * Modify values before they're set on certain properties.
	 *
	 * @param string $name
	 * @param mixed  $value
	 *
	 * @return void
	 */
	public function __set( string $name, $value ): void {
		if ( $name !== 'hide_fields' ) {
			return;
		}

		// Allow O(n) lookups for hidden fields.
		$this->hide_fields = array_flip( (array) $value );
	}

	/**
	 * Get allowed non-visible properties.
	 *
	 * @param string $name
	 *
	 * @return array|void
	 */
	public function __get( string $name ) {
		if ( $name !== 'hide_fields' ) {
			return;
		}

		return $this->hide_fields;
	}

}
