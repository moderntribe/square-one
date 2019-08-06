<?php

namespace Tribe\Project\Twig;

use Twig\Cache\FilesystemCache;

class Twig_Cache extends FilesystemCache {
	protected $path;

	public function __construct( $path, $options = 0 ) {
		$this->path = $path;
		parent::__construct( $path, $options );
	}

	public function generateKey( $name, $className ) {
		$hash = hash( 'sha256', $className );

		return $this->path . $hash[0] . $hash[1] . '/' . $hash . '.html';
	}
}