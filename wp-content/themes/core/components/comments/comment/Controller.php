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
	public $author;

	/**
	 * @var string
	 */
	public $edit_link;

	/**
	 * @var string
	 */
	public $gravatar;

	/**
	 * @var string
	 */
	public $comment_text;

	/**
	 * @var string
	 */
	public $moderation_message;

	/**
	 * @var string
	 */
	public $reply_link;

	/**
	 * @var string
	 */
	public $timestamp;

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
		$this->comment_id         = $args[ 'comment_id' ];
		$this->classes            = (array) $args[ 'classes' ];
		$this->attrs              = (array) $args[ 'attrs' ];
		$this->author             = $args[ 'author' ];
		$this->edit_link          = $args[ 'edit_link' ];
		$this->gravatar           = $args[ 'gravatar' ];
		$this->comment_text       = $args[ 'comment_text' ];
		$this->moderation_message = $args[ 'moderation_message' ];
		$this->reply_link         = $args[ 'rely_link' ];
		$this->timestamp          = $args[ 'timestamp' ];
		$this->time               = (array) $args[ 'time' ];
	}

	/**
	 * @return array
	 */
	protected function defaults(): array {
		return [
			'comment_id'         => 0,
			'classes'            => [],
			'attrs'              => [],
			'author'             => '',
			'edit_link'          => '',
			'gravatar'           => '',
			'comment_text'       => '',
			'moderation_message' => '',
			'reply_link'         => '',
			'timestamp'          => 0,
			'time'               => [],
		];
	}

	/**
	 * @return array
	 */
	protected function required(): array {
		return [
			'attrs' => [
				'id' => sprintf( 'comment-%d', $this->comment_id ),
			],
			'time'  => [
				'c'              => date( 'c', $this->timestamp ),
				'g:i A - M j, Y' => date( 'g:i A - M j, Y', $this->timestamp ),
			],
		];
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

}
