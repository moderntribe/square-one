<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components;


class Comments extends Context {
	public const PASSWORD_REQUIRED = 'post_password_required';
	public const HAVE_COMMENTS     = 'have_comments';
	public const OPEN              = 'open';
	public const TITLE             = 'title';
	public const COMMENTS          = 'comments';
	public const FORM              = 'form';
	public const PAGINATION        = 'pagination';

	protected $path = __DIR__ . '/comments.twig';

	protected $properties = [
		self::PASSWORD_REQUIRED => [
			self::DEFAULT => false,
		],
		self::HAVE_COMMENTS     => [
			self::DEFAULT => false,
		],
		self::OPEN              => [
			self::DEFAULT => false,
		],
		self::TITLE             => [
			self::DEFAULT => '',
		],
		self::COMMENTS          => [
			self::DEFAULT => '',
		],
		self::FORM              => [
			self::DEFAULT => '',
		],
		self::PAGINATION        => [
			self::DEFAULT => '',
		],
	];
}
