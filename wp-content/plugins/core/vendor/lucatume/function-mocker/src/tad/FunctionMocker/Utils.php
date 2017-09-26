<?php

namespace tad\FunctionMocker;


class Utils
{
	/**
	 * @var string
	 */
	protected static $vendorDir;

	public static function filterPathListFrom(array $list, $rootDir)
    {
        \Arg::_($rootDir, 'Root dir')->assert(is_dir($rootDir), 'Root dir must be an existing dir');

        $_list = array_map(function ($frag) use ($rootDir) {
            $path = $rootDir . DIRECTORY_SEPARATOR . self::normalizePathFrag($frag);

            return file_exists($path) ? $path : null;
        }, $list);

        return array_filter($_list);
    }

    public static function normalizePathFrag($path)
    {
        \Arg::_($path, 'Path')->is_string();

        return trim(trim($path), '/');
    }

    public static function includePatchwork()
    {
		if (function_exists('Patchwork\replace')) {
			return;
		}
		require_once Utils::getVendorDir('antecedent/patchwork/Patchwork.php');
    }

    public static function findParentContainingFrom($children, $cwd)
    {
        $dir = $cwd;
        $children = '/' . self::normalizePathFrag($children);
        while (true) {
            if (file_exists($dir . $children)) {
                break;
            } else {
                $dir = dirname($dir);
            }
        }

        return $dir;
    }

    /**
     * Gets the absolute path to the `vendor` dir optionally appending a path.
     *
     * @param string $path The relative path with no leading slash.
     *
     * @return string The absolute path to the file.
     */
    public static function getVendorDir($path = '')
    {
        $root = __DIR__;
        while (self::$vendorDir === null) {
            foreach (scandir($root, SCANDIR_SORT_ASCENDING) as $dir) {
                if (is_dir($root . '/' . implode(DIRECTORY_SEPARATOR, [$dir, 'antecedent', 'patchwork']))) {
                    self::$vendorDir = realpath($root . '/' . $dir);
                    break;
                }
            }
            $root = dirname($root);
        }

        return empty($path) ? self::$vendorDir : self::$vendorDir . '/'.$path;
    }
}

