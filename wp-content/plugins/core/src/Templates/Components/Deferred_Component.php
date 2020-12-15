<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components;

class Deferred_Component extends \ArrayObject {
	/**
	 * @var string
	 */
	private $path;
	/**
	 * @var string|null
	 */
	private $name;

	public function __construct( string $path, string $name = null, array $args = [] ) {
		$this->path = $path;
		$this->name = $name;
		parent::__construct( $args );
	}

	public function render(): string {
		return tribe_template_part( $this->path(), $this->name(), $this->args() );
	}

	public function path(): string {
		return $this->path;
	}

	public function name() {
		return $this->name;
	}

	public function args(): array {
		return $this->getArrayCopy();
	}

	public function __toString(): string {
		return $this->render();
	}
}
