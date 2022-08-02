<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Color_Theme;

use Ds\Map;
use Tribe\Libs\Field_Models\Collections\Swatch_Collection;
use Tribe\Tests\Test_Case;

final class ClassManagerTest extends Test_Case {

	public function test_it_gets_css_classes_from_swatch_hex_codes(): void {
		$collection = Swatch_Collection::create( [
			'white'             => [
				'color' => '#FFF',
				'slug'  => 'white',
			],
			'black'             => [
				'color' => '#000',
				'slug'  => 'black',
			],
			'green'             => [
				'color' => '#00FF00',
				'slug'  => 'green',
			],
			'banana-yellow'     => [
				'color' => '#FFFF00',
				'slug'  => 'banana-yellow',
			],
			'Cotton Candy Pink' => [
				'color' => '#FFC0CB',
				'slug'  => 'Cotton Candy Pink',
			],
			'poWDer_Blue'       => [
				'color' => '#b0e0e6',
				'slug'  => 'poWDer_Blue',
			],
		] );

		$class_manager = new Class_Manager( $collection, 't-theme--%s' );

		$this->assertSame( '', $class_manager->get_class( '#A020F0' ) );
		$this->assertSame( 't-theme--white', $class_manager->get_class( '#FFF' ) );
		$this->assertSame( 't-theme--black', $class_manager->get_class( '#000' ) );
		$this->assertSame( 't-theme--green', $class_manager->get_class( '#00FF00' ) );
		$this->assertSame( 't-theme--banana-yellow', $class_manager->get_class( '#FFFF00' ) );
		$this->assertSame( 't-theme--cotton-candy-pink', $class_manager->get_class( '#FFC0CB' ) );
		$this->assertSame( 't-theme--powder_blue', $class_manager->get_class( '#b0e0e6' ) );
	}
}
