<?php

namespace Tribe\Project\Templates\Components;

/**
 * Class Video
 *
 * @property string[] $attrs
 * @property string[] $classes
 * @property string   $video_url
 * @property string   $video_title
 * @property string   $trigger_label
 * @property string   $trigger_position
 * @property string   $thumbnail_url
 * @property string   $shim_url
 */
class Video extends Component {

	public const CLASSES          = 'classes';
	public const ATTRS            = 'attrs';
	public const VIDEO_URL        = 'video_url';
	public const VIDEO_TITLE      = 'video_title';
	public const TRIGGER_LABEL    = 'trigger_label';
	public const TRIGGER_POSITION = 'trigger_position';
	public const THUMBNAIL_URL    = 'thumbnail_url';
	public const SHIM_URL         = 'shim_url';

	public const TRIGGER_POSITION_CENTER = 'center';
	public const TRIGGER_POSITION_BOTTOM = 'bottom';

	protected function defaults(): array {
		return [
			self::CLASSES          => [ 'c-video' ],
			self::ATTRS            => [],
			self::VIDEO_URL        => '',
			self::VIDEO_TITLE      => '',
			self::TRIGGER_LABEL    => __( 'Play Video', 'tribe' ),
			self::TRIGGER_POSITION => self::TRIGGER_POSITION_BOTTOM,
			self::THUMBNAIL_URL    => '',
			self::SHIM_URL         => trailingslashit( get_template_directory_uri() ) . 'assets/img/theme/shims/16x9.png',
		];
	}

	public function init() {
		$this->data[ self::CLASSES ][] = sprintf( 'c-video--trigger-%s', $this->data[ self::TRIGGER_POSITION ] );
	}

	public function render(): void {
		?>
		<div {{ classes|stringify }} {{ attrs|stringify }}>
			<a
				href="{{ video_url|esc_url }}"
				class="c-video__trigger"
				data-js="c-video-trigger"
				target="_blank"
				rel="noopener"
				aria-label="{{ __( 'Click to Play Video' )|esc_html }}"
			>
				<img
					class="c-video__thumbnail lazyload"
					src="{{ shim_url|esc_url }}"
					data-src="{{ thumbnail_url|esc_url }}"
					role="presentation"
					alt=""
				/>
				<div class="c-video__trigger-action">
					<span class="c-video__trigger-icon icon-play" aria-hidden="true"></span>
					<span class="c-video__trigger-label">{{ trigger_label|esc_html }}</span>
				</div>
			</a>
		</div>
		<?php
	}
}
