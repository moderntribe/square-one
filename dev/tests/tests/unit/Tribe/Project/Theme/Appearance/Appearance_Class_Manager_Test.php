<?php

namespace Tribe\Project\Theme\Appearance;

use Codeception\Test\Unit;
use Tribe\Project\Object_Meta\Appearance\Appearance;

class Appearance_Class_Manager_Test extends Unit {

	public function test_it_gets_a_light_class_from_hex_color_codes() {
		$manager = new Appearance_Class_Manager();

		$this->assertSame( Appearance::CSS_LIGHT_CLASS, $manager->get_class_from_hex( '#fff' ) );
		$this->assertSame( Appearance::CSS_LIGHT_CLASS, $manager->get_class_from_hex( '#ffffff' ) );
		$this->assertSame( Appearance::CSS_LIGHT_CLASS, $manager->get_class_from_hex( '#FFF' ) );
		$this->assertSame( Appearance::CSS_LIGHT_CLASS, $manager->get_class_from_hex( '#FFFFFF' ) );
		$this->assertSame( Appearance::CSS_LIGHT_CLASS, $manager->get_class_from_hex( '#ffff00' ) );
		$this->assertSame( Appearance::CSS_LIGHT_CLASS, $manager->get_class_from_hex( '#e6ffe6' ) );
	}

	public function test_it_gets_a_dark_class_from_hex_color_codes() {
		$manager = new Appearance_Class_Manager();

		$this->assertSame( Appearance::CSS_DARK_CLASS, $manager->get_class_from_hex( '#000' ) );
		$this->assertSame( Appearance::CSS_DARK_CLASS, $manager->get_class_from_hex( '000000' ) );
		$this->assertSame( Appearance::CSS_DARK_CLASS, $manager->get_class_from_hex( '#1a1a00' ) );
		$this->assertSame( Appearance::CSS_DARK_CLASS, $manager->get_class_from_hex( '#000033' ) );
	}

	public function test_it_gets_default_css_class_on_bad_hex_color_codes() {
		$manager = new Appearance_Class_Manager();

		$this->assertSame( Appearance::DEFAULT_CSS_CLASS, $manager->get_class_from_hex( 'not a color' ) );
		$this->assertSame( Appearance::DEFAULT_CSS_CLASS, $manager->get_class_from_hex( 'k' ) );
		$this->assertSame( Appearance::DEFAULT_CSS_CLASS, $manager->get_class_from_hex( '#' ) );
		$this->assertSame( Appearance::DEFAULT_CSS_CLASS, $manager->get_class_from_hex( '?' ) );
		$this->assertSame( Appearance::DEFAULT_CSS_CLASS, $manager->get_class_from_hex( '#üçô' ) );
	}

}
