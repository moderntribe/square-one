<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\card;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\container\Container_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;
use Tribe\Project\Templates\Components\image\Image_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;
use Tribe\Project\Templates\Models\Image;
use Tribe\Project\Theme\Config\Image_Sizes;

class Card_Controller extends Abstract_Controller {
	public const TAG                   = 'tag';
	public const CLASSES               = 'classes';
	public const ATTRS                 = 'attrs';
	public const LAYOUT                = 'layout';
	public const MEDIA_POSITION        = 'media_position';
	public const VARIATION             = 'variation';
	public const MEDIA_WRAPPER_CLASSES = 'media_wrapper_classes';
	public const BODY_WRAPPER_CLASSES  = 'body_wrapper_classes';
	public const IMAGE                 = 'image';
	public const META_PRIMARY          = 'meta_primary';
	public const META_SECONDARY        = 'meta_secondary';
	public const TITLE                 = 'title';
	public const CONTENT               = 'content';
	public const CTA                   = 'cta';

	public const MEDIA_TOP    = 'top';
	public const MEDIA_RIGHT  = 'right';
	public const MEDIA_BOTTOM = 'bottom';
	public const MEDIA_LEFT   = 'left';
	public const MEDIA_BEHIND = 'behind';

	public const LAYOUT_STACKED = 'stacked';
	public const LAYOUT_INLINE  = 'inline';
	public const LAYOUT_OVERLAY = 'overlay';

	public const VARIANT_PLAIN    = 'plain';
	public const VARIANT_ELEVATED = 'elevated';
	public const VARIANT_OUTLINED = 'outlined';

	private string $tag;
	private array  $classes;
	private array  $attrs;
	private string $layout;
	private string $media_position;
	private string $variation;
	private array  $media_wrapper_classes;
	private array  $body_wrapper_classes;
	private int    $image;
	/**
	 * @var null|Deferred_Component
	 * @uses components/container
	 */
	private ?Deferred_Component $meta_primary;
	/**
	 * @var null|Deferred_Component
	 * @uses components/container
	 */
	private ?Deferred_Component $meta_secondary;
	/**
	 * @var null|Deferred_Component
	 * @uses components/text
	 */
	private ?Deferred_Component $title;
	/**
	 * @var null|Deferred_Component
	 * @uses components/text
	 */
	private ?Deferred_Component $content;
	/**
	 * @var null|Deferred_Component
	 * @uses components/link
	 */
	private ?Deferred_Component $cta;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->tag                   = (string) $args[ self::TAG ];
		$this->classes               = (array) $args[ self::CLASSES ];
		$this->attrs                 = (array) $args[ self::ATTRS ];
		$this->layout                = (string) $args[ self::LAYOUT ];
		$this->media_position        = (string) $args[ self::MEDIA_POSITION ];
		$this->variation             = (string) $args[ self::VARIATION ];
		$this->media_wrapper_classes = (array) $args[ self::MEDIA_WRAPPER_CLASSES ];
		$this->body_wrapper_classes  = (array) $args[ self::BODY_WRAPPER_CLASSES ];
		$this->image                 = (int) $args[ self::IMAGE ];
		$this->meta_primary          = $args[ self::META_PRIMARY ];
		$this->meta_secondary        = $args[ self::META_SECONDARY ];
		$this->title                 = $args[ self::TITLE ];
		$this->content               = $args[ self::CONTENT ];
		$this->cta                   = $args[ self::CTA ];
	}

	protected function defaults(): array {
		return [
			self::TAG                   => 'article',
			self::CLASSES               => [],
			self::ATTRS                 => [],
			self::LAYOUT                => self::LAYOUT_STACKED,
			self::MEDIA_POSITION        => self::MEDIA_TOP,
			self::VARIATION             => self::VARIANT_PLAIN,
			self::MEDIA_WRAPPER_CLASSES => [],
			self::BODY_WRAPPER_CLASSES  => [],
			self::IMAGE                 => null,
			self::META_PRIMARY          => null,
			self::META_SECONDARY        => null,
			self::TITLE                 => null,
			self::CONTENT               => null,
			self::CTA                   => null,
		];
	}

	protected function required(): array {
		return [
			self::CLASSES               => [ 'c-card' ],
			self::MEDIA_WRAPPER_CLASSES => [ 'c-card__media' ],
			self::BODY_WRAPPER_CLASSES  => [ 'c-card__body' ],
		];
	}

	public function get_tag(): string {
		return tag_escape( $this->tag );
	}

	public function get_classes(): string {
		$this->classes[] = sprintf( 'c-card--variant-%s', $this->variation );

		if ( ! empty( $this->image ) ) {
			$this->classes[] = sprintf( 'c-card--media-%s', $this->media_position );

			if ( $this->media_position === self::MEDIA_TOP || $this->media_position === self::MEDIA_BOTTOM ) {
				$this->classes[] = 'c-card--' . self::LAYOUT_STACKED;
			}

			if ( $this->media_position === self::MEDIA_LEFT || $this->media_position === self::MEDIA_RIGHT ) {
				$this->classes[] = 'c-card--' . self::LAYOUT_INLINE;
			}

			if ( $this->media_position === self::MEDIA_BEHIND ) {
				$this->classes[] = 'c-card--' . self::LAYOUT_OVERLAY;
			}
		}

		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_media_wrapper_classes(): string {
		return Markup_Utils::class_attribute( $this->media_wrapper_classes );
	}

	public function get_body_wrapper_classes(): string {
		return Markup_Utils::class_attribute( $this->body_wrapper_classes );
	}

	/**
	 * @return array
	 */
	public function get_image_args(): array {
		if ( empty( $this->image ) ) {
			return [];
		}

		return [
			Image_Controller::ATTACHMENT   => Image::factory( (int) $this->image ),
			Image_Controller::AS_BG        => true,
			Image_Controller::WRAPPER_TAG  => 'div',
			Image_Controller::CLASSES      => [ 'c-card__image', 'c-image--bg' ],
			Image_Controller::SRC_SIZE     => Image_Sizes::FOUR_THREE,
			Image_Controller::SRCSET_SIZES => [
				Image_Sizes::FOUR_THREE,
				Image_Sizes::FOUR_THREE_SMALL,
			],
		];
	}

	public function render_meta_primary(): array {
		if ( ! $this->meta_primary ) {
			return [];
		}

		return [
			Container_Controller::CLASSES => [ 'c-card__meta', 'c-card__meta--primary' ],
			Container_Controller::TAG     => 'div',
			Container_Controller::CONTENT => $this->meta_primary->render(),
		];
	}

	/**
	 * @return array
	 */
	public function render_meta_secondary(): array {
		if ( ! $this->meta_secondary ) {
			return [];
		}

		return [
			Container_Controller::CLASSES => [ 'c-card__meta', 'c-card__meta--secondary' ],
			Container_Controller::TAG     => 'div',
			Container_Controller::CONTENT => $this->meta_secondary->render(),
		];
	}

	/**
	 * @return Deferred_Component|null
	 */
	public function render_title() {
		$this->title[ Text_Controller::CLASSES ][] = 'c-card__title';

		return $this->title;
	}

	/**
	 * @return Deferred_Component|null
	 */
	public function render_content() {
		$this->content[ Text_Controller::CLASSES ][] = 'c-card__text';
		$this->content[ Text_Controller::CLASSES ][] = 'p';

		return $this->content;
	}

	/**
	 * @return array|Deferred_Component|null
	 */
	public function render_cta() {
		if ( empty( $this->cta[ 'url' ] ) ) {
			return [];
		}

		$this->cta[ Link_Controller::CLASSES ][] = 'c-card__action';
		$this->cta[ Link_Controller::CLASSES ][] = 'c-btn';

		return $this->cta;
	}
}
