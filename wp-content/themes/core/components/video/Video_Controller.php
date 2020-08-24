<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\video;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;

class Video_Controller extends Abstract_Controller {
	public const CLASSES                 = 'classes';
	public const ATTRS                   = 'attrs';
	public const VIDEO_URL               = 'video_url';
	public const VIDEO_TITLE             = 'video_title';
	public const TRIGGER_LABEL           = 'trigger_label';
	public const THUMBNAIL_URL           = 'thumbnail_url';
	public const SHIM_URL                = 'shim_url';
	public const TRIGGER_POSITION        = 'trigger_position';
	public const TRIGGER_POSITION_CENTER = 'center';
	public const TRIGGER_POSITION_BOTTOM = 'bottom';

	private array  $classes;
	private array  $attrs;
	private string $video_url;
	private string $video_title;
	private string $trigger_label;
	private string $thumbnail_url;
	private string $shim_url;
	private string $trigger_position;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->classes          = (array) $args[ self::CLASSES ];
		$this->attrs            = (array) $args[ self::ATTRS ];
		$this->video_url        = (string) $args[ self::VIDEO_URL ];
		$this->video_title      = (string) $args[ self::VIDEO_TITLE ];
		$this->trigger_label    = (string) $args[ self::TRIGGER_LABEL ];
		$this->thumbnail_url    = (string) $args[ self::THUMBNAIL_URL ];
		$this->shim_url         = (string) $args[ self::SHIM_URL ];
		$this->trigger_position = (string) $args[ self::TRIGGER_POSITION ];
	}

	protected function defaults(): array {
		return [
			self::CLASSES          => [],
			self::ATTRS            => [],
			self::VIDEO_URL        => '',
			self::VIDEO_TITLE      => '',
			self::TRIGGER_LABEL    => __( 'Play Video', 'tribe' ),
			self::THUMBNAIL_URL    => '',
			self::SHIM_URL         => trailingslashit( get_template_directory_uri() ) . 'assets/img/theme/shims/16x9.png',
			self::TRIGGER_POSITION => self::TRIGGER_POSITION_BOTTOM,
		];
	}

	protected function required(): array {
		return [
			self::CLASSES => [ 'c-video', sprintf( 'c-video--trigger-%s', $this->trigger_position ) ],
		];
	}

	public function get_classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_video_url(): string {
		return esc_url( $this->video_url );
	}

	public function get_image_shim_url(): string {
		return esc_url( $this->shim_url );
	}

	public function get_image_url(): string {
		return esc_url( $this->thumbnail_url );
	}

	public function get_trigger_label(): string {
		return esc_html( $this->trigger_label );
	}
}
