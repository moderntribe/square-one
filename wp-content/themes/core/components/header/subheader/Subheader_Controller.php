<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\header\subheader;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;
use Tribe\Project\Templates\Components\Traits\Page_Title;
use Tribe\Project\Templates\Components\image\Image_Controller;
use Tribe\Project\Theme\Config\Image_Sizes;
use Tribe\Project\Templates\Components\breadcrumbs\Breadcrumbs_Controller;
use Tribe\Project\Templates\Models\Breadcrumb;

class Subheader_Controller extends Abstract_Controller {

	use Page_Title;

	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';
	public const CONTAINER_CLASSES = 'container_classes';
	public const MEDIA_CLASSES     = 'media_classes';
	public const CONTENT_CLASSES   = 'content_classes';

	private array  $classes;
	private array  $attrs;
	private array  $container_classes;
	private array  $media_classes;
	private array  $content_classes;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->classes           = (array) $args[ self::CLASSES ];
		$this->attrs             = (array) $args[ self::ATTRS ];
		$this->container_classes = (array) $args[ self::CONTAINER_CLASSES ];
		$this->media_classes     = (array) $args[ self::MEDIA_CLASSES ];
		$this->content_classes   = (array) $args[ self::CONTENT_CLASSES ];
	}

	protected function defaults(): array {
		return [
			self::CLASSES           => [],
			self::ATTRS             => [],
			self::CONTAINER_CLASSES => [],
			self::MEDIA_CLASSES     => [],
			self::CONTENT_CLASSES   => [],
		];
	}

	protected function required(): array {
		return [
			self::CONTAINER_CLASSES => [ 'l-container' ],
			self::MEDIA_CLASSES     => [ 'c-subheader__media' ],
			self::CONTENT_CLASSES   => [ 'c-subheader__content' ],
			self::CLASSES           => [ 'c-subheader' ],
		];
	}

	public function get_classes(): string {
		if ( has_post_thumbnail() ) {
			$this->classes[] = 'c-subheader--has-image';
		}

		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_title_args(): array {
		if ( empty( $this->get_page_title() ) ) {
			return [];
		}

		return [
			Text_Controller::TAG     => 'h1',
			Text_Controller::CLASSES => [ 'page-title', 'h1', 'c-subheader__title' ],
			Text_Controller::CONTENT => $this->get_page_title(),
		];
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
	 * @return array
	 */

	public function get_image_args(): array {
		if ( ! has_post_thumbnail() ) {
			return [];
		}

		return [
			Image_Controller::IMG_ID       => (int) get_post_thumbnail_id(),
			Image_Controller::AUTO_SHIM    => false,
			Image_Controller::USE_LAZYLOAD => true,
			Image_Controller::CLASSES      => [ 'c-image--overlay', 'c-image--object-fit' ],
			Image_Controller::IMG_CLASSES  => [ 'c-subheader__media__image' ],
			Image_Controller::SRC_SIZE     => Image_Sizes::SIXTEEN_NINE,
			Image_Controller::SRCSET_SIZES => [
				Image_Sizes::SIXTEEN_NINE,
				Image_Sizes::SIXTEEN_NINE_SMALL,
			],
		];
	}

	public function render_breadcrumbs(): void {

		get_template_part(
			'components/breadcrumbs/breadcrumbs',
			'null',
			[ Breadcrumbs_Controller::BREADCRUMBS => $this->get_breadcrumbs() ]
		);
	}

	protected function get_breadcrumbs(): array {
		$page = get_the_ID();
		$url  = $page ? get_permalink( $page ) : home_url();

		return [
			new Breadcrumb( $url, get_the_title( $page ) ),
		];
	}
}
