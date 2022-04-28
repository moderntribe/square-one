<?php
namespace Tribe\Project\Integrations\ACF;

use tad\FunctionMocker\FunctionMocker;

class Max_Length_CounterTest extends \Codeception\Test\Unit {
	/**
	 * @var \UnitTester
	 */
	protected $tester;

	protected function _setUpBeforeClass(): void {
		FunctionMocker::setUp();
		FunctionMocker::replace( 'esc_attr', static function ( $string ) {
			return $string;
		} );
		FunctionMocker::replace( 'esc_html', static function ( $string ) {
			return $string;
		} );
	}

	protected function _before() {
	}

	protected function _after() {
	}

	/**
     * @dataProvider fieldDataProvider
     */
    public function test_add_counter_div( $field, $expected ) {
		$counter = new Max_Length_Counter();
		$counter->add_counter_div( $field );
		$output = $this->getActualOutput();
		$this->assertEquals( $expected, $output );
	}

	public function fieldDataProvider() {
		return [
			'show counter with no value' => [
				[
					'wrapper' => [
						'class' => Max_Length_Counter::MAX_LENGTH_COUNTER_WRAPPER_CLASS,
					],
					'maxlength' => '10',
					'value' => '',
				],
				'<div class="textright ' . Max_Length_Counter::MAX_LENGTH_COUNTER_DIV_CLASS . '">0 / 10</div>'
			],
			'show counter with value length of 5' => [
				[
					'wrapper' => [
						'class' => Max_Length_Counter::MAX_LENGTH_COUNTER_WRAPPER_CLASS,
					],
					'maxlength' => '10',
					'value' => '12345',
				],
				'<div class="textright ' . Max_Length_Counter::MAX_LENGTH_COUNTER_DIV_CLASS . '">5 / 10</div>'
			],
			'no maxlength set' => [
				[
					'wrapper' => [
						'class' => Max_Length_Counter::MAX_LENGTH_COUNTER_WRAPPER_CLASS,
					],
					'maxlength' => '',
					'value' => '12345',
				],
				NULL
			],
			'no wrapper class set' => [
				[
					'wrapper' => [
						'class' => '',
					],
					'maxlength' => '10',
					'value' => '',
				],
				NULL
			],
			'wrapper class not included' => [
				[
					'wrapper' => [
						'class' => 'cool-class another-cool-class',
					],
					'maxlength' => '10',
					'value' => '',
				],
				NULL
			],
			'wrapper class with no maxlength' => [
				[
					'wrapper' => [
						'class' => Max_Length_Counter::MAX_LENGTH_COUNTER_DIV_CLASS,
					],
					'maxlength' => '0',
					'value' => '',
				],
				NULL
			]
		];
	}
}
