<?php

namespace Tribe\Project;

use WP_Query;
use Tribe\Tests\Test_Case;
use Tribe\Project\Object_Meta\Appearance\Appearance;
use Tribe\Project\Theme\Appearance\Appearance_Identifier;
use Tribe\Project\Theme\Appearance\Appearance_Class_Manager;

class Appearance_Identifier_Test extends Test_Case {

	private Appearance_Class_Manager $manager;

	public function _setUp() {
		parent::_setUp();

		$this->manager = new Appearance_Class_Manager();
	}

	public function test_it_gets_default_global_color_theme() {
		$identifier = new Appearance_Identifier( $this->manager );

		$this->assertSame( Appearance::COLOR_THEME_DEFAULT, $identifier->current_theme() );
		$this->assertSame( Appearance::DEFAULT_CSS_CLASS, $identifier->get_body_class() );
	}

	public function test_it_gets_global_set_color_theme() {
		update_field( Appearance::COLOR_THEME, Appearance::OPTION_DARK, 'option' );

		$identifier = new Appearance_Identifier( $this->manager );

		$this->assertSame( Appearance::OPTION_DARK, $identifier->current_theme() );
		$this->assertSame( Appearance::CSS_DARK_CLASS, $identifier->get_body_class() );

		update_field( Appearance::COLOR_THEME, Appearance::OPTION_LIGHT, 'option' );

		$this->assertSame( Appearance::OPTION_LIGHT, $identifier->current_theme() );
		$this->assertSame( Appearance::CSS_LIGHT_CLASS, $identifier->get_body_class() );
	}

	public function test_it_gets_post_overridden_color_theme() {
		global $wp_query;

		$post = $this->factory()->post->create_and_get();

		// Make is_singular() return true
		$wp_query                 = new WP_Query();
		$wp_query->is_singular    = true;
		$wp_query->queried_object = $post;

		update_field( Appearance::COLOR_THEME, Appearance::OPTION_LIGHT, 'option' );
		update_field( Appearance::COLOR_THEME, Appearance::OPTION_DARK, $post->ID );

		$identifier = new Appearance_Identifier( $this->manager );

		$this->assertSame( Appearance::OPTION_LIGHT, $identifier->current_theme() );
		$this->assertSame( Appearance::CSS_LIGHT_CLASS, $identifier->get_body_class() );

		// Toggle must be checked
		update_field( Appearance::PAGE_THEME_OVERRIDE, '1', $post->ID );

		$this->assertSame( Appearance::OPTION_DARK, $identifier->current_theme() );
		$this->assertSame( Appearance::CSS_DARK_CLASS, $identifier->get_body_class() );
	}

}
