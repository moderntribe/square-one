<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Comments;

use Tribe\Project\Templates\Components\Component;
use Tribe\Project\Templates\Components\Context;

/**
 * Class Comment
 *
 * @property int      $comment_id
 * @property string[] $classes
 * @property string[] $attr
 * @property string   $author
 * @property string   $edit_link
 * @property string   $gravatar
 * @property string   $comment_text
 * @property string   $moderation_message
 * @property string   $reply_link
 */
class Comment extends Component {

	public const COMMENT_ID         = 'comment_id';
	public const CLASSES            = 'classes';
	public const ATTRIBUTES         = 'attr';
	public const AUTHOR             = 'author';
	public const EDIT_LINK          = 'edit_link';
	public const GRAVATAR           = 'gravatar';
	public const COMMENT_TEXT       = 'comment_text';
	public const MODERATION_MESSAGE = 'moderation_message';
	public const REPLY_LINK         = 'reply_link';
	public const TIMESTAMP          = 'timestamp';
	public const TIME               = 'time';


	protected function defaults(): array {
		return [
			self::COMMENT_ID         => 0,
			self::CLASSES            => [],
			self::ATTRIBUTES         => [],
			self::AUTHOR             => '',
			self::EDIT_LINK          => '',
			self::GRAVATAR           => '',
			self::COMMENT_TEXT       => '',
			self::MODERATION_MESSAGE => '',
			self::REPLY_LINK         => '',
			self::TIMESTAMP          => 0,
			self::TIME               => [],
		];
	}

	public function init() {
		$this->data[ self::ATTRIBUTES ]['id'] = sprintf( 'comment-%d', $this->data[ self::COMMENT_ID ] );

		// massage the timestamp into the formats we need in the template
		$this->data['time'] = [
			'c'              => date( 'c', $this->data[ self::TIMESTAMP ] ),
			'g:i A - M j, Y' => date( 'g:i A - M j, Y', $this->data[ self::TIMESTAMP ] ),
		];
	}
}
