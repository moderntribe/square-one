<?php

namespace Tribe\Project\Templates\Components;

use Tribe\Project\Twig\Twig_Template;
use Tribe\Project\Panels\Types\CardGrid as CardG;

class Card extends Twig_Template {

	protected $card = [];

	public function __construct( $card, $template, \Twig_Environment $twig = null ) {
		parent::__construct( $template, $twig );

		$this->card = $card;
	}

	public function get_data(): array {
		$data = [
			'card_classes'         => $this->get_card_classes(),
			'card_header_classes'  => $this->get_card_header_classes(),
			'title'                => esc_html( $this->card[ CardG::FIELD_CARD_TITLE ] ),
			'card_title_classes'   => $this->get_card_title_classes(),
			'heading_tag'          => 'h3',
			'title_attrs'          => $this->get_card_title_attrs(),
			'card_content_classes' => $this->get_card_content_classes(),
			'description'          => $this->card[ CardG::FIELD_CARD_DESCRIPTION ],
			'desc_classes'         => $this->get_card_desc_classes(),
			'desc_attrs'           => $this->get_card_desc_attrs(),
			'image'                => $this->get_card_image( $this->card[ CardG::FIELD_CARD_IMAGE ] ),
			'image_classes'        => $this->get_image_classes(),
			'button'               => $this->get_button(),
		];

		return $data;
	}

	protected function get_card_image( $img ) {

		if ( empty( $img ) ) {
			return false;
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

	protected function get_button() {

		if ( empty( $this->card[ CardG::FIELD_CARD_CTA ]['url'] ) ) {
			return '';
		}

		$options = [
			'url'         => esc_url( $this->card[ CardG::FIELD_CARD_CTA ]['url'] ),
			'label'       => esc_html( $this->card[ CardG::FIELD_CARD_CTA ]['label'] ),
			'target'      => esc_attr( $this->card[ CardG::FIELD_CARD_CTA ]['target'] ),
			'btn_as_link' => true,
		];

		$button = Button::factory( $options );
		return $button->render();
	}

	protected function get_card_classes() {

		$classes = [ 'c-card' ];

		if ( ! empty( CardG::NAME ) ) {
			$classes[] = '';
		}

		return implode( ' ', $classes );
	}

	protected function get_card_header_classes() {

		$classes = [ 'c-card__header' ];

		if ( ! empty( CardG::NAME ) ) {
			$classes[] = '';
		}

		return implode( ' ', $classes );
	}

	protected function get_image_classes() {

		$classes = [ 'c-image' ];

		if ( ! empty( CardG::NAME ) ) {
			$classes[] = '';
		}

		return implode( ' ', $classes );
	}

	protected function get_card_title_classes() {

		$classes = [ 'c-card__title' ];

		if ( ! empty( CardG::NAME ) ) {
			$classes[] = '';
		}

		return implode( ' ', $classes );
	}

	protected function get_card_title_attrs() {
		$card_title_attrs = sprintf( 'class="c-card__title" data-depth="0" data-name="%s" data-index="%s" data-livetext', esc_attr( CardG::FIELD_CARD_TITLE ),
			esc_attr( get_nest_index() ) );

		return $card_title_attrs;
	}

	protected function get_card_content_classes() {

		$classes = [ 'c-card__content' ];

		if ( ! empty( CardG::NAME ) ) {
			$classes[] = '';
		}

		return implode( ' ', $classes );
	}

	protected function get_card_desc_classes() {

		$classes = [ 'c-card__desc' ];

		if ( ! empty( CardG::NAME ) ) {
			$classes[] = '';
		}

		return implode( ' ', $classes );
	}

	protected function get_card_desc_attrs() {
		$card_desc_attrs = sprintf( 'data-depth="0" data-name="%s" data-index="%s" data-autop="true" data-livetext', esc_attr( CardG::FIELD_CARD_DESCRIPTION ),
			esc_attr( get_nest_index() ) );

		return $card_desc_attrs;
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