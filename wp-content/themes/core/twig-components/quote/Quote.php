<?php

namespace Tribe\Project\Templates\Components;

use Tribe\Project\Components\Component;
use Tribe\Project\Templates\Components\Controller as Image_Component;
use Tribe\Project\Templates\Models\Image;
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
class Quote extends Component {

	private const QUOTE       = 'quote';
	private const CITE        = 'cite';

	public const QUOTE_TEXT   = 'quote_text';
	public const CITE_NAME    = 'cite_name';
	public const CITE_TITLE   = 'cite_title';
	public const CITE_IMAGE   = 'cite_image';
	public const CLASSES      = 'classes';
	public const ATTRS        = 'attrs';
	public const CITE_CLASSES = 'cite_classes';
	public const CITE_ATTRS   = 'cite_attrs';

	protected function defaults(): array {
		return [
			self::QUOTE        => [],
			self::CITE         => [],
			self::QUOTE_TEXT   => '',
			self::CITE_NAME    => '',
			self::CITE_TITLE   => '',
			self::CITE_IMAGE   => [],
			self::CLASSES      => [ 'c-quote' ],
			self::ATTRS        => [],
			self::CITE_CLASSES => [ 'c-quote__cite' ],
			self::CITE_ATTRS   => [],
		];
	}

	public function init() {
		$this->data[ self::QUOTE ] = $this->setup_quote_text();
		$this->data[ self::CITE ]  = $this->setup_quote_citation();
	}

	private function setup_quote_text(): array {
		/**
		 * Note: HTML5 validation requires that the The block's `<section>` element contain a heading.
		 * Therefore, we're setting the quote text to an `<h2>`. This means that the quote text cannot be multiple paragraphs.
		 * However, given the designs for this block, and the notes on the design requesting a max number of lines
		 * The `<h2>` designation doesn't seem like a limitation.
		 */
		return [
			Text::TAG     => 'h2',
			Text::TEXT    => $this->data[ self::QUOTE_TEXT ],
			Text::CLASSES => [ 'c-quote__text', 'h4' ],
		];
	}

	private function setup_quote_citation(): array {
		$cite = [];

		if ( ! empty( $this->data[ self::CITE_IMAGE ] ) ) {
			$cite['image'] = $this->setup_cite_image();
		}

		if ( ! empty( $this->data[ self::CITE_NAME ] ) ) {
			$cite['name'] = $this->setup_cite_name();
		}

		if ( ! empty( $this->data[ self::CITE_NAME ] ) ) {
			$cite['title'] = $this->setup_cite_title();
		}

		return $cite;
	}

	private function setup_cite_image(): array {
		return [
			Image_Component::ATTACHMENT      => Image::factory( (int) $this->data[ self::CITE_IMAGE ]['id'] ),
			Image_Component::WRAPPER_TAG     => 'span',
			Image_Component::WRAPPER_CLASSES => [ 'c-quote__cite-figure' ],
			Image_Component::IMG_CLASSES     => [ 'c-quote__cite-image' ],
			Image_Component::SRC_SIZE        => Image_Sizes::SQUARE_XSMALL,
		];
	}

	private function setup_cite_name(): array {
		return [
			Text::TAG     => 'span',
			Text::TEXT    => $this->data[ self::CITE_NAME ],
			Text::CLASSES => [ 'c-quote__cite-name' ],
		];
	}

	private function setup_cite_title(): array {
		return [
			Text::TAG     => 'span',
			Text::TEXT    => $this->data[ self::CITE_TITLE ],
			Text::CLASSES => [ 'c-quote__cite-title' ],
		];
	}
}
