<?php


namespace Tribe\Project\Templates\Controllers\Panel;

use Tribe\Project\Panels\Types\VideoText as VideoTextPanel;
use Tribe\Project\Templates\Components\Content_Block;
use Tribe\Project\Templates\Components\Link;
use Tribe\Project\Templates\Components\Panels\Videotext as Videotext_Context;
use Tribe\Project\Templates\Components\Text;

class Videotext extends Panel {
	use Traits\Headerless;
	use Traits\Unwrapped;

	protected function render_content( \ModularContent\Panel $panel, array $panel_vars ): string {
		return $this->factory->get( Videotext_Context::class, [
			'wrapper_classes' => $this->get_wrapper_classes( $panel_vars ),
			'video'           => $this->get_panel_video( $panel_vars ),
			'content_block'   => $this->get_content_block( $panel_vars ),
		] )->render();
	}

	protected function get_content_block( array $panel_vars ): string {

		$title_attrs       = [];
		$description_attrs = [];

		if ( is_panel_preview() ) {

			$title_attrs = [
				'data-depth'    => 0,
				'data-name'     => VideoTextPanel::FIELD_TITLE,
				'data-livetext' => true,
			];

			$description_attrs = [
				'data-depth'    => 0,
				'data-name'     => VideoTextPanel::FIELD_DESCRIPTION,
				'data-autop'    => 'true',
				'data-livetext' => true,
			];
		}

		$options = [
			Content_Block::TITLE           => $this->get_video_text_title( $panel_vars, $title_attrs ),
			Content_Block::TEXT            => $this->get_video_text_text( $panel_vars, $description_attrs ),
			Content_Block::CONTENT_CLASSES => '',
			Content_Block::BUTTON          => $this->get_video_text_button( $panel_vars ),
			Content_Block::CLASSES         => '',
		];

		return $this->factory->get( Content_Block::class, $options )->render();
	}

	protected function get_video_text_title( array $panel_vars, $title_attributes ): string {
		$options = [
			Text::TEXT    => esc_html( $panel_vars[ VideoTextPanel::FIELD_TITLE ] ),
			Text::TAG     => 'h2',
			Text::CLASSES => [ 'h2' ],
			Text::ATTRS   => $title_attributes,
		];

		return $this->factory->get( Text::class, $options )->render();
	}

	protected function get_video_text_text( array $panel_vars, $description_attrs ): string {
		$options = [
			Text::ATTRS   => $description_attrs,
			Text::CLASSES => [],
			Text::TEXT    => $panel_vars[ VideoTextPanel::FIELD_DESCRIPTION ],
		];

		return $this->factory->get( Text::class, $options )->render();
	}

	protected function get_video_text_button( array $panel_vars ): string {
		$options = [
			Link::CLASSES => [ 'c-btn', 'c-btn--sm' ],
			Link::URL     => $panel_vars[ VideoTextPanel::FIELD_CTA ][ Link::URL ],
			Link::TARGET  => $panel_vars[ VideoTextPanel::FIELD_CTA ][ Link::TARGET ],
			Link::TEXT    => $panel_vars[ VideoTextPanel::FIELD_CTA ][ Link::TEXT ],
		];

		return $this->factory->get( Link::class, $options )->render();
	}

	protected function get_panel_video( array $panel_vars ): string {
		if ( empty( $panel_vars[ VideoTextPanel::FIELD_VIDEO ] ) ) {
			return '';
		}

		$url = $panel_vars[ VideoTextPanel::FIELD_VIDEO ];

		return (string) $GLOBALS['wp_embed']->shortcode( [], $url );
	}

	protected function get_wrapper_classes( array $panel_vars ): array {
		$classes = [];

		if ( VideoTextPanel::FIELD_LAYOUT_OPTION_VIDEO_RIGHT === $panel_vars[ VideoTextPanel::FIELD_LAYOUT ] ) {
			$classes[] = 'g-row--reorder-2-col';
		}

		return $classes;
	}
}
