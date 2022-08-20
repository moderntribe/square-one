<?php declare(strict_types=1);

namespace Tribe\Project\Block_Middleware\Guards;

use Ds\Map;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Project\Block_Middleware\Contracts\Middleware;
use Tribe\Project\Blocks\Contracts\Model;
use Tribe\Tests\Unit;

final class MiddlewareGuardTest extends Unit {

	public function test_it_allows_all_middleware_for_blocks(): void {
		$blockOne = new class extends Block_Config {
			public function add_block() {
			}
		};

		$blockTwo = new class extends Block_Config {
			public function add_block() {
			}
		};

		$middleware = new class implements Middleware {};

		$allowed_items = [
			get_class( $blockOne ) => true,
			get_class( $blockTwo ) => true,
		];

		$guard = new Middleware_Guard( new Map( $allowed_items ) );

		$this->assertTrue( $guard->allowed( $blockOne, $middleware ) );
		$this->assertTrue( $guard->allowed( $blockTwo, $middleware ) );
	}

	public function test_it_denies_middleware_for_missing_blocks(): void {
		$blockOne = new class extends Block_Config {
			public function add_block() {
			}
		};

		$blockTwo = new class extends Block_Config {
			public function add_block() {
			}
		};

		$middleware = new class implements Middleware {};

		$allowed_items = [
			get_class( $blockOne ) => true,
		];

		$guard = new Middleware_Guard( new Map( $allowed_items ) );

		$this->assertTrue( $guard->allowed( $blockOne, $middleware ) );
		$this->assertFalse( $guard->allowed( $blockTwo, $middleware ) );
	}

	public function test_it_explicitly_denies_middleware_for_blocks(): void {
		$blockOne = new class extends Block_Config {
			public function add_block() {
			}
		};

		$blockTwo = new class extends Block_Config {
			public function add_block() {
			}
		};

		$middleware = new class implements Middleware {};

		$allowed_items = [
			get_class( $blockOne ) => false,
			get_class( $blockTwo ) => true,
		];

		$guard = new Middleware_Guard( new Map( $allowed_items ) );

		$this->assertFalse( $guard->allowed( $blockOne, $middleware ) );
		$this->assertTrue( $guard->allowed( $blockTwo, $middleware ) );
	}

	public function test_it_allows_specific_middleware_for_blocks(): void {
		$blockOne = new class extends Block_Config {
			public function add_block() {
			}
		};

		$blockTwo = new class extends Block_Config {
			public function add_block() {
			}
		};

		$allowed_middleware_block_one = new class implements Middleware {};
		$allowed_middleware_block_two = new class implements Middleware {};

		$allowed_items = [
			get_class( $blockOne ) => [
				get_class( $allowed_middleware_block_one ),
			],
			get_class( $blockTwo ) => [
				get_class( $allowed_middleware_block_two ),
			]
		];

		$guard = new Middleware_Guard( new Map( $allowed_items ) );

		$this->assertTrue( $guard->allowed( $blockOne, $allowed_middleware_block_one ) );
		$this->assertFalse( $guard->allowed( $blockOne, $allowed_middleware_block_two ) );

		$this->assertTrue( $guard->allowed( $blockTwo, $allowed_middleware_block_two ) );
		$this->assertFalse( $guard->allowed( $blockTwo, $allowed_middleware_block_one ) );
	}

	public function test_it_allows_block_model_middleware_for_all_blocks(): void {
		$modelOne = new class implements Model {
			public function set_data( array $data ): Model {
				return $this;
			}

			public function get_data(): array {
				return [];
			}
		};

		$modelTwo = new class implements Model {
			public function set_data( array $data ): Model {
				return $this;
			}

			public function get_data(): array {
				return [];
			}
		};

		$middleware = new class implements Middleware {};

		$allowed_items = [
			get_class( $modelOne ) => true,
			get_class( $modelTwo ) => true,
		];

		$guard = new Middleware_Guard( new Map( $allowed_items ) );

		$this->assertTrue( $guard->allowed( $modelOne, $middleware ) );
		$this->assertTrue( $guard->allowed( $modelTwo, $middleware ) );
	}

	public function test_it_allows_specific_middleware_for_models(): void {
		$modelOne = new class implements Model {
			public function set_data( array $data ): Model {
				return $this;
			}

			public function get_data(): array {
				return [];
			}
		};

		$modelTwo = new class implements Model {
			public function set_data( array $data ): Model {
				return $this;
			}

			public function get_data(): array {
				return [];
			}
		};

		$allowed_middleware_model_one = new class implements Middleware {};
		$allowed_middleware_model_two = new class implements Middleware {};

		$allowed_items = [
			get_class( $modelOne ) => [
				get_class( $allowed_middleware_model_one ),
			],
			get_class( $modelTwo ) => [
				get_class( $allowed_middleware_model_two ),
			]
		];

		$guard = new Middleware_Guard( new Map( $allowed_items ) );

		$this->assertTrue( $guard->allowed( $modelOne, $allowed_middleware_model_one ) );
		$this->assertFalse( $guard->allowed( $modelOne, $allowed_middleware_model_two ) );

		$this->assertTrue( $guard->allowed( $modelTwo, $allowed_middleware_model_two ) );
		$this->assertFalse( $guard->allowed( $modelTwo, $allowed_middleware_model_one ) );
	}

	public function test_it_denies_block_model_middleware_for_missing_blocks(): void {
		$modelOne = new class implements Model {
			public function set_data( array $data ): Model {
				return $this;
			}

			public function get_data(): array {
				return [];
			}
		};

		$modelTwo = new class implements Model {
			public function set_data( array $data ): Model {
				return $this;
			}

			public function get_data(): array {
				return [];
			}
		};

		$middleware = new class implements Middleware {};

		$allowed_items = [
			get_class( $modelOne ) => true,
		];

		$guard = new Middleware_Guard( new Map( $allowed_items ) );

		$this->assertTrue( $guard->allowed( $modelOne, $middleware ) );
		$this->assertFalse( $guard->allowed( $modelTwo, $middleware ) );
	}

	public function test_it_explicitly_denies_block_model_middleware_for_blocks(): void {
		$modelOne = new class implements Model {
			public function set_data( array $data ): Model {
				return $this;
			}

			public function get_data(): array {
				return [];
			}
		};

		$modelTwo = new class implements Model {
			public function set_data( array $data ): Model {
				return $this;
			}

			public function get_data(): array {
				return [];
			}
		};

		$middleware = new class implements Middleware {};

		$allowed_items = [
			get_class( $modelOne ) => false,
			get_class( $modelTwo ) => true,
		];

		$guard = new Middleware_Guard( new Map( $allowed_items ) );

		$this->assertFalse( $guard->allowed( $modelOne, $middleware ) );
		$this->assertTrue( $guard->allowed( $modelTwo, $middleware ) );
	}

}
