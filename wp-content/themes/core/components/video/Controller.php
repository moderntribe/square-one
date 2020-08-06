<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\video;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;

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
class Controller extends Abstract_Controller {
	private $classes;
	private $attrs;
	public $video_url;
	private $video_title;
	public $trigger_label;
	private $trigger_position;
	public $thumbnail_url;
	public $shim_url;

	public const TRIGGER_POSITION_CENTER = 'center';
	public const TRIGGER_POSITION_BOTTOM = 'bottom';

	public function __construct( array $args = [] ) {
		$this->trigger_position = $args['trigger_position'] ?? self::TRIGGER_POSITION_BOTTOM;
		$this->classes          = array_merge( [ 'c-video', sprintf( 'c-video--trigger-%s', $this->trigger_position ) ], (array) $args['classes'] ?? [] );
		$this->attrs            = (array) ( $args['attrs'] ?? [] );
		$this->video_url        = $args['video_url'] ?? '';
		$this->video_title      = $args['video_title'] ?? '';
		$this->trigger_label    = $args['trigger_label'] ?? __( 'Play Video', 'tribe' );
		$this->thumbnail_url    = $args['thumbnail_url'] ?? '';
		$this->shim_url         = $args['shim_url'] ?? trailingslashit( get_template_directory_uri() ) . 'assets/img/theme/shims/16x9.png';
	}

	public function classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function attributes(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}
}
