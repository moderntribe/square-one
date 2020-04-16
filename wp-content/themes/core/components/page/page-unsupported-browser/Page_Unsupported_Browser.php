<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Page;

use Tribe\Project\Templates\Components\Context;

class Page_Unsupported_Browser extends Context {
	public const COPYRIGHT    = 'copyright';
	public const HOME_URL     = 'home_url';
	public const BLOG_NAME    = 'name';
	public const STYLES       = 'styles';
	public const FAVICON      = 'favicon';
	public const TITLE        = 'legacy_browser_title';
	public const CONTENT      = 'legacy_browser_content';
	public const LOGO_HEADER  = 'legacy_logo_header';
	public const LOGO_FOOTER  = 'legacy_logo_footer';
	public const ICON_CHROME  = 'legacy_browser_icon_chrome';
	public const ICON_FIREFOX = 'legacy_browser_icon_firefox';
	public const ICON_SAFARI  = 'legacy_browser_icon_safari';
	public const ICON_IE      = 'legacy_browser_icon_ie';

	protected $path = __DIR__ . '/page-unsupported-browser.twig';

	protected $properties = [
		self::COPYRIGHT    => [
			self::DEFAULT => '',
		],
		self::HOME_URL     => [
			self::DEFAULT => '',
		],
		self::BLOG_NAME    => [
			self::DEFAULT => '',
		],
		self::STYLES       => [
			self::DEFAULT => '',
		],
		self::FAVICON      => [
			self::DEFAULT => '',
		],
		self::TITLE        => [
			self::DEFAULT => '',
		],
		self::CONTENT      => [
			self::DEFAULT => '',
		],
		self::LOGO_HEADER  => [
			self::DEFAULT => '',
		],
		self::LOGO_FOOTER  => [
			self::DEFAULT => '',
		],
		self::ICON_CHROME  => [
			self::DEFAULT => '',
		],
		self::ICON_FIREFOX => [
			self::DEFAULT => '',
		],
		self::ICON_SAFARI  => [
			self::DEFAULT => '',
		],
		self::ICON_IE      => [
			self::DEFAULT => '',
		],
	];
}
