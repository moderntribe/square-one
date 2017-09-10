<?php

namespace Tribe\Project\Templates\Components;

class Content_Block extends Component {

	const TEMPLATE_NAME = 'components/contentblock.twig';

	const TITLE           = 'title';
	const TITLE_TAG       = 'title_tag';
	const TEXT            = 'text';
	const BUTTON          = 'button';
	const TITLE_CLASSES   = 'title_classes';
	const CLASSES         = 'classes';
	const CONTENT_CLASSES = 'content_classes';
	const TEXT_CLASSES    = 'text_classes';
	const TITLE_ATTRS     = 'title_attrs';
	const TEXT_ATTRS      = 'text_attrs';
	const CTA             = 'cta';
	const CTA_URL         = 'url';
	const CTA_LABEL       = 'label';
	const CTA_TARGET      = 'target';
	const CTA_CLASSES     = 'classes';

	protected function parse_options( array $options ): array {
		$defaults = [
			self::TITLE           => '',
			self::TITLE_TAG       => '',
			self::TEXT            => '',
			self::BUTTON          => '',
			self::TITLE_CLASSES   => [],
			self::CLASSES         => [],
			self::CONTENT_CLASSES => [],
			self::TEXT_CLASSES    => [],
			self::TITLE_ATTRS     => [],
			self::TEXT_ATTRS      => [],
			self::CTA             => [],
			self::CTA_CLASSES     => [],
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			static::TITLE           => $this->get_title(),
			static::CLASSES         => $this->merge_classes( [ 'c-content-block' ], $this->options[ self::CLASSES ], true ),
			static::CONTENT_CLASSES => $this->merge_classes( [ 'c-content-block__content' ], $this->options[ self::CONTENT_CLASSES ], true ),
			static::TEXT            => $this->get_content_block_text(),
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
			Title::CLASSES => $this->merge_classes( [ 'c-content-block__title' ], $this->options[ self::TITLE_CLASSES ] ),
			Title::ATTRS   => $this->options[ self::TITLE_ATTRS ],
			Title::TAG     => $this->options[ self::TITLE_TAG ],
		];

		$title_obj = Title::factory( $options );

		return $title_obj->render();
	}

	protected function get_button(): string {

		if ( empty( $this->options[ self::CTA ][ self::CTA_URL ] ) ) {
			return '';
		}

		$options = [
			'url'         => esc_url( $this->options[ self::CTA ][ self::CTA_URL ] ),
			'label'       => esc_html( $this->options[ self::CTA ][ self::CTA_LABEL ] ),
			'target'      => esc_attr( $this->options[ self::CTA ][ self::CTA_TARGET ] ),
			'classes'     => $this->options[ self::CTA_CLASSES ],
			'btn_as_link' => true,
		];

		$button = Button::factory( $options );

		return $button->render();
	}

	protected function get_content_block_text(): string {

		if ( empty( $this->options[ self::TEXT ] ) ) {
			return '';
		}

		$options = [
			Text::TEXT    => $this->options[ self::TEXT ],
			Text::CLASSES => $this->merge_classes( [ 'c-content-block__desc' ], $this->options[ self::TEXT_CLASSES ] ),
			Text::ATTRS   => $this->options[ self::TEXT_ATTRS ],
		];

		$text_object = Text::factory( $options );

		return $text_object->render();
	}
}
