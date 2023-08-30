<?php declare(strict_types=1);

namespace Tribe\Project\Integrations\ACF;

use Brain\Monkey;
use Tribe\Tests\Unit;

final class Max_Length_CounterTest extends Unit {

	/**
	 * @dataProvider fieldDataProvider
	 */
	public function test_it_adds_counter_div( array $field, ?string $expected ) {
		Monkey\Functions\when( 'esc_attr' )->returnArg();
		Monkey\Functions\when( 'esc_html' )->returnArg();

		$counter = new Max_Length_Counter();
		$counter->add_counter_div( $field );
		$output = $this->getActualOutput();
		$this->assertEquals( $expected, $output );
	}

	public function fieldDataProvider(): array {
		return [
			'show counter with no value'          => [
				[
					'wrapper'   => [
						'class' => Max_Length_Counter::MAX_LENGTH_COUNTER_WRAPPER_CLASS,
					],
					'maxlength' => '10',
					'value'     => '',
				],
				'<div class="textright ' . Max_Length_Counter::MAX_LENGTH_COUNTER_DIV_CLASS . '">0 / 10</div>',
			],
			'show counter with value length of 5' => [
				[
					'wrapper'   => [
						'class' => Max_Length_Counter::MAX_LENGTH_COUNTER_WRAPPER_CLASS,
					],
					'maxlength' => '10',
					'value'     => '12345',
				],
				'<div class="textright ' . Max_Length_Counter::MAX_LENGTH_COUNTER_DIV_CLASS . '">5 / 10</div>',
			],
			'no maxlength set'                    => [
				[
					'wrapper'   => [
						'class' => Max_Length_Counter::MAX_LENGTH_COUNTER_WRAPPER_CLASS,
					],
					'maxlength' => '',
					'value'     => '12345',
				],
				null,
			],
			'no wrapper class set'                => [
				[
					'wrapper'   => [
						'class' => '',
					],
					'maxlength' => '10',
					'value'     => '',
				],
				null,
			],
			'wrapper class not included'          => [
				[
					'wrapper'   => [
						'class' => 'cool-class another-cool-class',
					],
					'maxlength' => '10',
					'value'     => '',
				],
				null,
			],
			'wrapper class with no maxlength'     => [
				[
					'wrapper'   => [
						'class' => Max_Length_Counter::MAX_LENGTH_COUNTER_DIV_CLASS,
					],
					'maxlength' => '0',
					'value'     => '',
				],
				null,
			],
		];
	}

}
