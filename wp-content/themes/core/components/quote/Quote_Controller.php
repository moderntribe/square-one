<?php

namespace Tribe\Project\Templates\Components\quote;

use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Models\Image;
use Tribe\Project\Theme\Config\Image_Sizes;

class Quote_Controller extends Abstract_Controller {
	public const CLASSES    = 'classes';
	public const ATTRS      = 'attrs';
	public const QUOTE_TEXT = 'quote_text';
	public const CITE_NAME  = 'cite_name';
	public const CITE_TITLE = 'cite_title';
	public const CITE_IMAGE = 'cite_image';

	private array $classes;
	private array $attrs;
	private string $quote_text;
	private string $cite_name;
	private string $cite_title;
	private int $cite_image;

	public function __construct( array $args = [] ) {
		$args             = $this->parse_args( $args );
		$this->classes    = (array) $args[ self::CLASSES ];
		$this->attrs      = (array) $args[ self::ATTRS ];
		$this->quote_text = $args[ self::QUOTE_TEXT ];
		$this->cite_name  = $args[ self::CITE_NAME ];
		$this->cite_title = $args[ self::CITE_TITLE ];
		$this->cite_image = $args[ self::CITE_IMAGE ];
	}

	protected function defaults(): array {
		return [
			self::CLASSES    => [],
			self::ATTRS      => [],
			self::QUOTE_TEXT => '',
			self::CITE_NAME  => '',
			self::CITE_TITLE => '',
			self::CITE_IMAGE => null,
		];
	}

	protected function required(): array {
		return [
			self::CLASSES => [ 'c-quote' ],
		];
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

	public function get_image_args() {
		if ( empty( $this->cite_image ) ) {
			return [];
		}

		return [
			'attachment'      => Image::factory( (int) $this->cite_image ),
			'wrapper_tag'     => 'span',
			'wrapper_classes' => [ 'c-quote__cite-figure' ],
			'img_classes'     => [ 'c-quote__cite-image' ],
			'src_size'        => Image_Sizes::SQUARE_XSMALL,
		];
	}

	public function get_quote() {
		return $this->quote_text;
	}

	/**
	 * @return mixed
	 */
	public function get_cite_name() {
		return $this->cite_name;
	}

	/**
	 * @return mixed
	 */
	public function get_cite_title() {
		return $this->cite_title;
	}


}
