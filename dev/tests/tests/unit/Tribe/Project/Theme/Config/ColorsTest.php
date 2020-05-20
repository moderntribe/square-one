<?php namespace Tribe\Project\Theme\Config;

class ColorsTest extends \Codeception\Test\Unit {
	/**
	 * @var \UnitTester
	 */
	protected $tester;

	protected function _before() {
	}

	protected function _after() {
	}

	public function test_stores_colors() {
		$config = [
			'white' => [ 'color' => '#ffffff', 'label' => 'White' ],
			'black' => [ 'color' => '#000000', 'label' => 'Black' ],
			'red'   => [ 'color' => '#ff0000', 'label' => 'Red' ],
		];
		$colors = new Colors( $config );
		$this->assertEquals( $config, $colors->get_colors() );
	}

	public function test_get_subset() {
		$config = [
			'white' => [ 'color' => '#ffffff', 'label' => 'White' ],
			'black' => [ 'color' => '#000000', 'label' => 'Black' ],
			'red'   => [ 'color' => '#ff0000', 'label' => 'Red' ],
		];
		$colors = new Colors( $config );
		$this->assertEquals( [
			'black' => [ 'color' => '#000000', 'label' => 'Black' ],
			'red'   => [ 'color' => '#ff0000', 'label' => 'Red' ],
		], $colors->get_subset( [ 'black', 'red' ] )->get_colors() );
	}

	public function test_format_for_blocks() {
		$config = [
			'white' => [ 'color' => '#ffffff', 'label' => 'White' ],
			'black' => [ 'color' => '#000000', 'label' => 'Black' ],
		];
		$colors = new Colors( $config );

		$this->assertEquals( [
			[
				'name'  => 'White',
				'slug'  => 'white',
				'color' => '#ffffff',
			],
			[
				'name'  => 'Black',
				'slug'  => 'black',
				'color' => '#000000',
			],
		], $colors->format_for_blocks() );
	}

	public function test_format_for_acf() {
		$config = [
			'white' => [ 'color' => '#ffffff', 'label' => 'White' ],
			'black' => [ 'color' => '#000000', 'label' => 'Black' ],
		];
		$colors = new Colors( $config );
		$this->assertEquals( [
			'#ffffff' => 'White',
			'#000000' => 'Black',
		], $colors->format_for_acf() );
	}

	public function test_get_name_by_value() {
		$config = [
			'white' => [ 'color' => '#ffffff', 'label' => 'White' ],
			'black' => [ 'color' => '#000000', 'label' => 'Black' ],
		];
		$colors = new Colors( $config );
		$this->assertEquals( 'black', $colors->get_name_by_value( '#000000' ) );
	}
}
