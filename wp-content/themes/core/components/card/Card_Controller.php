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

class Card_Controller extends Abstract_Controller {
	public const TAG            = 'tag';
	public const CLASSES        = 'classes';
	public const ATTRS          = 'attrs';
	public const IMAGE          = 'image';
	public const META_PRIMARY   = 'meta_primary';
	public const META_SECONDARY = 'meta_secondary';
	public const TITLE          = 'title';
	public const DESCRIPTION    = 'description';
	public const CTA            = 'cta';

	private string $tag;
	private array  $classes;
	private array  $attrs;

	/**
	 * @var null|Deferred_Component
	 * @uses components/image
	 */
	private ?Deferred_Component $image;

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
	 * @uses components/container
	 */
	private ?Deferred_Component $description;

	/**
	 * @var null|Deferred_Component
	 * @uses components/link
	 */
	private ?Deferred_Component $cta;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->tag            = (string) $args[ self::TAG ];
		$this->classes        = (array) $args[ self::CLASSES ];
		$this->attrs          = (array) $args[ self::ATTRS ];
		$this->image          = $args[ self::IMAGE ];
		$this->meta_primary   = $args[ self::META_PRIMARY ];
		$this->meta_secondary = $args[ self::META_SECONDARY ];
		$this->title          = $args[ self::TITLE ];
		$this->description    = $args[ self::DESCRIPTION ];
		$this->cta            = $args[ self::CTA ];
	}

	protected function defaults(): array {
		return [
			self::TAG            => 'article',
			self::CLASSES        => [],
			self::ATTRS          => [],
			self::IMAGE          => null,
			self::META_PRIMARY   => null,
			self::META_SECONDARY => null,
			self::TITLE          => null,
			self::DESCRIPTION    => null,
			self::CTA            => null,
		];
	}

	protected function required(): array {
		return [
			self::CLASSES => [ 'c-card' ],
		];
	}

	public function get_tag(): string {
		return tag_escape( $this->tag );
	}

	public function get_classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	/**
	 * @return Deferred_Component|null
	 */
	public function render_image() {
		if ( empty( $this->image ) ) {
			return null;
		}

		$this->image[ Image_Controller::CLASSES ][] = 'c-card__image';

		return $this->image;
	}

	/**
	 * @return Deferred_Component|null
	 */
	public function render_meta_primary() {
		if ( empty( $this->meta_primary ) ) {
			return null;
		}

		$this->meta_primary[ Container_Controller::CLASSES ][] = 'c-card__meta';
		$this->meta_primary[ Container_Controller::CLASSES ][] = 'c-card__meta--primary';

		return $this->meta_primary;
	}

	/**
	 * @return Deferred_Component|null
	 */
	public function render_meta_secondary() {
		if ( empty( $this->meta_secondary ) ) {
			return null;
		}

		$this->meta_secondary[ Container_Controller::CLASSES ][] = 'c-card__meta';
		$this->meta_secondary[ Container_Controller::CLASSES ][] = 'c-card__meta--secondary';

		return $this->meta_secondary;
	}

	/**
	 * @return Deferred_Component|null
	 */
	public function render_title() {
		if ( empty( $this->title ) ) {
			return null;
		}

		$this->title[ Text_Controller::CLASSES ][] = 'c-card__title';

		return $this->title;
	}

	/**
	 * @return Deferred_Component|null
	 */
	public function render_description() {
		if ( empty( $this->description ) ) {
			return null;
		}

		$this->description[ Container_Controller::CLASSES ][] = 'c-card__description';

		return $this->description;
	}

	public function get_cta_args() {
		if ( empty( $this->cta['url'] ) ) {
			return [];
		}

		$this->cta[ Link_Controller::CLASSES ][] = 'c-card__cta-link';

		return [
			Container_Controller::TAG     => 'p',
			Container_Controller::CLASSES => [ 'c-card__cta' ],
			Container_Controller::CONTENT => $this->cta->render(),
		];
	}
}
