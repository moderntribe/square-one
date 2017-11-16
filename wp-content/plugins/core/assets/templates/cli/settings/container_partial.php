	}

	public function %1$s( Container $container ) {
		$container[ 'settings.%1$s' ] = function ( Container $container ) {
			return new Settings\%2$s();
		};
		add_action( 'init', function () use ( $container ) {
			$container[ 'settings.%1$s' ]->hook();
		}, 0, 0 );
