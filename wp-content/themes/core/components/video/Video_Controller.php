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

	private array $classes;
	private array $attrs;
	private string $video_url;
	private string $video_title;
	private string $trigger_label;
	private string $thumbnail_url;
	private string $shim_url;
	private string $trigger_position;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->classes          = (array) $args['classes'];
		$this->attrs            = (array) $args['attrs'];
		$this->video_url        = (string) $args['video_url'];
		$this->video_title      = (string) $args['video_title'];
		$this->trigger_label    = (string) $args['trigger_label'];
		$this->thumbnail_url    = (string) $args['thumbnail_url'];
		$this->shim_url         = (string) $args['shim_url'];
		$this->trigger_position = (string) $args['trigger_position'];
	}

	protected function defaults(): array {
		return [
			'classes'          => [],
			'attrs'            => [],
			'video_url'        => '',
			'video_title'      => '',
			'trigger_label'    => __( 'Play Video', 'tribe' ),
			'thumbnail_url'    => '',
			'shim_url'         => trailingslashit( get_template_directory_uri() ) . 'assets/img/theme/shims/16x9.png',
			'trigger_position' => self::TRIGGER_POSITION_BOTTOM,
		];
	}

	protected function required(): array {
		return [
			'classes' => [ 'c-video', sprintf( 'c-video--trigger-%s', $this->trigger_position ) ],
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
