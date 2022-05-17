<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\quote;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\image\Image_Controller;
use Tribe\Project\Templates\Models\Quote;
use Tribe\Project\Theme\Config\Image_Sizes;

class Quote_Controller extends Abstract_Controller {

	public const ATTRS   = 'attrs';
	public const QUOTE   = 'quote';
	public const CLASSES = 'classes';

	/**
	 * @var string[]
	 */
	private array $attrs;

	/**
	 * @var string[]
	 */
	private array $classes;
	private Quote $quote;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->attrs   = (array) $args[ self::ATTRS ];
		$this->quote   = $args[ self::QUOTE ];
		$this->classes = (array) $args[ self::CLASSES ];
	}

	public function get_classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function has_quote(): bool {
		return (bool) array_filter(
			$this->quote->except( 'cite_image' )->toArray(),
			static fn( $v ) =>
				is_array( $v ) ? array_filter( $v ) : ! empty( $v )
		);
	}

	public function get_quote(): Quote {
		return $this->quote;
	}

	public function get_image_args(): array {
		$image_id = $this->quote->cite_image->id;

		if ( empty( $image_id ) ) {
			return [];
		}

		return [
			Image_Controller::IMG_ID      => $image_id,
			Image_Controller::WRAPPER_TAG => 'span',
			Image_Controller::CLASSES     => [ 'c-quote__cite-figure' ],
			Image_Controller::IMG_CLASSES => [ 'c-quote__cite-image' ],
			Image_Controller::SRC_SIZE    => Image_Sizes::SQUARE_XSMALL,
		];
	}

	protected function defaults(): array {
		return [
			self::ATTRS   => [],
			self::QUOTE   => new Quote(),
			self::CLASSES => [],
		];
	}

	protected function required(): array {
		return [
			self::CLASSES => [ 'c-quote' ],
		];
	}

}
