<?php

namespace Tribe\Project\Templates\Components;

class Video extends Component {

	const TEMPLATE_NAME = 'components/video.twig';

	const TITLE                  = 'title';
	const CAPTION_POSITION       = 'caption_position';
	const VIDEO_URL              = 'video_url';
	const THUMBNAIL_URL          = 'thumbnail_url';
	const PLAY_TEXT              = 'play_text';
	const SHIM                   = 'shim';
	const FIGURE_CLASSES         = 'figure_classes';
	const CONTAINER_ATTRS        = 'container_attrs';
	const CONTAINER_CLASSES      = 'container_classes';
	const CONTAINER_WRAP_CLASSES = 'container_wrap_classes';

	const CAPTION_POSITION_CENTER = 'center';
	const CAPTION_POSITION_BOTTOM = 'bottom';
	const CAPTION_POSITION_BELOW  = 'below';

	protected function parse_options( array $options ): array {
		$defaults = [
			static::TITLE                  => '',
			static::CAPTION_POSITION       => static::CAPTION_POSITION_CENTER, // possible options: center, bottom, below
			static::VIDEO_URL              => '',
			static::THUMBNAIL_URL          => '',
			static::PLAY_TEXT              => __( 'Play Video', 'tribe' ),
			static::SHIM                   => trailingslashit( get_stylesheet_directory_uri() ) . 'img/theme/shims/16x9.png',
			static::FIGURE_CLASSES         => [],
			static::CONTAINER_ATTRS        => [],
			static::CONTAINER_CLASSES      => [],
			static::CONTAINER_WRAP_CLASSES => [],
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			static::TITLE                  => $this->options[ static::TITLE ],
			static::CAPTION_POSITION       => $this->options[ static::CAPTION_POSITION ],
			static::VIDEO_URL              => $this->options[ static::VIDEO_URL ],
			static::THUMBNAIL_URL          => $this->options[ static::THUMBNAIL_URL ],
			static::PLAY_TEXT              => $this->options[ static::PLAY_TEXT ],
			static::SHIM                   => $this->options[ static::SHIM ],
			static::FIGURE_CLASSES         => $this->merge_classes( [ 'c-video__embed' ], $this->options[ static::FIGURE_CLASSES ], true ),
			static::CONTAINER_ATTRS        => $this->merge_attrs( [], $this->options[ static::CONTAINER_ATTRS ], true ),
			static::CONTAINER_CLASSES      => $this->merge_classes( [ 'c-video', sprintf( 'c-video--caption-%s', $this->options[ static::CAPTION_POSITION ] ), ], $this->options[ static::CONTAINER_CLASSES ], true ),
			static::CONTAINER_WRAP_CLASSES => $this->merge_classes( [ 'c-video__wrapper' ], $this->options[ static::CONTAINER_WRAP_CLASSES ], true ),
		];

		return $data;
	}
}
