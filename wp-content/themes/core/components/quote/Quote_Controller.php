<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\quote;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\image\Image_Controller;
use Tribe\Project\Theme\Config\Image_Sizes;

class Quote_Controller extends Abstract_Controller {

	public const ATTRS      = 'attrs';
	public const CITE_IMAGE = 'cite_image';
	public const CITE_NAME  = 'cite_name';
	public const CITE_TITLE = 'cite_title';
	public const CLASSES    = 'classes';
	public const QUOTE_TEXT = 'quote_text';

	private int $cite_image;

	/**
	 * @var string[]
	 */
	private array $attrs;

	/**
	 * @var string[]
	 */
	private array $classes;
	private string $cite_name;
	private string $cite_title;
	private string $quote_text;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->attrs      = (array) $args[ self::ATTRS ];
		$this->cite_image = (int) $args[ self::CITE_IMAGE ];
		$this->cite_name  = (string) $args[ self::CITE_NAME ];
		$this->cite_title = (string) $args[ self::CITE_TITLE ];
		$this->classes    = (array) $args[ self::CLASSES ];
		$this->quote_text = (string) $args[ self::QUOTE_TEXT ];
	}

	public function get_classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function has_citation(): bool {
		return ! ( empty( $this->cite_name ) && empty( $this->cite_title ) && empty( $this->cite_image ) );
	}

	public function get_image_args(): array {
		if ( empty( $this->cite_image ) ) {
			return [];
		}

		return [
			Image_Controller::IMG_ID      => $this->cite_image,
			Image_Controller::WRAPPER_TAG => 'span',
			Image_Controller::CLASSES     => [ 'c-quote__cite-figure' ],
			Image_Controller::IMG_CLASSES => [ 'c-quote__cite-image' ],
			Image_Controller::SRC_SIZE    => Image_Sizes::SQUARE_XSMALL,
		];
	}

	public function get_quote(): string {
		return $this->quote_text;
	}

	public function get_cite_name(): string {
		return $this->cite_name;
	}

	public function get_cite_title(): string {
		return $this->cite_title;
	}

	protected function defaults(): array {
		return [
			self::ATTRS      => [],
			self::CITE_IMAGE => 0,
			self::CITE_NAME  => '',
			self::CITE_TITLE => '',
			self::CLASSES    => [],
			self::QUOTE_TEXT => '',
		];
	}

	protected function required(): array {
		return [
			self::CLASSES => [ 'c-quote' ],
		];
	}

}
