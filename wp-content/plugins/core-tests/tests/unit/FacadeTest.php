<?php

class FacadeTest extends \Codeception\Test\Unit {
	/**
	 * @var \UnitTester
	 */
	protected $tester;

	protected function _before() {
		parent::_before();

		\tad\FunctionMocker\FunctionMocker::init();

		$request_mock = $this->makeEmpty( \Tribe\Project\Request\Request::class, [ 'all' => function () {
			return [ 'original' => true ];
		} ] );

		$container_mock = $this->makeEmpty( \Tribe\Project\Core::class, [ 'container' => function () use ( $request_mock ) {
			return [
				'request' => $request_mock,
			];
		} ] );

		\tad\FunctionMocker\FunctionMocker::replace( 'tribe_project', function () use ( $container_mock ) {
			return $container_mock;
		} );
	}

	public function testMock() {
		$all        = [ 'foo' => 'bar', 'bash' => 'baz' ];
		$all_2      = [ 'this' => 'that' ];
		$original   = [ 'original' => true ];
		$test_class = new class {
			public function return_all() {
				return \Tribe\Project\Facade\Items\Request::all();
			}
		};

		\Tribe\Project\Facade\Items\Request::make( [ 'all' => function () use ( $all ) {
			return $all;
		} ] );

		$this->assertEquals( $all, $test_class->return_all() );

		\Tribe\Project\Facade\Items\Request::make( [ 'all' => function () use ( $all_2 ) {
			return $all_2;
		} ] );

		$this->assertEquals( $all_2, $test_class->return_all() );

		\Tribe\Project\Facade\Items\Request::destroy_mock();

		$this->assertEquals( $original, $test_class->return_all() );
	}
}