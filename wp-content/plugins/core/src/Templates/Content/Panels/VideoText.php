<?php


namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\VideoText as VideoTextPanel;
use Tribe\Project\Templates\Components\Button;
use Tribe\Project\Templates\Components\Content_Block;
use Tribe\Project\Templates\Components\Video;
use Tribe\Project\Templates\Components\Text;
use Tribe\Project\Templates\Components\Title;
use Tribe\Project\Theme\Util;
use Tribe\Project\Facade\Items\Theme\Oembed;

class VideoText extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	protected function get_mapped_panel_data(): array {

		$data = [
			'wrapper_classes' => $this->get_wrapper_classes(),
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
			Content_Block::TITLE           => $this->get_video_text_title( $title_attrs ),
			Content_Block::TEXT            => $this->get_video_text_text( $description_attrs ),
			Content_Block::CONTENT_CLASSES => '',
			Content_Block::BUTTON          => $this->get_video_text_button(),
			Content_Block::CLASSES         => '',
		];

		$content_block_obj = Content_Block::factory( $options );

		return $content_block_obj->render();
	}

	protected function get_video_text_title( $title_attributes ) {
		$options = [
			Title::TITLE   => esc_html( $this->panel_vars[ VideoTextPanel::FIELD_TITLE ] ),
			Title::TAG     => 'h2',
			Title::CLASSES => [ 'h2' ],
			Title::ATTRS   => $title_attributes,
		];

		$title_obj = Title::factory( $options );

		return $title_obj->render();
	}

	protected function get_video_text_text( $description_attrs ) {
		$options = [
			Text::ATTRS   => $description_attrs,
			Text::CLASSES => '',
			Text::TEXT    => $this->panel_vars[ VideoTextPanel::FIELD_DESCRIPTION ],
		];

		$text_object = Text::factory( $options );

		return $text_object->render();
	}

	protected function get_video_text_button() {
		$options = [
			Button::TAG         => '',
			Button::URL         => $this->panel_vars[ VideoTextPanel::FIELD_CTA ][ Button::URL ],
			Button::TYPE        => '',
			Button::TARGET      => $this->panel_vars[ VideoTextPanel::FIELD_CTA ][ Button::TARGET ],
			Button::CLASSES     => [ 'c-btn c-btn--sm' ],
			Button::ATTRS       => '',
			Button::LABEL       => $this->panel_vars[ VideoTextPanel::FIELD_CTA ][ Button::LABEL ],
			Button::BTN_AS_LINK => true,
		];

		$button_object = Button::factory( $options );

		return $button_object->render();
	}

	protected function get_panel_video() {
		if ( empty( $this->panel_vars[ VideoTextPanel::FIELD_VIDEO ] ) ) {
			return '';
		}

		$url = $this->panel_vars[ VideoTextPanel::FIELD_VIDEO ];
		$oembed   = _wp_oembed_get_object();
		$provider = $oembed->get_provider( $url );
		$data     = $oembed->fetch( $provider, $url );
		$container_classes = [ 'c-video--lazy' ];

		if ( $data->provider_name === 'YouTube' ) {
			$embed_id    = Oembed::get_youtube_embed_id( $url );
			$video_thumb = Oembed::get_youtube_max_resolution_thumbnail( $url );
		} else {
			$embed_id    = Oembed::get_vimeo_embed_id( $url );
			$video_thumb = $data->thumbnail_url;
		}

		$options = [
			Video::THUMBNAIL_URL     => $video_thumb,
			Video::CONTAINER_ATTRS   => [
				'data-js'             => 'c-video',
				'data-embed-id'       => $embed_id,
				'data-embed-provider' => $data->provider_name,
			],
			Video::CONTAINER_CLASSES => $container_classes,
			Video::TITLE             => __( 'Play Video', 'tribe' ),
			Video::VIDEO_URL         => $url,
			Video::PLAY_TEXT         => $data->title,
		];

		$video_obj     = Video::factory( $options );
		return $video_obj->render();
	}

	protected function get_wrapper_classes() {

		$classes = [];

		if ( VideoTextPanel::FIELD_LAYOUT_OPTION_VIDEO_RIGHT === $this->panel_vars[ VideoTextPanel::FIELD_LAYOUT ] ) {
			$classes[] = 'g-row--reorder-2-col';
		}

		return Util::class_attribute( $classes, false );
	}

	public static function instance() {
		return tribe_project()->container()['twig.templates.content/panels/videotext'];
	}
}
