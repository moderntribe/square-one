<?php

class RequestTest extends \Codeception\Test\Unit {
	/**
	 * @var \UnitTester
	 */
	protected $tester;
	private $backup_SERVER;

	protected function _before() {
		parent::_before();
		$this->backup_SERVER = $_SERVER;
	}

	protected function _after() {
		$_SERVER = $this->backup_SERVER;
		parent::_after();
	}


	/*public function testQuery() {
		include_once( __DIR__ . '/../../../../wp/wp-includes/class-wp-query.php' );
		$server_mock = $this->makeEmpty( Tribe\Project\Request\Server::class, [ 'get_query' => function () {
			return new \WP_Query();
		} ] );
		$request     = new \Tribe\Project\Request\Request( $server_mock );
		$this->assertTrue( is_a( $request->query(), '\WP_Query' ) );
	}

	public function testHeaders() {
		$expected    = [ 'foo' => 'bar', 'bash' => 'baz' ];
		$server_mock = $this->makeEmpty( Tribe\Project\Request\Server::class, [ 'get_headers' => function () use ( $expected ) {
			return $expected;
		} ] );
		$request     = new \Tribe\Project\Request\Request( $server_mock );
		$this->assertEquals( $expected, $request->headers() );
	}

	public function testHeader() {
		$headers     = [ 'foo' => 'bar', 'bash' => 'baz' ];
		$expected    = $headers['foo'];
		$server_mock = $this->makeEmpty( Tribe\Project\Request\Server::class, [ 'get_headers' => function () use ( $headers ) {
			return $headers;
		} ] );
		$request     = new \Tribe\Project\Request\Request( $server_mock );
		$this->assertEquals( $expected, $request->header( 'foo' ) );
	}

	public function testInput() {
		$input          = [ 'foo' => 'bar', 'bash' => 'baz', 'valid_email' => 'admin@tri.be', 'invalid_email' => 'invalid' ];
		$expected       = $input['foo'];
		$expected_email = $input['valid_email'];
		$server_mock    = $this->makeEmpty( Tribe\Project\Request\Server::class, [ 'get_input' => function () use ( $input ) {
			return $input;
		} ] );
		$request        = new \Tribe\Project\Request\Request( $server_mock );
		$this->assertEquals( $expected, $request->input( 'foo' ) );
		$this->assertEquals( $expected_email, $request->input( 'valid_email', FILTER_VALIDATE_EMAIL ) );
		$this->assertFalse( $request->input( 'invalid_email', FILTER_VALIDATE_EMAIL ) );
	}

	public function testAll() {
		$input            = [ 'foo' => 'bar', 'bash' => 'baz' ];
		$query_params     = [ 'this' => 'that' ];
		$server_mock      = $this->makeEmpty( Tribe\Project\Request\Server::class, [
			'get_method'       => function () use ( $input ) {
				return 'GET';
			},
			'get_input'        => function () use ( $input ) {
				return $input;
			},
			'get_query_params' => function () use ( $query_params ) {
				return $query_params;
			},
		] );
		$server_mock_post = $this->makeEmpty( Tribe\Project\Request\Server::class, [
			'get_method'       => function () use ( $input ) {
				return 'POST';
			},
			'get_input'        => function () use ( $input ) {
				return $input;
			},
			'get_query_params' => function () use ( $query_params ) {
				return $query_params;
			},
		] );
		$request          = new \Tribe\Project\Request\Request( $server_mock );
		$request_post     = new \Tribe\Project\Request\Request( $server_mock_post );

		$this->assertEquals( $input, $request->all() );
		$this->assertEquals( $input + $query_params, $request_post->all() );
	}

	public function testOnly() {
		$input       = [ 'foo' => 'bar', 'bash' => 'baz', 'this' => 'that', 'before' => 'after' ];
		$expected    = [ 'foo' => 'bar', 'this' => 'that' ];
		$server_mock = $this->makeEmptyExcept( Tribe\Project\Request\Server::class, 'input', [
			'get_method' => function () {
				return 'GET';
			},
			'get_input'  => function () use ( $input ) {
				return $input;
			},
		] );
		$request     = new \Tribe\Project\Request\Request( $server_mock );
		$this->assertEquals( $expected, $request->only( [ 'foo', 'this' ] ) );
	}

	public function testExcept() {
		$input       = [ 'foo' => 'bar', 'bash' => 'baz', 'this' => 'that', 'before' => 'after' ];
		$expected    = [ 'bash' => 'baz', 'before' => 'after' ];
		$server_mock = $this->makeEmptyExcept( Tribe\Project\Request\Server::class, 'input', [
			'get_method' => function () {
				return 'GET';
			},
			'get_input'  => function () use ( $input ) {
				return $input;
			},
		] );
		$request     = new \Tribe\Project\Request\Request( $server_mock );
		$this->assertEquals( $expected, $request->except( [ 'foo', 'this' ] ) );
	}

	public function testHas() {
		$input       = [ 'foo' => 'bar', 'bash' => 'baz', 'this' => 'that', 'before' => 'after' ];
		$server_mock = $this->makeEmpty( Tribe\Project\Request\Server::class, [ 'get_input' => function () use ( $input ) {
			return $input;
		} ] );
		$request     = new \Tribe\Project\Request\Request( $server_mock );
		$this->assertFalse( $request->has( 'foobar' ) );
		$this->assertTrue( $request->has( 'foo' ) );
	}

	public function testFilled() {
		$input       = [ 'foo' => 'bar', 'bash' => '', 'this' => 'that', 'before' => 'after' ];
		$server_mock = $this->makeEmpty( Tribe\Project\Request\Server::class, [ 'get_input' => function () use ( $input ) {
			return $input;
		} ] );
		$request     = new \Tribe\Project\Request\Request( $server_mock );
		$this->assertFalse( $request->filled( 'bash' ) );
		$this->assertTrue( $request->filled( 'foo' ) );
	}

	public function testPath() {
		$_SERVER['REQUEST_URI'] = '/test_page?foo=bar&bash=baz';
		$path                   = 'test_page';
		$path_params            = 'test_page?foo=bar&bash=baz';
		$server_mock            = $this->makeEmptyExcept( Tribe\Project\Request\Server::class, 'get_path' );
		$request                = new \Tribe\Project\Request\Request( $server_mock );
		$this->assertEquals( $path, $request->path() );
		$this->assertEquals( $path_params, $request->path( true ) );
	}

	public function testUrl() {
		$_SERVER     = json_decode( '{"SERVER_SOFTWARE":"nginx\/1.12.2","REQUEST_URI":"\/","HOSTNAME":"a7332abbfce9","TERM":"linux","COMPOSER_CACHE_DIR":"\/application\/composer-cache","PATH":"\/usr\/local\/sbin:\/usr\/local\/bin:\/usr\/sbin:\/usr\/bin:\/sbin:\/bin","PWD":"\/application","SHLVL":"1","COMPOSER_ALLOW_SUPERUSER":"1","HOME":"\/var\/www","_":"\/usr\/sbin\/php-fpm7.0","USER":"www-data","HTTP_COOKIE":"wp-settings-1=libraryContent%3Dbrowse%26editor%3Dtinymce; wp-settings-time-1=1521836610; XDEBUG_SESSION=PHPSTORM","HTTP_ACCEPT_LANGUAGE":"en-US,en;q=0.9","HTTP_ACCEPT_ENCODING":"gzip, deflate, br","HTTP_ACCEPT":"text\/html,application\/xhtml+xml,application\/xml;q=0.9,image\/webp,image\/apng,*\/*;q=0.8","HTTP_USER_AGENT":"Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/66.0.3359.181 Safari\/537.36","HTTP_UPGRADE_INSECURE_REQUESTS":"1","HTTP_X_FORWARDED_PROTO":"https","HTTP_X_FORWARDED_FOR":"172.20.10.1","HTTP_X_REAL_IP":"172.20.10.1","HTTP_CONNECTION":"close","HTTP_HOST":"square1.tribe","REDIRECT_STATUS":"200","SERVER_NAME":"square1.tribe","SERVER_PORT":"443","SERVER_ADDR":"172.20.10.6","REMOTE_PORT":"45952","REMOTE_ADDR":"172.20.10.100","GATEWAY_INTERFACE":"CGI\/1.1","SERVER_PROTOCOL":"HTTP\/1.1","DOCUMENT_ROOT":"\/application\/www\/wp","DOCUMENT_URI":"\/index.php","SCRIPT_FILENAME":"\/application\/www\/wp\/index.php","SCRIPT_NAME":"\/index.php","CONTENT_LENGTH":"","CONTENT_TYPE":"","REQUEST_METHOD":"GET","QUERY_STRING":"","PHP_VALUE":"error_log=\/proc\/self\/fd\/2","FCGI_ROLE":"RESPONDER","PHP_SELF":"\/index.php","REQUEST_TIME_FLOAT":"1526937785.1017","REQUEST_TIME":"1526937785","HTTPS":"on"}', true );
		$url         = 'https://square1.tribe';
		$server_mock = $this->makeEmptyExcept( Tribe\Project\Request\Server::class, 'get_url' );
		$request     = new \Tribe\Project\Request\Request( $server_mock );
		$this->assertEquals( $url, $request->url() );
	}

	public function testFullURL() {
		$_SERVER     = json_decode( '{"SERVER_SOFTWARE":"nginx\/1.12.2","REQUEST_URI":"\/foobar?foo=bar&bash=baz","HOSTNAME":"a7332abbfce9","TERM":"linux","COMPOSER_CACHE_DIR":"\/application\/composer-cache","PATH":"\/usr\/local\/sbin:\/usr\/local\/bin:\/usr\/sbin:\/usr\/bin:\/sbin:\/bin","PWD":"\/application","SHLVL":"1","COMPOSER_ALLOW_SUPERUSER":"1","HOME":"\/var\/www","_":"\/usr\/sbin\/php-fpm7.0","USER":"www-data","HTTP_COOKIE":"wp-settings-1=libraryContent%3Dbrowse%26editor%3Dtinymce; wp-settings-time-1=1521836610; XDEBUG_SESSION=PHPSTORM","HTTP_ACCEPT_LANGUAGE":"en-US,en;q=0.9","HTTP_ACCEPT_ENCODING":"gzip, deflate, br","HTTP_ACCEPT":"text\/html,application\/xhtml+xml,application\/xml;q=0.9,image\/webp,image\/apng,*\/*;q=0.8","HTTP_USER_AGENT":"Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/66.0.3359.181 Safari\/537.36","HTTP_UPGRADE_INSECURE_REQUESTS":"1","HTTP_X_FORWARDED_PROTO":"https","HTTP_X_FORWARDED_FOR":"172.20.10.1","HTTP_X_REAL_IP":"172.20.10.1","HTTP_CONNECTION":"close","HTTP_HOST":"square1.tribe","REDIRECT_STATUS":"200","SERVER_NAME":"square1.tribe","SERVER_PORT":"443","SERVER_ADDR":"172.20.10.6","REMOTE_PORT":"45952","REMOTE_ADDR":"172.20.10.100","GATEWAY_INTERFACE":"CGI\/1.1","SERVER_PROTOCOL":"HTTP\/1.1","DOCUMENT_ROOT":"\/application\/www\/wp","DOCUMENT_URI":"\/index.php","SCRIPT_FILENAME":"\/application\/www\/wp\/index.php","SCRIPT_NAME":"\/index.php","CONTENT_LENGTH":"","CONTENT_TYPE":"","REQUEST_METHOD":"GET","QUERY_STRING":"","PHP_VALUE":"error_log=\/proc\/self\/fd\/2","FCGI_ROLE":"RESPONDER","PHP_SELF":"\/index.php","REQUEST_TIME_FLOAT":"1526937785.1017","REQUEST_TIME":"1526937785","HTTPS":"on"}', true );
		$url         = 'https://square1.tribe/foobar?foo=bar&bash=baz';
		$server_mock = $this->make( Tribe\Project\Request\Server::class );
		$request     = new \Tribe\Project\Request\Request( $server_mock );
		$this->assertEquals( $url, $request->full_url() );
	}

	public function testIs() {
		$_SERVER['REQUEST_URI'] = '/test_page?foo=bar&bash=baz';
		$path                   = 'test_page';
		$server_mock_simple     = $this->makeEmptyExcept( Tribe\Project\Request\Server::class, 'get_path' );
		$request_simple         = new \Tribe\Project\Request\Request( $server_mock_simple );

		$this->assertTrue( $request_simple->is( $path ) );
		$this->assertFalse( $request_simple->is( 'foobar' ) );

		$_SERVER['REQUEST_URI'] = '/test_page/another?foo=bar&bash=baz';
		$path                   = 'test_page/*';
		$server_mock            = $this->makeEmptyExcept( Tribe\Project\Request\Server::class, 'get_path' );
		$request                = new \Tribe\Project\Request\Request( $server_mock );
		$this->assertTrue( $request->is( $path ) );
	}*/
}
