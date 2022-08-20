<?php declare(strict_types=1);

namespace Tribe\Project\Block_Middleware\Pipelines;

use Ds\Map;
use Tribe\Libs\Pipeline\Pipeline;
use Tribe\Project\Block_Middleware\Guards\Block_Model_Middleware_Guard;
use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Tests\Fixtures\Description_Model_Middleware;
use Tribe\Tests\Fixtures\Subtitle_Model_Middleware;
use Tribe\Tests\Test_Case;

class AddModelDataPipelineTest extends Test_Case {

	public function test_it_processes_block_model_middleware_pipeline(): void {
		$modelOne = new class( [
			'name' => 'acf/modelone'
		] ) extends Base_Model {

			protected function init_data(): array {
				return [
					'classes' => [
						'class-one',
						'class-two',
					],
					'title' => 'hello world',
				];
			}
		};

		$this->assertSame( [
			'classes' => [
				'class-one',
				'class-two',
			],
			'title'   => 'hello world',
		], $modelOne->get_data() );

		$model_guard = new Block_Model_Middleware_Guard( new Map( [
			get_class( $modelOne ) => true,
		] ) );

		$pipeline = $this->container->get( Pipeline::class )
		                            ->via( 'set_data' )
		                            ->through( [
			                            new Description_Model_Middleware( $model_guard ),
			                            new Subtitle_Model_Middleware( $model_guard ),
		                            ] );

		$processed_model = ( new Add_Model_Data_Pipeline( $pipeline ) )->process( $modelOne );

		$this->assertEquals( [
			'classes'     => [
				'class-one',
				'class-two',
			],
			'title'       => 'hello world',
			'description' => 'This is new data',
			'subtitle'    => 'hello world again',
		], $processed_model->get_data() );
	}

	public function test_it_processes_specific_block_model_middleware_pipeline(): void {
		$modelOne = new class( [
			'name' => 'acf/modelone'
		] ) extends Base_Model {

			protected function init_data(): array {
				return [
					'classes' => [
						'class-one',
						'class-two',
					],
					'title' => 'hello world',
				];
			}
		};

		$this->assertSame( [
			'classes' => [
				'class-one',
				'class-two',
			],
			'title'   => 'hello world',
		], $modelOne->get_data() );

		$model_guard = new Block_Model_Middleware_Guard( new Map( [
			get_class( $modelOne ) => [
				Subtitle_Model_Middleware::class,
			],
		] ) );

		$pipeline = $this->container->get( Pipeline::class )
		                            ->via( 'set_data' )
		                            ->through( [
			                            new Description_Model_Middleware( $model_guard ),
			                            new Subtitle_Model_Middleware( $model_guard ),
		                            ] );

		$processed_model = ( new Add_Model_Data_Pipeline( $pipeline ) )->process( $modelOne );

		$this->assertEquals( [
			'classes'     => [
				'class-one',
				'class-two',
			],
			'title'       => 'hello world',
			'subtitle'    => 'hello world again',
		], $processed_model->get_data() );
	}
}
