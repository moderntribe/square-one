<?php

namespace Tribe\Project\Templates\Components;

class Video extends Component {

	protected $path = 'components/video.twig';

	const TITLE                  = 'title';
	const CAPTION_POSITION       = 'caption_position';
	const VIDEO_URL              = 'video_url';
	const THUMBNAIL_URL          = 'thumbnail_url';
	const PLAY_TEXT              = 'play_text';
	const SHIM                   = 'shim';
	const EMBED_CLASSES          = 'embed_classes';
	const CONTAINER_ATTRS        = 'container_attrs';
	const CONTAINER_CLASSES      = 'container_classes';
	const CONTAINER_WRAP_CLASSES = 'container_wrap_classes';

	const CAPTION_POSITION_CENTER = 'center';
	const CAPTION_POSITION_BOTTOM = 'bottom';
	const CAPTION_POSITION_BELOW  = 'below';

	protected function parse_options( array $options ): array {
		$defaults = [
			self::TITLE                  => '',
			self::CAPTION_POSITION       => self::CAPTION_POSITION_CENTER, // possible options: center, bottom, below
			self::VIDEO_URL              => '',
			self::THUMBNAIL_URL          => '',
			self::PLAY_TEXT              => __( 'Play Video', 'tribe' ),
			self::SHIM                   => trailingslashit( get_stylesheet_directory_uri() ) . 'img/theme/shims/16x9.png',
			self::EMBED_CLASSES          => [],
			self::CONTAINER_ATTRS        => [],
			self::CONTAINER_CLASSES      => [],
			self::CONTAINER_WRAP_CLASSES => [],
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			self::TITLE                  => $this->options[ self::TITLE ],
			self::CAPTION_POSITION       => $this->options[ self::CAPTION_POSITION ],
			self::VIDEO_URL              => $this->options[ self::VIDEO_URL ],
			self::THUMBNAIL_URL          => $this->options[ self::THUMBNAIL_URL ],
			self::PLAY_TEXT              => $this->options[ self::PLAY_TEXT ],
			self::SHIM                   => $this->options[ self::SHIM ],
			self::EMBED_CLASSES          => $this->merge_classes( [ 'c-video__embed' ], $this->options[ self::EMBED_CLASSES ], true ),
			self::CONTAINER_ATTRS        => $this->merge_attrs( [], $this->options[ self::CONTAINER_ATTRS ], true ),
			self::CONTAINER_CLASSES      => $this->merge_classes( [ 'c-video', sprintf( 'c-video--caption-%s', $this->options[ self::CAPTION_POSITION ] ), ], $this->options[ self::CONTAINER_CLASSES ], true ),
			self::CONTAINER_WRAP_CLASSES => $this->merge_classes( [ 'c-video__wrapper' ], $this->options[ self::CONTAINER_WRAP_CLASSES ], true ),
		];

		return $data;
	}
}
