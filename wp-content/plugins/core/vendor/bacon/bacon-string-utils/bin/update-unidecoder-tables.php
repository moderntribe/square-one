<?php
/**
 * BaconStringUtils
 *
 * @link      http://github.com/Bacon/BaconStringUtils For the canonical source repository
 * @copyright 2011-2013 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

$argv = $_SERVER['argv'];

if (!isset($argv[1])) {
    echo "You must supply a path to a tar.gz package containing a python\n"
       . "release of Unidecode. You can find them at:\n"
       . "https://pypi.python.org/pypi/Unidecode/\n";
    exit(1);
}

$path = $argv[1];

if (!is_file($path) || !is_readable($path)) {
    echo "Supplied path is not a file or not readable\n";
    exit(1);
}

$filename = basename($path);

if (!preg_match('(^Unidecode-([0-9.]+)\.tar\.gz$)', $filename, $matches)) {
    echo "Filename must match the pattern Unidecode-VERSION.tar.gz\n";
    exit(1);
}

$version = $matches[1];

// Let's see if we can open the archive
try {
    $archive = new PharData($path);
} catch (Exception $e) {
    echo "Caught exception while opening archive: " . $e->getMessage() . "\n";
    exit(1);
}

// Seems like it worked, let's clean the table directory and generate new ones
foreach (glob(__DIR__ . '/../src/BaconStringUtils/UniDecoder/*') as $table) {
    unlink($table);
}

$iterator = new RecursiveIteratorIterator($archive);

foreach ($iterator as $file) {
    if (!preg_match('(Unidecode-' . $version . '/unidecode/x([0-9a-f]{3})\.py$)', $file->getPathname(), $matches)) {
        continue;
    }

    $section  = $matches[1];
    $contents = file_get_contents($file->getPathname());

    // Cleanup first
    $contents = preg_replace('(\s*#[\s0-9a-zx]*\n)', '', $contents);
    $contents = preg_replace('(^\s*data\s*=\s*\(\s*\'(.+)\'\s*,\s*\)\s*$)s', '\1', $contents);

    // then parse the table data
    $data = preg_split('(\',\')', $contents);

    // Decode encoded values
    foreach ($data as $key => $datum) {
        if (preg_match('(^\\\\x([0-9a-f]{2})$)', $datum, $matches)) {
            $data[$key] = chr(hexdec($matches[1]));
        } else {
            $data[$key] = stripslashes($datum);
        }
    }

    // Generate array structure
    $code = "<?php\n"
          . "/**\n"
          . " * BaconStringUtils\n"
          . " *\n"
          . " * @link      http://github.com/Bacon/BaconStringUtils For the canonical source repository\n"
          . " * @copyright 2011-2013 Ben Scholzen 'DASPRiD'\n"
          . " * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License\n"
          . " */\n"
          . "\n"
          . "// Generated from UniDecode-" . $version . "\n"
          . "return array(\n";

    foreach ($data as $key => $datum) {
        $ord = ord($datum);

        if ($datum === '') {
            $value = "''";
        } elseif ($ord === 9) {
            $value = '"\t"';
        } elseif ($ord === 10) {
            $value = '"\n"';
        } elseif ($ord >= 32 && $ord <= 126) {
            $value = var_export($datum, true);
        } else {
            $value = '"\x' . str_pad(dechex($ord), 2, '0', STR_PAD_LEFT) . '"';
        }

        $code .= "    " . $value . ",\n";
    }

    $code .= ");\n";

    file_put_contents(__DIR__ . '/../src/BaconStringUtils/UniDecoder/x' . $section . '.php', $code);
}
