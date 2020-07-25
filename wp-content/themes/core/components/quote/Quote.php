<?php

namespace Tribe\Project\Templates\Components;

use Tribe\Project\Components\Component;
use Tribe\Project\Templates\Components\Image as Image_Component;
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
		return [
			Text::TEXT    => self::QUOTE_TEXT,
			Text::CLASSES => 'c-quote__text',
		];
	}

	private function setup_quote_citation(): array {
		$cite = [];

		// If the citation has an image, the name & title are rendered as the figcaption content.
		if ( ! empty( $this->data[ self::CITE_IMAGE ] ) ) {
			$cite['image'] = $this->setup_cite_image();
		} else {
			$cite['name'] = $this->setup_cite_name();
			$cite['title'] = $this->setup_cite_title();
		}

		return $cite;
	}

	private function setup_cite_image(): array {
		$cite_image = [
			Image_Component::ATTACHMENT      => Image::factory( (int) $this->data[ self::CITE_IMAGE ]['id'] ),
			Image_Component::WRAPPER_CLASSES => [ 'c-quote__cite-figure' ],
			Image_Component::IMG_CLASSES     => [ 'c-quote__cite-image' ],
			Image_Component::SRC_SIZE        => Image_Sizes::SQUARE_XSMALL,
		];

		$rendered_cite_name  = $this->factory->get( Text::class, $this->setup_cite_name() )->get_rendered_output();
		$rendered_cite_title = $this->factory->get( Text::class, $this->setup_cite_title() )->get_rendered_output();

		if ( ! empty( $rendered_cite_name ) || ! empty( $rendered_cite_title ) ) {
			$cite_image[ Image_Component::HTML ] = $this->factory->get( Text::class, [
				Text::TAG     => 'figcaption',
				Text::TEXT    => $rendered_cite_name . $rendered_cite_title,
				Text::CLASSES => [ 'c-quote__cite-figcaption' ],
			] );
		}

		return $cite_image;
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
