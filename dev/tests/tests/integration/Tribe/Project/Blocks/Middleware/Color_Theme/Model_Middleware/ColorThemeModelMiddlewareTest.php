<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Color_Theme\Model_Middleware;

use Mockery;
use Tribe\Project\Block_Middleware\Guards\Block_Model_Middleware_Guard;
use Tribe\Project\Blocks\Contracts\Model;
use Tribe\Project\Blocks\Middleware\Color_Theme\Contracts\Appearance;
use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Tests\Test_Case;

/**
 * Test the specific Color Theme Implementation for this project.
 *
 * This test may need to be updated if the default colors change.
 *
 * @see Appearance
 */
final class ColorThemeModelMiddlewareTest extends Test_Case {

	/**
	 * @var Block_Model_Middleware_Guard|\Mockery\MockInterface
	 */
	private $guard;
	private Color_Theme_Field_Model_Middleware $middleware;

	public function _setUp() {
		parent::_setUp();

		$this->guard = Mockery::mock( Block_Model_Middleware_Guard::class );

		$this->middleware = $this->container->make( Color_Theme_Field_Model_Middleware::class, [
			'guard' => $this->guard,
		] );
	}

	public function _tearDown(): void {
		parent::_tearDown();

		$GLOBALS['post'] = null;
	}

	/**
	 * @note If the appearance white option is removed, this test will fail. Adjust to your project's
	 *       color if required.
	 */
	public function test_it_merges_color_theme_css_classes(): void {
		$post_id = $this->factory()->post->create( [
			'post_type'   => 'post',
			'post_status' => 'publish',
		] );

		update_field( Appearance::COLOR_THEME, '#ffffff', $post_id );

		$GLOBALS['post'] = get_post( $post_id );

		$block_model = new class( [
			'className' => 'custom-css-class another-custom-class',
		] ) extends Base_Model {

			protected function init_data(): array {
				return [
					'classes' => $this->get_classes(),
				];
			}

		};

		$this->guard->shouldReceive( 'allowed' )->andReturnTrue();

		$closure = static function ( Model $model ): Model {
			return $model;
		};

		$model = $this->middleware->set_data( $block_model, $closure );

		$this->assertSame( [
			Color_Theme_Field_Model_Middleware::CLASSES => [
				'custom-css-class',
				'another-custom-class',
				't-theme--white', // color theme class added
			],
		], $model->get_data() );
	}

}
