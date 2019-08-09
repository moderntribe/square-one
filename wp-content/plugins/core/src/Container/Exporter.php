<?php


namespace Tribe\Project\Container;

use Tribe\Project\Container\Service_Provider;
use JBZoo\PimpleDumper\PimpleDumper;
use Pimple\Container;

class Exporter extends PimpleDumper {
	/**
	 * @var Service_Provider[]
	 */
	protected $providers = [];

	public function __construct( array $providers ) {
		parent::__construct();
		$this->providers = $providers;
	}

	public function dumpPhpstorm( Container $container ) {
		$map          = $this->_parseContainer( $container );
		$constant_map = $this->_mapConstants( $this->providers );

		return $this->_writePHPStorm( $map, get_class( $container ), $constant_map );
	}

	protected function _mapConstants( $providers ) {
		$map = [];
		foreach ( $providers as $provider ) {
			$class      = get_class( $provider );
			$reflection = new \ReflectionClass( $class );
			$constants  = $reflection->getConstants();
			foreach ( $constants as $constant => $value ) {
				if ( is_string( $value ) ) {
					$map[ $value ] = $class . '::' . $constant;
				}
			}
		}

		return $map;
	}

	/**
	 * Dump mapping to phpstorm meta file
	 *
	 * @param array  $map
	 * @param string $className
	 * @param array  $constant_map
	 *
	 * @return string
	 */
	protected function _writePHPStorm( $map, $className, $constant_map = [] ) {
		$fileName = $this->_root . DIRECTORY_SEPARATOR . self::FILE_PHPSTORM;

		if ( is_dir( $fileName ) ) {
			$fileName .= DIRECTORY_SEPARATOR . 'pimple.meta.php';
		}

		$list = [];
		foreach ( $map as $data ) {
			if ( $data['type'] === 'class' && strpos( $data['value'], 'class@anonymous' ) !== 0 ) {
				$list[] = "            '{$data['name']}' => " . '\\' . "{$data['value']}::class,";
				if ( array_key_exists( $data['name'], $constant_map ) ) {
					$list[] = '            \\' . "{$constant_map[$data['name']]} => " . '\\' . "{$data['value']}::class,";
				}
			}
		}

		$tmpl = [
			'<?php',
			'/**',
			' * ProcessWire PhpStorm Meta',
			' *',
			' * This file is not a CODE, it makes no sense and won\'t run or validate',
			' * Its AST serves PhpStorm IDE as DATA source to make advanced type inference decisions.',
			' * ',
			' * @see https://confluence.jetbrains.com/display/PhpStorm/PhpStorm+Advanced+Metadata',
			' */',
			'',
			'namespace PHPSTORM_META {',
			'',
			'    override( new \\' . $className . ',',
			'        map( [',
			'            \'\' => \'@\',',
			implode( "\n", $list ),
			'        ],',
			'    ) );',
			'',
			'}',
			'',
		];

		$content = implode( "\n", $tmpl );

		$this->_updateFile( $fileName, $content );

		return $fileName;
	}
}
