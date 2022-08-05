<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Patterns;

interface Pattern_Interface {

	public const TITLE         = 'title';
	public const DESCRIPTION   = 'description';
	public const CONTENT       = 'content';
	public const CATEGORIES    = 'categories';
	public const KEYWORDS      = 'keywords';
	public const VIEWPORTWIDTH = 'viewportWidth';
	public const BLOCKTYPES    = 'blockTypes';
	public const POSTTYPES     = 'postTypes';
	public const INSERTER      = 'inserter';

	public function register(): void;

}
