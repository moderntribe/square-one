<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\comments\comment;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;

/**
 * Class Comment
 */
class Controller extends Abstract_Controller {

	/**
	 * @var int
	 */
	public $comment_id;

	/**
	 * @var array
	 */
	public $classes;

	/**
	 * @var array
	 */
	public $attrs;

	/**
	 * @var string
	 */
	public $edit_link;

	/**
	 * @var string
	 */
	public $reply_link;

	/**
	 * @var array
	 */
	public $time;

	/**
	 * Comment constructor.
	 *
	 * @param array $args
	 */
	public function __construct( array $args = [] ) {
		$args = wp_parse_args( $args, $this->defaults() );
		foreach ( $this->required() as $key => $value ) {
			$args[ $key ] = array_merge( $args[ $key ], $value );
		}
		$this->comment_id = $args[ 'comment_id' ];
		$this->classes    = (array) $args[ 'classes' ];
		$this->attrs      = (array) $args[ 'attrs' ];
		$this->edit_link  = $args[ 'edit_link' ];
		$this->reply_link = $args[ 'reply_link' ];
		$this->time       = (array) $args[ 'time' ];
	}

	/**
	 * @return array
	 */
	protected function defaults(): array {
		return [
			'comment_id' => 0,
			'classes'    => [],
			'attrs'      => [],
			'edit_link'  => '',
			'reply_link' => '',
			'time'       => [],
		];
	}

	/**
	 * @return array
	 */
	protected function required(): array {
		$timestamp = get_comment_time( 'U' );

		return [
			'time' => [
				'c'              => date( 'c', $timestamp ),
				'g:i A - M j, Y' => date( 'g:i A - M j, Y', $timestamp ),
			],
		];
	}

	public function init() {
		$this->attrs[ 'id' ] = sprintf( 'comment-%d', $this->comment_id );
	}

	/**
	 * @return string
	 */
	public function classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	/**
	 * @return string
	 */
	public function attributes(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	/**
	 * @return bool|mixed|string|void
	 */
	public function get_gravatar() {
		$gravatar = get_avatar( $this->comment_id, 150 );
		if ( ! $gravatar ) {
			return '';
		}

		return $gravatar;
	}

	public function get_moderation_message() {
		$status = wp_get_comment_status( $this->comment_id );

		return 'unapproved' === $status ? tribe_template_part( 'components/text/text', null, [
				'content' => __( 'Your comment is awaiting moderation.', 'tribe' ),
				'classes' => [ 'comment__message-moderation ' ],
			]
		)
			: '';
	}
}
