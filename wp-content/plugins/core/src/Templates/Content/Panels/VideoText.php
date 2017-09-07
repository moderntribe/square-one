<?php


namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\VideoText as VideoTextPanel;
use Tribe\Project\Templates\Components\Content_Block;

class VideoText extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	protected function get_mapped_panel_data(): array {

		$data = [
			'wrapper_classes' => $this->get_panel_classes(),
			'video'           => $this->get_panel_video(),
			'content_block'   => $this->get_content_block(),
		];

		return $data;
	}

	protected function get_content_block() {

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
			Content_Block::TITLE         => esc_html( $this->panel_vars[ VideoTextPanel::FIELD_TITLE ] ),
			Content_Block::TITLE_TAG     => 'h2',
			Content_Block::TEXT          => $this->panel_vars[ VideoTextPanel::FIELD_DESCRIPTION ],
			Content_Block::CTA           => $this->panel_vars[ VideoTextPanel::FIELD_CTA ],
			Content_Block::TITLE_ATTRS   => $title_attrs,
			Content_Block::TITLE_CLASSES => [ 'h2' ],
			Content_Block::TEXT_ATTRS    => $description_attrs,
			Content_Block::CTA_CLASSES   => [ 'c-btn--sm' ],
		];

		$content_block_obj = Content_Block::factory( $options );

		return $content_block_obj->render();
	}

	protected function get_panel_video() {

		$video = false;

		if ( ! empty( $this->panel_vars[ VideoTextPanel::FIELD_VIDEO ] ) ) {
			$video = apply_filters( 'the_content', $this->panel_vars[ VideoTextPanel::FIELD_VIDEO ] );
		}

		return $video;
	}

	protected function get_panel_classes() {

		$classes = [];

		if ( VideoTextPanel::FIELD_LAYOUT_OPTION_VIDEO_RIGHT === $this->panel_vars[ VideoTextPanel::FIELD_LAYOUT ] ) {
			$classes[] = 'site-grid--reorder-2-col';
		}

		return implode( ' ', $classes );
	}

	public static function instance() {
		return tribe_project()->container()['twig.templates.content/panels/videotext'];
	}
}
