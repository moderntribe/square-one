<?php

namespace Tribe\Project\Templates\Components\thing;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\image\Controller as Image_Component;
use Tribe\Project\Templates\Models\Image;
use Tribe\Project\Theme\Config\Image_Sizes;
use Tribe\Project\Templates\Factory_Method;

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
class Controller {

	use Factory_Method;

	public $quote;
	public $cite;

	public $quote_text;
	public $cite_name;
	public $cite_title;
	public $cite_image;
	public $classes;
	public $attrs;
	public $cite_classes;
	public $cite_attrs;

	public $text_tag;
	public $text_text;
	public $text_classes;

	private $Image_Component;

	protected function __construct() {

		$this->cite         = [];
		$this->quote_text   = '';
		$this->cite_name    = '';
		$this->cite_title   = '';
		$this->cite_image   = [];
		$this->classes      = [ 'c-quote' ];
		$this->attrs        = [];
		$this->cite_classes = [ 'c-quote__cite' ];
		$this->cite_attrs   = [];
		$this->text_tag = 'h2';
		$this->text_text = '';
		$this->text_classes = '';

		$this->Image_Component = new Image_Component();

		$this->init();

	}

	public function init() {
		$this->quote = $this->setup_quote_text();
		$this->cite  = $this->setup_quote_citation();
	}

	public function stringify( $classes ): string {
		return Markup_Utils::class_attribute( $classes );
	}

	private function setup_quote_text(): array {
		/**
		 * Note: HTML5 validation requires that the The block's `<section>` element contain a heading.
		 * Therefore, we're setting the quote text to an `<h2>`. This means that the quote text cannot be multiple paragraphs.
		 * However, given the designs for this block, and the notes on the design requesting a max number of lines
		 * The `<h2>` designation doesn't seem like a limitation.
		 */
		return [
			$this->text_tag = 'h2',
			$this->text_text = $this->quote_text,
			$this->text_classes = [ 'c-quote__text', 'h4' ]
		];
	}

	private function setup_quote_citation(): array {
		$cite = [];

		if ( ! empty( $this->cite_image ) ) {
			$cite['image'] = $this->setup_cite_image();
		}

		if ( ! empty( $this->cite_name ) ) {
			$cite['name'] = $this->setup_cite_name();
		}

		if ( ! empty( $this->cite_name ) ) {
			$cite['title'] = $this->setup_cite_title();
		}

		return $cite;
	}

	private function setup_cite_image(): void {

			$this->Image_Component->attachment      = Image::factory( (int) $this->cite_image['id'] );
			$this->Image_Component->wrapper_tag     = 'span';
			$this->Image_Component->wrapper_classes = [ 'c-quote__cite-figure' ];
			$this->Image_Component->img_classes     = [ 'c-quote__cite-image' ];
			$this->Image_Component->src_size        = Image_Sizes::SQUARE_XSMALL;

	}

	private function setup_cite_name(): array {
		return [
			$this->text_tag     => 'span',
			$this->text_text    => $this->cite_name,
			$this->text_classes => [ 'c-quote__cite-name' ],
		];
	}

	private function setup_cite_title(): array {
		return [
			$this->text_tag    => 'span',
			$this->text_text    => $this->cite_title,
			$this->text_classes => [ 'c-quote__cite-title' ],
		];
	}
}
