<?php declare(strict_types=1);

namespace Tribe\Project\Block_Middleware;

use DI;
use Ds\Map;
use Psr\Container\ContainerInterface;
use Tribe\Libs\Container\Definer_Interface;
use Tribe\Libs\Pipeline\Contracts\Pipeline;
use Tribe\Project\Block_Middleware\Guards\Block_Field_Middleware_Guard;
use Tribe\Project\Block_Middleware\Guards\Block_Model_Middleware_Guard;
use Tribe\Project\Block_Middleware\Pipelines\Add_Fields_Pipeline;
use Tribe\Project\Block_Middleware\Pipelines\Add_Model_Data_Pipeline;
use Tribe\Project\Blocks\Blocks_Definer;
use Tribe\Project\Blocks\Contracts\Model;
use Tribe\Project\Blocks\Middleware\Color_Theme\Field_Middleware\Color_Theme_Field_Middleware;
use Tribe\Project\Blocks\Middleware\Color_Theme\Model_Middleware\Color_Theme_Field_Model_Middleware;
use Tribe\Project\Blocks\Middleware\Post_Loop\Field_Middleware\Post_Loop_Field_Middleware;
use Tribe\Project\Blocks\Types\Accordion\Accordion;
use Tribe\Project\Blocks\Types\Accordion\Accordion_Model;
use Tribe\Project\Blocks\Types\Card_Grid\Card_Grid;
use Tribe\Project\Blocks\Types\Content_Loop\Content_Loop;
use Tribe\Project\Blocks\Types\Hero\Hero;
use Tribe\Project\Blocks\Types\Hero\Hero_Model;
use Tribe\Project\Blocks\Types\Interstitial\Interstitial;
use Tribe\Project\Blocks\Types\Interstitial\Interstitial_Model;
use Tribe\Project\Blocks\Types\Media_Text\Media_Text;
use Tribe\Project\Blocks\Types\Media_Text\Media_Text_Model;
use Tribe\Project\Blocks\Types\Stats\Stats;
use Tribe\Project\Blocks\Types\Stats\Stats_Model;
use Tribe\Project\Blocks\Types\Tabs\Tabs;
use Tribe\Project\Blocks\Types\Tabs\Tabs_Model;

/**
 * Base middleware definitions.
 *
 * phpcs:disable SlevomatCodingStandard.TypeHints.UselessConstantTypeHint.UselessVarAnnotation
 *
 * @see \Tribe\Project\Blocks\Blocks_Definer::define()
 */
class Block_Middleware_Definer implements Definer_Interface {

	public const FIELD_MIDDLEWARE_COLLECTION = 'block_middleware.fields';
	public const MODEL_MIDDLEWARE_COLLECTION = 'block_middleware.models';
	public const BLOCK_MIDDLEWARE            = 'block_middleware.block_middleware';

	/**
	 * Define the block models that accept middleware.
	 *
	 * A block can also define specific middleware
	 * as an array.
	 *
	 * @link https://github.com/phpstan/phpstan/issues/7273 (phpstan bug)
	 *
	 * @var array<class-string, bool|\Tribe\Project\Block_Middleware\Contracts\Middleware[]>
	 */
	public const MODEL_MIDDLEWARE = [
		Accordion_Model::class    => true,
		Hero_Model::class         => true,
		Interstitial_Model::class => true,
		Media_Text_Model::class   => true,
		Stats_Model::class        => true,
		Tabs_Model::class         => true,
	];

	public function define(): array {
		return array_merge(
			$this->configure_middleware(),
			$this->define_middleware(),
			$this->decorate_models()
		);
	}

	/**
	 * Configure which blocks will allow middleware and add any custom
	 * block middleware below.
	 *
	 * @note Each block/block model should have a matching definition
	 *       in both MODEL_MIDDLEWARE & BLOCK_MIDDLEWARE constants!
	 */
	private function configure_middleware(): array {
		return [
			/**
			 * Define the blocks that accept middleware.
			 *
			 * A block can also define specific middleware
			 * as an array.
			 *
			 * @var array<class-string, bool|\Tribe\Project\Block_Middleware\Contracts\Middleware[]>
			 */
			self::BLOCK_MIDDLEWARE            => DI\add( [
				Accordion::class    => true,
				Card_Grid::class    => [
					Post_Loop_Field_Middleware::class,
				],
				Content_Loop::class => [
					Post_Loop_Field_Middleware::class,
				],
				Hero::class         => true,
				Interstitial::class => true,
				Media_Text::class   => true,
				Stats::class        => true,
				Tabs::class         => true,
			] ),

			/**
			 * Add custom Block Middleware field definitions to dynamically
			 * attach fields to existing blocks.
			 *
			 * @var \Tribe\Project\Block_Middleware\Contracts\Field_Middleware[]
			 */
			self::FIELD_MIDDLEWARE_COLLECTION => DI\add( [
				DI\get( Color_Theme_Field_Middleware::class ),
				DI\get( Post_Loop_Field_Middleware::class ),
			] ),

			/**
			 * Add custom Block Model middleware to dynamically append new data
			 * to the existing block's model data.
			 *
			 * @var \Tribe\Project\Block_Middleware\Contracts\Model_Middleware[]
			 */
			self::MODEL_MIDDLEWARE_COLLECTION => DI\add( [
				DI\get( Color_Theme_Field_Model_Middleware::class ),
			] ),
		];
	}

	/**
	 * Define the middleware guards and pipelines.
	 *
	 * @note You shouldn't need to touch this.
	 */
	private function define_middleware(): array {
		return [
			Block_Field_Registrar::class        => DI\autowire()->constructorParameter( 'blocks', DI\get( Blocks_Definer::TYPES ) ),
			Block_Field_Middleware_Guard::class => DI\autowire()
				->constructorParameter(
					'allowed_items',
					static fn ( ContainerInterface $c ) => new Map( $c->get( self::BLOCK_MIDDLEWARE ) )
				),

			Block_Model_Middleware_Guard::class => DI\autowire()
				->constructorParameter(
					'allowed_items',
					static fn ( ContainerInterface $c ) => new Map( self::MODEL_MIDDLEWARE )
				),

			Add_Fields_Pipeline::class          => DI\autowire()
				->constructorParameter(
					'pipeline',
					static function ( ContainerInterface $c ) {
						$pipeline = $c->get( Pipeline::class );

						return $pipeline->via( 'add_fields' )->through( $c->get( self::FIELD_MIDDLEWARE_COLLECTION ) );
					},
				),

			Add_Model_Data_Pipeline::class      => DI\autowire()
				->constructorParameter(
					'pipeline',
					static function ( ContainerInterface $c ) {
						$pipeline = $c->get( Pipeline::class );

						return $pipeline->via( 'set_data' )->through( $c->get( self::MODEL_MIDDLEWARE_COLLECTION ) );
					},
				),
		];
	}

	/**
	 * Decorate each block model from the allowed list with the results of the model data pipeline.
	 *
	 * The final result should be a block with new data appended to whatever its existing data.
	 *
	 * @note You shouldn't need to touch this.
	 */
	private function decorate_models(): array {
		$models = [];

		foreach ( array_keys( self::MODEL_MIDDLEWARE ) as $model ) {
			$models[ $model ] = DI\decorate(
				static fn( Model $previous, DI\FactoryInterface $container ) => $container->make( Add_Model_Data_Pipeline::class )->process( $previous )
			);
		}

		return $models;
	}

}
