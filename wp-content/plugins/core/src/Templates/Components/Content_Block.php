<?php

namespace Tribe\Project\Templates\Components;

class Content_Block extends Component {

	const TEMPLATE_NAME = 'components/contentblock.twig';

	const TITLE                         = 'title';
	const TITLE_TAG                     = 'title_tag';
	const DESCRIPTION                   = 'description';
	const TITLE_CLASSES                 = 'title_classes';
	const CONTENT_BLOCK_CLASSES         = 'content_block_classes';
	const CONTENT_BLOCK_CONTENT_CLASSES = 'content_block_content_classes';
	const DESCRIPTION_CLASSES           = 'description_classes';
	const TITLE_ATTRS                   = 'title_attrs';
	const DESCRIPTION_ATTRS             = 'description_attrs';
	const CTA                           = 'cta';
	const CTA_URL                       = 'url';
	const CTA_LABEL                     = 'label';
	const CTA_TARGET                    = 'target';
	const CTA_CLASSES                   = 'classes';

	protected function parse_options( array $options ): array {
		$defaults = [
			self::TITLE                         => '',
			self::TITLE_TAG                     => '',
			self::DESCRIPTION                   => '',
			self::TITLE_CLASSES                 => [],
			self::CONTENT_BLOCK_CLASSES         => [],
			self::CONTENT_BLOCK_CONTENT_CLASSES => [],
			self::DESCRIPTION_CLASSES           => [],
			self::TITLE_ATTRS                   => [],
			self::DESCRIPTION_ATTRS             => [],
			self::CTA                           => [],
			self::CTA_CLASSES                   => [],
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			'title'                         => $this->get_title(),
			'content_block_classes'         => $this->merge_classes( [ 'c-content-block' ], $this->options[ self::CONTENT_BLOCK_CLASSES ], true ),
			'content_block_content_classes' => $this->merge_classes( [ 'c-content-block__content' ], $this->options[ self::CONTENT_BLOCK_CONTENT_CLASSES ], true ),
			'description'                   => $this->get_content_block_description(),
			'button'                        => $this->get_button(),
		];

		return $data;
	}

	protected function get_title(): string {

		if ( empty( $this->options[ self::TITLE ] ) ) {
			return '';
		}

		$options = [
			Title::TITLE         => $this->options[ self::TITLE ],
			Title::CLASSES       => $this->merge_classes( [ 'c-content-block__title' ], $this->options[ self::TITLE_CLASSES ] ),
			Title::ATTRS         => $this->options[ self::TITLE_ATTRS ],
			Title::TAG           => $this->options[ self::TITLE_TAG ],
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

	protected function get_content_block_description(): string {

		if ( empty( $this->options[ self::DESCRIPTION ] ) ) {
			return '';
		}

		$options = [
			Description::DESCRIPTION => $this->options[ self::DESCRIPTION ],
			Description::CLASSES     => $this->merge_classes( [ 'c-content-block__desc' ], $this->options[ self::DESCRIPTION_CLASSES ] ),
			Description::ATTRS       => $this->options[ self::DESCRIPTION_ATTRS ],
		];

		$desc_object = Description::factory( $options );

		return $desc_object->render();
	}
}
