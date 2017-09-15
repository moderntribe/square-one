<?php

namespace Tribe\Project\Templates\Components;

class Card extends Component {

	const TEMPLATE_NAME = 'components/card.twig';

	const TITLE           = 'title';
	const TEXT            = 'text';
	const IMAGE           = 'image';
	const PRE_TITLE       = 'pre_title';
	const POST_TITLE      = 'post_title';
	const CLASSES         = 'card_classes';
	const TITLE_CLASSES   = 'title_classes';
	const HEADER_CLASSES  = 'card_header_classes';
	const CONTENT_CLASSES = 'card_content_classes';
	const IMAGE_CLASSES   = 'image_classes';
	const TEXT_CLASSES    = 'text_classes';
	const TITLE_ATTRS     = 'title_attrs';
	const TEXT_ATTRS      = 'text_attrs';
	const BUTTON          = 'button';
	const CTA             = 'cta';
	const CTA_URL         = 'url';
	const CTA_LABEL       = 'label';
	const CTA_TARGET      = 'target';

	protected function parse_options( array $options ): array {
		$defaults = [
			self::TITLE           => '',
			self::TEXT            => '',
			self::IMAGE           => '',
			self::PRE_TITLE       => '',
			self::POST_TITLE      => '',
			self::CLASSES         => [],
			self::TITLE_CLASSES   => [],
			self::HEADER_CLASSES  => [],
			self::CONTENT_CLASSES => [],
			self::IMAGE_CLASSES   => [],
			self::TEXT_CLASSES    => [],
			self::TITLE_ATTRS     => [],
			self::TEXT_ATTRS      => [],
			self::BUTTON          => '',
			self::CTA             => [],
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			static::TITLE           => $this->get_title(),
			static::PRE_TITLE       => $this->options[ static::PRE_TITLE ],
			static::POST_TITLE      => $this->options[ static::POST_TITLE ],
			static::CLASSES         => $this->merge_classes( [ 'c-card' ], $this->options[ self::CLASSES ], true ),
			static::HEADER_CLASSES  => $this->merge_classes( [ 'c-card__header' ], $this->options[ self::HEADER_CLASSES ], true ),
			static::CONTENT_CLASSES => $this->merge_classes( [ 'c-card__content' ], $this->options[ self::CONTENT_CLASSES ], true ),
			static::IMAGE_CLASSES   => $this->merge_classes( [ 'c-image' ], $this->options[ self::IMAGE_CLASSES ], true ),
			static::TEXT            => $this->get_card_text(),
			static::IMAGE           => $this->get_card_image( $this->options[ self::IMAGE ] ),
			static::BUTTON          => $this->get_button(),
		];

		return $data;
	}

	protected function get_title(): string {

		if ( empty( $this->options[ self::TITLE ] ) ) {
			return '';
		}

		$options = [
			Title::TITLE   => $this->options[ self::TITLE ],
			Title::CLASSES => $this->merge_classes( [ 'c-card__title' ], $this->options[ self::TITLE_CLASSES ] ),
			Title::ATTRS   => $this->options[ self::TITLE_ATTRS ],
			Title::TAG     => 'h3',
		];

		$title_obj = Title::factory( $options );

		return $title_obj->render();
	}

	protected function get_card_image( $img ): string {

		if ( empty( $img ) ) {
			return '';
		}

		$options = [
			'img_id'       => $img,
			'as_bg'        => false,
			'use_lazyload' => false,
			'echo'         => false,
			'src_size'     => 'component-card',
		];

		$image = Image::factory( $options );

		return $image->render();
	}

	protected function get_button(): string {

		if ( empty( $this->options[ self::CTA ][ self::CTA_URL ] ) ) {
			return '';
		}

		$options = [
			'url'         => esc_url( $this->options[ self::CTA ][ self::CTA_URL ] ),
			'label'       => esc_html( $this->options[ self::CTA ][ self::CTA_LABEL ] ),
			'target'      => esc_attr( $this->options[ self::CTA ][ self::CTA_TARGET ] ),
			'btn_as_link' => true,
			'classes'     => [ 'c-btn--sm' ],
		];

		$button = Button::factory( $options );

		return $button->render();
	}

	protected function get_card_text(): string {

		if ( empty( $this->options[ self::TEXT ] ) ) {
			return '';
		}

		$options = [
			Text::TEXT    => $this->options[ self::TEXT ],
			Text::CLASSES => $this->merge_classes( [ 'c-card__desc' ], $this->options[ self::TEXT_CLASSES ] ),
			Text::ATTRS   => $this->options[ self::TEXT_ATTRS ],
		];

		$text_object = Text::factory( $options );

		return $text_object->render();
	}
}