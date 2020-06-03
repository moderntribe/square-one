<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Comments;

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
class Comment extends Context {
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

	protected $path = __DIR__ . '/comment.twig';

	protected $properties = [
		self::COMMENT_ID         => [
			self::DEFAULT => 0,
		],
		self::CLASSES            => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'post-interaction' ],
		],
		self::ATTRIBUTES         => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [],
		],
		self::AUTHOR             => [
			self::DEFAULT => '',
		],
		self::EDIT_LINK          => [
			self::DEFAULT => '',
		],
		self::GRAVATAR           => [
			self::DEFAULT => '',
		],
		self::COMMENT_TEXT       => [
			self::DEFAULT => '',
		],
		self::MODERATION_MESSAGE => [
			self::DEFAULT => '',
		],
		self::REPLY_LINK         => [
			self::DEFAULT => '',
		],
		self::TIMESTAMP          => [
			self::DEFAULT => 0,
		],
	];

	public function get_data(): array {
		$this->properties[ self::ATTRIBUTES ][ self::MERGE_ATTRIBUTES ]['id'] = sprintf( 'comment-%d', $this->comment_id );

		$data = parent::get_data();

		// massage the timestamp into the formats we need in the template
		$data['time'] = [
			'c'              => date( 'c', $data[ self::TIMESTAMP ] ),
			'g:i A - M j, Y' => date( 'g:i A - M j, Y', $data[ self::TIMESTAMP ] ),
		];
		unset( $data[ self::TIMESTAMP ] );

		return $data;
	}


}
