<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks;

use DI;
use Tribe\Libs\Container\Definer_Interface;
use Tribe\Project\Blocks\Builder\Block_Builder;
use Tribe\Project\Blocks\Builder\Builder_Factory;
use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Controllers;

class Blocks_Definer implements Definer_Interface {
	public const TYPES          = 'blocks.types';
	public const CONTROLLER_MAP = 'blocks.controller_map';
	public const BLACKLIST      = 'blocks.blacklist';

	public function define(): array {
		return [
			self::TYPES => [
				DI\get( Types\Accordion::class ),
				DI\get( Types\Accordion_Section::class ),
			],

			self::CONTROLLER_MAP => [
				Types\Accordion::NAME => Controllers\Block\Accordion::class,
			],

			/**
			 * An array of core/3rd-party block types that should be unregistered
			 */
			self::BLACKLIST      => [
				'core/buttons',
				'core/button',
				'core/rss',
				'core/social-links',
				'core/spacer',
			],

			Render_Filter::class => DI\create()
				->constructor( DI\get( Component_Factory::class ), DI\get( self::CONTROLLER_MAP ) ),

			Allowed_Blocks::class => DI\create()
				->constructor( DI\get( self::BLACKLIST ) ),

			\Tribe\Gutenpanels\Builder\Block_Builder::class             => DI\get( Block_Builder::class ),
			\Tribe\Gutenpanels\Builder\Factories\Builder_Factory::class => DI\get( Builder_Factory::class ),
		];
	}
}
