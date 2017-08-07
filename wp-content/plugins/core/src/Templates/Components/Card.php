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

		$this->card = $card;
	}

	public function get_data(): array {
		$data = [
			'card_classes'         => $this->get_card_classes(),
			'card_header_classes'  => $this->get_card_header_classes(),
			'title'                => $this->get_title(),
			'card_content_classes' => $this->get_card_content_classes(),
			'description'          => $this->get_card_description(),
			'image'                => $this->get_card_image( $this->card[ self::IMAGE ] ),
			'image_classes'        => $this->get_image_classes(),
			'button'               => $this->get_button(),
		];

		return $data;
	}

	protected function get_title(): string {

		if ( empty( $this->card[ self::TITLE ] ) ) {
			return '';
		}

		$title   = esc_html( $this->card[ self::TITLE ] );
		$classes = $this->get_card_title_classes();
		$attrs   = $this->get_card_title_attrs();
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

	protected function get_card_classes(): string {
		$classes = [ 'c-card' ];

		if ( ! empty( $this->card[ self::CLASSES ] ) ) {
			$classes = array_merge( $classes, $this->card[ self::CLASSES ] );
		}

		return implode( ' ', $classes );
	}

	protected function get_card_header_classes(): string {
		$classes = [ 'c-card__header' ];

		if ( ! empty( $this->card[ self::HEADER_CLASSES ] ) ) {
			$classes = array_merge( $classes, $this->card[ self::HEADER_CLASSES ] );
		}

		return implode( ' ', $classes );
	}

	protected function get_image_classes(): string {
		$classes = [ 'c-image' ];

		if ( ! empty( $this->card[ self::IMAGE_CLASSES ] ) ) {
			$classes = array_merge( $classes, $this->card[ self::IMAGE_CLASSES ] );
		}

		return implode( ' ', $classes );
	}

	protected function get_card_title_classes(): array {
		$classes = [ 'c-card__title' ];

		if ( ! empty( $this->card[ self::TITLE_CLASSES ] ) ) {
			$classes = array_merge( $classes, $this->card[ self::TITLE_CLASSES ] );
		}

		return $classes;
	}

	protected function get_card_content_classes(): string {
		$classes = [ 'c-card__content' ];

		if ( ! empty( $this->card[ self::CONTENT_CLASSES ] ) ) {
			$classes = array_merge( $classes, $this->card[ self::CONTENT_CLASSES ] );
		}

		return implode( ' ', $classes );
	}

	protected function get_card_description_classes(): array {
		$classes = [ 'c-card__desc' ];

		if ( ! empty( $this->card[ self::DESCRIPTION_CLASSES ] ) ) {
			$classes = array_merge( $classes, $this->card[ self::DESCRIPTION_CLASSES ] );
		}

		return $classes;
	}

	protected function get_card_title_attrs(): array {
		if ( empty( $this->card[ self::TITLE_ATTRS ] ) ) {
			return [];
		}

		return $this->card[ self::TITLE_ATTRS ];
	}

	protected function get_card_description_attrs(): array {
		if ( empty( $this->card[ self::DESCRIPTION_ATTRS ] ) ) {
			return [];
		}

		return $this->card[ self::DESCRIPTION_ATTRS ];
	}

	protected function get_card_description(): string {

		if ( empty( $this->card[ self::DESCRIPTION ] ) ) {
			return '';
		}

		$classes = $this->get_card_description_classes();
		$attrs   = $this->get_card_description_attrs();

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