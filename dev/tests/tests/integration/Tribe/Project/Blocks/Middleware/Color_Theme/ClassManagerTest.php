<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Color_Theme;

use Ds\Map;
use Tribe\Tests\Test_Case;

final class ClassManagerTest extends Test_Case {

	public function test_it_gets_css_classes_from_swatch_hex_codes(): void {
		$map = new Map( [
			'#FFF'    => 'white',
			'#000'    => 'black',
			'#00FF00' => 'green',
			'#FFFF00' => 'banana-yellow',
			'#FFC0CB' => 'Cotton Candy Pink',
			'#b0e0e6' => 'poWDer_Blue',
		] );

		$class_manager = new Class_Manager( $map, 't-theme--%s' );

		$this->assertSame( '', $class_manager->get_class( '#A020F0' ) );
		$this->assertSame( 't-theme--white', $class_manager->get_class( '#FFF' ) );
		$this->assertSame( 't-theme--black', $class_manager->get_class( '#000' ) );
		$this->assertSame( 't-theme--green', $class_manager->get_class( '#00FF00' ) );
		$this->assertSame( 't-theme--banana-yellow', $class_manager->get_class( '#FFFF00' ) );
		$this->assertSame( 't-theme--cotton-candy-pink', $class_manager->get_class( '#FFC0CB' ) );
		$this->assertSame( 't-theme--powder_blue', $class_manager->get_class( '#b0e0e6' ) );
	}
}
