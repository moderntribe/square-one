<?php

namespace Tribe\Project\Templates\Components\quote;

use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Models\Image;
use Tribe\Project\Theme\Config\Image_Sizes;

class Controller extends Abstract_Controller {
	private $classes;
	private $attrs;
	public $quote_text;
	public $cite_name;
	public $cite_title;
	private $cite_image;

	public function __construct( array $args = [] ) {
		$this->classes    = array_merge( [ 'c-quote' ], (array) ( $args['classes'] ?? [] ) );
		$this->attrs      = (array) ( $args['attrs'] ?? [] );
		$this->quote_text = $args['quote_text'] ?? '';
		$this->cite_name  = $args['cite_name'] ?? '';
		$this->cite_title = $args['cite_title'] ?? '';
		$this->cite_image = $args['cite_image'] ?? null;
	}

	public function classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function attributes(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function has_citation(): bool {
		return ! ( empty( $this->cite_name ) && empty( $this->cite_title ) && empty( $this->cite_image ) );
	}

	public function render_image() {
		if ( empty( $this->cite_image ) ) {
			return '';
		}

		return get_template_part( 'components/image/image', null, [
			'attachment'      => Image::factory( (int) $this->cite_image ),
			'wrapper_tag'     => 'span',
			'wrapper_classes' => [ 'c-quote__cite-figure' ],
			'img_classes'     => [ 'c-quote__cite-image' ],
			'src_size'        => Image_Sizes::SQUARE_XSMALL,
		] );
	}
}
