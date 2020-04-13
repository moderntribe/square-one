<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Comments;


use Tribe\Project\Templates\Components\Context;

/**
 * Class Trackback
 *
 * @property int      $comment_id
 * @property string[] $classes
 * @property string[] $attr
 * @property string   $label
 * @property string   $author_link
 * @property string   $edit_link
 */
class Trackback extends Context {
	public const COMMENT_ID  = 'comment_id';
	public const CLASSES     = 'classes';
	public const ATTRIBUTES  = 'attr';
	public const LABEL       = 'label';
	public const AUTHOR_LINK = 'author_link';
	public const EDIT_LINK   = 'edit_link';

	protected $path = __DIR__ . '/trackback.twig';

	protected $properties = [
		self::COMMENT_ID  => [
			self::DEFAULT => 0,
		],
		self::CLASSES     => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'post-interaction' ],
		],
		self::ATTRIBUTES  => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [],
		],
		self::LABEL       => [
			self::DEFAULT => '',
		],
		self::AUTHOR_LINK => [
			self::DEFAULT => '',
		],
		self::EDIT_LINK   => [
			self::DEFAULT => '',
		],
	];

	public function get_data(): array {
		$this->properties[ self::ATTRIBUTES ][ self::MERGE_ATTRIBUTES ]['id'] = sprintf( 'comment-%d', $this->comment_id );

		return parent::get_data();
	}


}
