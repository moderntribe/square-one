<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\blocks\quote;

use Tribe\Libs\Field_Models\Models\Image;
use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Blocks\Types\Quote\Quote as Quote_Block;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\image\Image_Controller;
use Tribe\Project\Templates\Components\quote\Quote_Controller;
use Tribe\Project\Templates\Models\Quote;
use Tribe\Project\Theme\Config\Image_Sizes;

class Quote_Block_Controller extends Abstract_Controller {

	public const ATTRS             = 'attrs';
	public const QUOTE             = 'quote';
	public const CLASSES           = 'classes';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const LAYOUT            = 'layout';
	public const MEDIA             = 'media';
	public const MEDIA_CLASSES     = 'media_classes';

	private Image $media;

	/**
	 * @var string[]
	 */
	private array $attrs;

	/**
	 * @var string[]
	 */
	private array $classes;

	/**
	 * @var string[]
	 */
	private array $container_classes;

	/**
	 * @var string[]
	 */
	private array $content_classes;

	/**
	 * @var string[]
	 */
	private array $media_classes;
	private string $layout;
	private Quote $quote;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->attrs             = (array) $args[ self::ATTRS ];
		$this->classes           = (array) $args[ self::CLASSES ];
		$this->container_classes = (array) $args[ self::CONTAINER_CLASSES ];
		$this->content_classes   = (array) $args[ self::CONTENT_CLASSES ];
		$this->layout            = (string) $args[ self::LAYOUT ];
		$this->media             = $args[ self::MEDIA ];
		$this->media_classes     = (array) $args[ self::MEDIA_CLASSES ];
		$this->quote             = $args[ self::QUOTE ];
	}

	public function get_classes(): string {
		$this->classes[] = 'c-block--layout-' . $this->layout;

		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_container_classes(): string {
		return Markup_Utils::class_attribute( $this->container_classes );
	}

	public function get_media_classes(): string {
		return Markup_Utils::class_attribute( $this->media_classes );
	}

	public function get_content_classes(): string {
		return Markup_Utils::class_attribute( $this->content_classes );
	}

	public function has_image(): bool {
		return (bool) $this->media->id;
	}

	public function get_media_args(): array {
		if ( ! $this->has_image() ) {
			return [];
		}

		$classes = [ 'b-quote__figure', 'c-image--bg' ];
		$src     = Image_Sizes::FOUR_THREE;
		$srcset  = [
			Image_Sizes::FOUR_THREE_SMALL,
			Image_Sizes::FOUR_THREE,
			Image_Sizes::FOUR_THREE_LARGE,
		];

		if ( $this->layout === Quote_Block::MEDIA_OVERLAY ) {
			$classes[] = 'c-image--overlay';
			$src       = Image_Sizes::SIXTEEN_NINE;
			$srcset    = [
				Image_Sizes::SIXTEEN_NINE_SMALL,
				Image_Sizes::SIXTEEN_NINE,
				Image_Sizes::SIXTEEN_NINE_LARGE,
			];
		}

		return [
			Image_Controller::IMG_ID       => $this->media->id,
			Image_Controller::AS_BG        => true,
			Image_Controller::AUTO_SHIM    => false,
			Image_Controller::USE_LAZYLOAD => true,
			Image_Controller::CLASSES      => $classes,
			Image_Controller::IMG_CLASSES  => [ 'b-quote__img' ],
			Image_Controller::SRC_SIZE     => $src,
			Image_Controller::SRCSET_SIZES => $srcset,
		];
	}

	public function get_quote_args(): array {
		return [
			Quote_Controller::QUOTE => $this->quote,
		];
	}

	protected function defaults(): array {
		return [
			self::ATTRS             => [],
			self::QUOTE             => new Quote(),
			self::CLASSES           => [],
			self::CONTAINER_CLASSES => [],
			self::CONTENT_CLASSES   => [],
			self::LAYOUT            => Quote_Block::MEDIA_OVERLAY,
			self::MEDIA             => new Image(),
			self::MEDIA_CLASSES     => [],
		];
	}

	protected function required(): array {
		return [
			self::CLASSES           => [ 'c-block', 'b-quote', 'c-block--full-bleed' ],
			self::CONTAINER_CLASSES => [ 'b-quote__container', 'l-container' ],
			self::CONTENT_CLASSES   => [ 'b-quote__content', 't-theme--light' ],
			self::MEDIA_CLASSES     => [ 'b-quote__media' ],
		];
	}

}
