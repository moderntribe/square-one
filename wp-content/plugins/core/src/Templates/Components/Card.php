<?php

namespace Tribe\Project\Templates\Components;

use Tribe\Project\Panels\Types\CardGrid;
use Tribe\Project\Twig\Twig_Template;

class Card extends Twig_Template {

	const TITLE               = 'title';
	const DESCRIPTION         = 'description';
	const IMAGE               = 'image';
	const CLASSES             = 'classes';
	const TITLE_CLASSES       = 'title_classes';
	const HEADER_CLASSES      = 'header_classes';
	const CONTENT_CLASSES     = 'content_classes';
	const IMAGE_CLASSES       = 'image_classes';
	const DESCRIPTION_CLASSES = 'description_classes';
	const TITLE_ATTRS         = 'title_attrs';
	const DESCRIPTION_ATTRS   = 'description_attrs';
	const CTA                 = 'cta';
	const CTA_URL             = 'url';
	const CTA_LABEL           = 'label';
	const CTA_TARGET          = 'target';

	protected $card = [];

	public function __construct( $card, $template, \Twig_Environment $twig = null ) {
		parent::__construct( $template, $twig );

		$this->card = $this->parse_args( $card );
	}

	protected function parse_args( $options ) {
		$defaults = [
			self::TITLE               => '',
			self::DESCRIPTION         => '',
			self::IMAGE               => '',
			self::CLASSES             => [],
			self::TITLE_CLASSES       => [],
			self::HEADER_CLASSES      => [],
			self::CONTENT_CLASSES     => [],
			self::IMAGE_CLASSES       => [],
			self::DESCRIPTION_CLASSES => [],
			self::TITLE_ATTRS         => [],
			self::DESCRIPTION_ATTRS   => [],
			self::CTA                 => [],
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			'title'                => $this->get_title(),
			'card_classes'         => implode( ' ', array_merge( [ 'c-card' ], $this->card[ self::CLASSES ] ) ),
			'card_header_classes'  => implode( ' ', array_merge( [ 'c-card__header' ], $this->card[ self::HEADER_CLASSES ] ) ),
			'card_content_classes' => implode( ' ', array_merge( [ 'c-card__content' ], $this->card[ self::CONTENT_CLASSES ] ) ),
			'image_classes'        => implode( ' ', array_merge( [ 'c-image' ], $this->card[ self::IMAGE_CLASSES ] ) ),
			'description'          => $this->get_card_description(),
			'image'                => $this->get_card_image( $this->card[ self::IMAGE ] ),
			'button'               => $this->get_button(),
		];

		return $data;
	}

	protected function get_title(): string {

		if ( empty( $this->card[ self::TITLE ] ) ) {
			return '';
		}

		$title   = $this->card[ self::TITLE ];
		$classes = array_merge( [ 'c-card__title' ], $this->card[ self::TITLE_CLASSES ] );
		$attrs   = $this->card[ self::TITLE_ATTRS ];
		$tag     = 'h3';

		$title_obj = Title::factory( $title, $tag, $classes, $attrs );

		return $title_obj->render();
	}

	protected function get_card_image( $img ): string {

		if ( empty( $img ) ) {
			return '';
		}

		$options = [
			'as_bg'        => false,
			'use_lazyload' => false,
			'echo'         => false,
			'src_size'     => 'component-card',
		];

		$image = Image::factory( $img, $options );

		return $image->render();
	}

	protected function get_button(): string {

		if ( empty( $this->card[ self::CTA ][ self::CTA_URL ] ) ) {
			return '';
		}

		$options = [
			'url'         => esc_url( $this->card[ self::CTA ][ self::CTA_URL ] ),
			'label'       => esc_html( $this->card[ self::CTA ][ self::CTA_LABEL ] ),
			'target'      => esc_attr( $this->card[ self::CTA ][ self::CTA_TARGET ] ),
			'btn_as_link' => true,
		];

		$button = Button::factory( $options );

		return $button->render();
	}

	protected function get_card_description(): string {

		if ( empty( $this->card[ self::DESCRIPTION ] ) ) {
			return '';
		}

		$classes = array_merge( [ 'c-card__desc' ], $this->card[ self::DESCRIPTION_CLASSES ] );
		$attrs   = $this->card[ self::DESCRIPTION_ATTRS ];

		$desc_object = Description::factory( $this->card[ self::DESCRIPTION ], $classes, $attrs );

		return $desc_object->render();
	}

	/**
	 * Get an instance of this controller bound to the correct data.
	 *
	 * @param        $card
	 * @param string $template
	 *
	 * @return static
	 */
	public static function factory( $card, $template = 'components/card.twig' ) {
		return new static( $card, $template );
	}
}