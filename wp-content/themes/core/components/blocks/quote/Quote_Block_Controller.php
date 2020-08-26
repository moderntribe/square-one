<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\blocks\quote;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Blocks\Types\Quote\Quote as Quote_Block;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\quote\Quote_Controller;
use Tribe\Project\Templates\Components\image\Image_Controller;
use Tribe\Project\Templates\Models\Image;
use Tribe\Project\Theme\Config\Image_Sizes;

class Quote_Block_Controller extends Abstract_Controller {
	public const LAYOUT            = 'layout';
	public const MEDIA             = 'media';
	public const QUOTE_TEXT        = 'quote_text';
	public const CITE_NAME         = 'cite_name';
	public const CITE_TITLE        = 'cite_title';
	public const CITE_IMAGE        = 'cite_image';
	public const CONTAINER_CLASSES = 'container_classes';
	public const MEDIA_CLASSES     = 'media_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';

	private string $layout;
	private int    $media;
	private string $cite_name;
	private string $cite_title;
	private int    $cite_image;
	private string $quote_text;
	private array  $container_classes;
	private array  $media_classes;
	private array  $content_classes;
	protected array  $classes;
	protected array  $attrs;

	/**
	 * @param array $args
	 */
	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->layout            = (string) $args[ self::LAYOUT ];
		$this->media             = (int) $args[ self::MEDIA ];
		$this->cite_name         = (string) $args[ self::CITE_NAME ];
		$this->cite_title        = (string) $args[ self::CITE_TITLE ];
		$this->cite_image        = (int) $args[ self::CITE_IMAGE ];
		$this->quote_text        = (string) $args[ self::QUOTE_TEXT ];
		$this->container_classes = (array) $args[ self::CONTAINER_CLASSES ];
		$this->media_classes     = (array) $args[ self::MEDIA_CLASSES ];
		$this->content_classes   = (array) $args[ self::CONTENT_CLASSES ];
		$this->classes           = (array) $args[ self::CLASSES ];
		$this->attrs             = (array) $args[ self::ATTRS ];
	}

	/**
	 * @return array
	 */
	protected function defaults(): array {
		return [
			self::LAYOUT            => Quote_Block::MEDIA_OVERLAY,
			self::MEDIA             => 0,
			self::QUOTE_TEXT        => '',
			self::CITE_NAME         => '',
			self::CITE_TITLE        => '',
			self::CITE_IMAGE        => 0,
			self::CONTAINER_CLASSES => [],
			self::MEDIA_CLASSES     => [],
			self::CONTENT_CLASSES   => [],
			self::CLASSES           => [],
			self::ATTRS             => [],
		];
	}

	/**
	 * @return array
	 */
	protected function required(): array {
		return [
			self::CONTAINER_CLASSES => [ 'b-quote__container', 'l-container' ],
			self::MEDIA_CLASSES     => [ 'b-quote__media' ],
			self::CONTENT_CLASSES   => [ 'b-quote__content', 't-theme--light' ],
			self::CLASSES           => [ 'c-block', 'b-quote', 'c-block--full-bleed' ],
		];
	}

	/**
	 * @return string
	 */
	public function get_classes(): string {
		$this->classes[] = 'c-block--layout-' . $this->layout;

		return Markup_Utils::class_attribute( $this->classes );
	}

	/**
	 * @return string
	 */
	public function get_attrs(): string {
		return Markup_Utils::class_attribute( $this->attrs );
	}

	/**
	 * @return string
	 */
	public function get_container_classes(): string {
		return Markup_Utils::class_attribute( $this->container_classes );
	}

	/**
	 * @return string
	 */
	public function get_media_classes(): string {
		return Markup_Utils::class_attribute( $this->media_classes );
	}

	/**
	 * @return string
	 */
	public function get_content_classes(): string {
		return Markup_Utils::class_attribute( $this->content_classes );
	}

	/**
	 * @return bool
	 */
	public function has_image(): bool {
		return (bool) $this->media;
	}

	/**
	 * @return array
	 */
	public function get_media_args(): array {
		if ( ! $this->has_image() ) {
			return [];
		}

		$classes       = [ 'b-quote__figure', 'c-image--bg' ];
		$src           = Image_Sizes::FOUR_THREE;
		$srcset        = [
			Image_Sizes::FOUR_THREE_SMALL,
			Image_Sizes::FOUR_THREE,
			Image_Sizes::FOUR_THREE_LARGE,
		];

		if ( $this->layout === Quote_Block::MEDIA_OVERLAY ) {
			$classes[]     = 'c-image--overlay';
			$src           = Image_Sizes::SIXTEEN_NINE;
			$srcset        = [
				Image_Sizes::SIXTEEN_NINE_SMALL,
				Image_Sizes::SIXTEEN_NINE,
				Image_Sizes::SIXTEEN_NINE_LARGE,
			];
		}

		return [
			Image_Controller::ATTACHMENT   => Image::factory( (int) $this->media ),
			Image_Controller::AS_BG        => true,
			Image_Controller::USE_LAZYLOAD => true,
			Image_Controller::CLASSES      => $classes,
			Image_Controller::IMG_CLASSES  => [ 'b-quote__img' ],
			Image_Controller::SRC_SIZE     => $src,
			Image_Controller::SRCSET_SIZES => $srcset,
		];
	}

	/**
	 * @return array
	 */
	public function get_quote_args(): array {
		return [
			Quote_Controller::CITE_IMAGE => $this->cite_image,
			Quote_Controller::QUOTE_TEXT => $this->quote_text,
			Quote_Controller::CITE_NAME  => $this->cite_name,
			Quote_Controller::CITE_TITLE => $this->cite_title,
		];
	}

}
