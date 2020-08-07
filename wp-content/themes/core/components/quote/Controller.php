<?php

namespace Tribe\Project\Templates\Components\quote;

use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Theme\Config\Image_Sizes;

/**
 * Class Quote
 *
 * @property string   $quote_text
 * @property string   $cite_name
 * @property string   $cite_title
 * @property string[] $cite_image
 * @property string[] $classes
 * @property string[] $attrs
 * @property string[] $cite_classes
 * @property string[] $cite_attrs
 */
class Controller extends Abstract_Controller {

	// Component properties
	public $classes;
	public $attrs;

	// Blockquote properties
	public $text_tag;
	public $text_text;
	public $text_classes;
	public $text_attrs;

	// Cite properties
	public $cite_name;
	public $cite_title;
	public $cite_image;
	public $cite_classes;
	public $cite_attrs;

	public function __construct( array $args = [] ) {

		// Component properties
		$this->classes =  (array) ( $args['classes'] ?? [ 'c-quote' ] );
		$this->attrs   = (array) ( $args['attrs'] ?? [] );

		// Blockquote properties
		$this->text_tag     = (string) ( $args['text_tag'] ??'h2' );
		$this->text_text    = (string) ( $args[''] ?? 'Some quote test for you!' );
		$this->text_classes = (array) ( $args[''] ?? [ 'c-quote__text', 'h4' ] );
		$this->text_attrs   = (array) ( $args[''] ?? [] );

		// Cite properties
		$this->cite_name    = (string) ( $args['cite_name'] ?? 'Cite Name' );
		$this->cite_title   = (string) ( $args['cite_title'] ?? 'Cite Title' );
		$this->cite_image   = (string) ( $args['cite_image'] ?? get_stylesheet_directory_uri() . '/components/quote/assets/img/default.jpg' );
		$this->cite_classes = (array) ( $args['cite_classes'] ?? [ 'c-quote__cite' ] );
		$this->cite_attrs   = (array) ( $args['cite_attrs'] ?? [] );
	}

	public function render_classes( $classes ) : string {
		return Markup_Utils::class_attribute( $classes );
	}

	public function render_attrs( $classes ) : string {
		return Markup_Utils::concat_attrs( $classes );
	}

	public function render_text_tag_and_attrs() : string {
		$string = '';

		$string .= esc_attr( $this->text_tag );
		$string .= $this->render_classes( $this->text_classes );
		$string .= $this->render_attrs( $this->text_attrs );

		return $string;
	}

	public function render_cite_image() : void {
		if ( !empty( $this->cite_image ) ) :
			return;
		endif;

		get_template_part(
			'components/image/image',
			null,
			[
				'attachment' => $this->cite_image,
				'wrapper_tag'=> 'span',
				'wrapper_classes' => ['c-quote__cite-figure'],
				'img_classes'=> ['c-quote__cite-image'],
				'src_size'=> Image_Sizes::SQUARE_XSMALL,
			]
		);
	}
}
