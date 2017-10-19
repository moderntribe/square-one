<?php
/**
 * JBZoo PimpleDumper
 *
 * This file is part of the JBZoo CCK package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package   PimpleDumper
 * @license   MIT
 * @copyright Copyright (C) JBZoo.com,  All rights reserved.
 * @link      https://github.com/JBZoo/PimpleDumper
 * @author    Denis Smetannikov <denis@jbzoo.com>
 */

namespace JBZoo\PimpleDumper;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class PimpleDumper
 * @package JBZoo\PimpleDumper
 */
class PimpleDumper implements ServiceProviderInterface
{
    const FIND_IN_ROOT  = '.idea';
    const FILE_PHPSTORM = '.phpstorm.meta.php';
    const FILE_PIMPLE   = 'pimple.json';

    /**
     * @var Container
     */
    protected $_container;

    /**
     * @var string
     */
    protected $_root;

    /**
     * PimpleDumper constructor.
     */
    public function __construct()
    {
        $this->_root = $this->_findRoot();
    }

    /**
     * {@inheritdoc}
     */
    public function register(Container $pimple)
    {
        $this->_container = $pimple;
    }

    /**
     * @param string $root
     * @throws Exception
     */
    public function setRoot($root)
    {
        if ($root && $realRoot = realpath($root)) {
            $this->_root = $realRoot;
        } else {
            throw new Exception('New root path is not real: ' . $root);
        }
    }

    /**
     * Autodump via destructor and register ServiceProviderInterface
     */
    public function __destruct()
    {
        if ($this->_container) {
            $this->dumpPimple($this->_container, true);
            //$this->dumpPhpstorm($this->_container);
        }
    }

    /**
     * @param Container $container
     * @param bool      $isAppend
     * @return string
     */
    public function dumpPimple(Container $container, $isAppend = false)
    {
        $map = $this->_parseContainer($container);
        return $this->_writeJSON($map, $isAppend);
    }

    /**
     * @param Container $container
     * @return string
     */
    public function dumpPhpstorm(Container $container)
    {
        $map = $this->_parseContainer($container);
        return $this->_writePHPStorm($map, get_class($container));
    }

    /**
     * @return string
     */
    protected function _findRoot()
    {
        $path = __DIR__;

        $dumpPath = null;

        for ($i = 0; $i <= 100; $i++) {
            $realpath = realpath($path . '/' . self::FIND_IN_ROOT);

            if ($realpath) {
                $dumpPath = realpath($path);
                break;
            }

            $path = realpath($path . '/..');
            if (!$path) {
                break;
            }
        }

        if (!$dumpPath) {
            $dumpPath = realpath(__DIR__ . '/..'); // fallback
        }

        return $dumpPath;
    }

    /**
     * Generate a mapping of the container's values
     * @param Container $container
     * @return array
     */
    protected function _parseContainer(Container $container)
    {
        $map = array();

        foreach ($container->keys() as $name) {
            if ($item = $this->_parseItem($container, $name)) {
                $map[] = $item;
            }
        }

        $map = $this->_normalizeMap($map);

        return $map;
    }

    /**
     * @param array $map
     * @return array
     */
    protected function _normalizeMap($map)
    {
        $map = (array)$map;

        usort($map, function ($itemA, $itemB) {
            return strcmp($itemA['name'], $itemB['name']);
        });

        $map = array_filter($map);
        $map = array_values($map);

        return $map;
    }

    /**
     * Parse the item's type and value
     *
     * @param Container $container
     * @param string    $name
     *
     * @return array|null
     */
    protected function _parseItem(Container $container, $name)
    {
        try {
            $element = $container[$name];
        } catch (\Exception $e) {
            return null;
        }

        if (is_object($element)) {
            if ($element instanceof \Closure) {
                $type  = 'closure';
                $value = '';

            } elseif ($element instanceof Container) {
                $value = $this->_parseContainer($element);
                $type  = is_array($value) ? 'container' : 'class';

            } else {
                $type  = 'class';
                $value = get_class($element);
            }

        } elseif (is_array($element)) {
            $type  = 'array';
            $value = '';

        } elseif (is_string($element)) {
            $type  = 'string';
            $value = $element;

        } elseif (is_int($element)) {
            $type  = 'int';
            $value = $element;

        } elseif (is_float($element)) {
            $type  = 'float';
            $value = $element;

        } elseif (is_bool($element)) {
            $type  = 'bool';
            $value = $element;

        } elseif ($element === null) {
            $type  = 'null';
            $value = '';

        } else {
            $type  = 'unknown';
            $value = gettype($element);
        }

        return array(
            'name'  => $name,
            'type'  => $type,
            'value' => $value,
        );
    }

    /**
     * @param array $oldMap
     * @param array $newMap
     * @return array
     */
    protected function _merge($oldMap, $newMap)
    {
        $result = array();

        foreach ($oldMap as $dataOld) {
            $result[$dataOld['name']] = $dataOld;
        }

        foreach ($newMap as $dataNew) {
            $name = $dataNew['name'];

            if ($dataNew['type'] === 'container' && $result[$name]['type'] !== 'class') {
                $nastedOld        = isset($result[$name]['value']) ? $result[$name]['value'] : array();
                $dataNew['value'] = $this->_merge($nastedOld, $dataNew['value']);
                $result[$name]    = $dataNew;
            }

            if (!isset($result[$name])) {
                $result[$name] = $dataNew;
            }
        }

        $result = $this->_normalizeMap($result);

        return $result;
    }

    /**
     * Dump mapping to file
     *
     * @param array $map
     * @param bool  $isAppend
     * @return string
     */
    protected function _writeJSON($map, $isAppend = false)
    {
        $fileName = $this->_root . DIRECTORY_SEPARATOR . self::FILE_PIMPLE;

        if ($isAppend && file_exists($fileName)) {
            $content = file_get_contents($fileName);
            $oldMap  = @json_decode($content, true);
            $map     = $this->_merge((array)$oldMap, (array)$map);
        }

        if (defined('JSON_PRETTY_PRINT')) {
            $content = json_encode($map, JSON_PRETTY_PRINT);
        } else {
            $content = json_encode($map);
        }

        $this->_updateFile($fileName, $content);

        return $fileName;
    }

    /**
     * Dump mapping to phpstorm meta file
     *
     * @param array  $map
     * @param string $className
     * @return string
     */
    protected function _writePHPStorm($map, $className)
    {
        $fileName = $this->_root . DIRECTORY_SEPARATOR . self::FILE_PHPSTORM;

        $list = array();
        foreach ($map as $data) {
            if ($data['type'] === 'class') {
                $list[] = "            '{$data['name']}' instanceof {$data['value']},";
            }
        }

        $tmpl = array(
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
            '    $STATIC_METHOD_TYPES = [',
            '        new \\' . $className . ' => [',
            '            \'\' == \'@\',',
            implode("\n", $list),
            '        ],',
            '    ];',
            '',
            '}',
            '',
        );

        $content = implode("\n", $tmpl);

        $this->_updateFile($fileName, $content);

        return $fileName;
    }

    /**
     * Update file
     * Prevent file lastModified time change
     *
     * @param string $fileName
     * @param string $content
     * @return mixed
     */
    protected function _updateFile($fileName, $content)
    {
        $oldContent = null;
        if (file_exists($fileName)) {
            $oldContent = file_get_contents($fileName);
        }

        if ($content !== $oldContent) {
            file_put_contents($fileName, $content);
        }

        return $fileName;
    }
}
