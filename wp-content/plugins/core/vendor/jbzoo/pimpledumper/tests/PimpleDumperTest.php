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

namespace JBZoo\PHPUnit;

use JBZoo\PimpleDumper\PimpleDumper;
use Pimple\Container;

/**
 * Class PimpleDumperTest
 * @package JBZoo\PHPUnit
 */
class PimpleDumperTest extends PHPUnit
{
    protected function setUp()
    {
        $file = PROJECT_ROOT . '/pimple.json';
        if (file_exists($file)) {
            unlink($file);
        }

        $file = PROJECT_ROOT . '/.phpstorm.meta.php';
        if (file_exists($file)) {
            if (is_dir($file)) {
                unlink($file . DIRECTORY_SEPARATOR . 'pimple.meta.php');
                rmdir($file);
            }
            else {
                unlink($file);
            }
        }
    }

    public function testSorting()
    {
        $pimple = new Container();
        $dumper = new PimpleDumper();

        $pimple['n_2'] = 2;
        $pimple['n_1'] = 1;
        $pimple['n_3'] = 3;

        isSame(array(
            array('name' => 'n_1', 'type' => 'int', 'value' => 1),
            array('name' => 'n_2', 'type' => 'int', 'value' => 2),
            array('name' => 'n_3', 'type' => 'int', 'value' => 3),
        ), $this->_fromJson($dumper->dumpPimple($pimple)));
    }

    public function testTypes()
    {
        $pimple    = new Container();
        $subPimple = new Container();
        $dumper    = new PimpleDumper();

        $subPimple['t_array'] = array();
        $subPimple['f_array'] = function () {
            return array();
        };

        $pimple['t_array']   = array();
        $pimple['t_pimple']  = $subPimple;
        $pimple['t_string']  = 'qwerty';
        $pimple['t_int']     = 1;
        $pimple['t_float']   = 1.5;
        $pimple['t_bool']    = true;
        $pimple['t_null']    = null;
        $pimple['t_class']   = new \stdClass();
        $pimple['t_closure'] = function () {
        };

        $pimple['f_array']   = function () {
            return array();
        };
        $pimple['f_string']  = function () {
            return 'qwerty';
        };
        $pimple['f_int']     = function () {
            return 1;
        };
        $pimple['f_float']   = function () {
            return 1.5;
        };
        $pimple['f_bool']    = function () {
            return true;
        };
        $pimple['f_null']    = function () {
        };
        $pimple['f_class']   = function () {
            return new \stdClass();
        };
        $pimple['f_closure'] = function () {
            return function () {
            };
        };
        $pimple['f_pimple']  = function () use ($subPimple) {
            return $subPimple;
        };

        $expected = array(
            array('name' => 'f_array', 'type' => 'array', 'value' => ''),
            array('name' => 'f_bool', 'type' => 'bool', 'value' => true),
            array('name' => 'f_class', 'type' => 'class', 'value' => 'stdClass'),
            array('name' => 'f_closure', 'type' => 'closure', 'value' => ''),
            array('name' => 'f_float', 'type' => 'float', 'value' => 1.5),
            array('name' => 'f_int', 'type' => 'int', 'value' => 1),
            array('name' => 'f_null', 'type' => 'null', 'value' => ''),
            array('name' => 'f_pimple', 'type' => 'container', 'value' => array(
                array('name' => 'f_array', 'type' => 'array', 'value' => ''),
                array('name' => 't_array', 'type' => 'array', 'value' => ''),
            )),
            array('name' => 'f_string', 'type' => 'string', 'value' => 'qwerty'),
            array('name' => 't_array', 'type' => 'array', 'value' => ''),
            array('name' => 't_bool', 'type' => 'bool', 'value' => true),
            array('name' => 't_class', 'type' => 'class', 'value' => 'stdClass'),
            array('name' => 't_closure', 'type' => 'null', 'value' => ''),
            array('name' => 't_float', 'type' => 'float', 'value' => 1.5),
            array('name' => 't_int', 'type' => 'int', 'value' => 1),
            array('name' => 't_null', 'type' => 'null', 'value' => ''),
            array('name' => 't_pimple', 'type' => 'container', 'value' => array(
                array('name' => 'f_array', 'type' => 'array', 'value' => ''),
                array('name' => 't_array', 'type' => 'array', 'value' => ''),
            )),
            array('name' => 't_string', 'type' => 'string', 'value' => 'qwerty'),
        );

        isSame($expected, $this->_fromJson($dumper->dumpPimple($pimple)));

        $pimple['f_pimple']['zzz'] = array();
        $expected[7]['value'][2]   = $expected[16]['value'][2] = array(
            'name'  => 'zzz',
            'type'  => 'array',
            'value' => '',
        );

        isSame($expected, $this->_fromJson($dumper->dumpPimple($pimple)));
    }

    public function testAppend()
    {
        $pimple = new Container();
        $dumper = new PimpleDumper();

        $pimple['n_2'] = 2;
        $pimple['n_1'] = 1;
        $pimple['n_3'] = 3;

        $dumper->dumpPimple($pimple);

        $pimple['n_0'] = 0;
        $pimple['n_4'] = 4;

        isSame(array(
            array('name' => 'n_0', 'type' => 'int', 'value' => 0),
            array('name' => 'n_1', 'type' => 'int', 'value' => 1),
            array('name' => 'n_2', 'type' => 'int', 'value' => 2),
            array('name' => 'n_3', 'type' => 'int', 'value' => 3),
            array('name' => 'n_4', 'type' => 'int', 'value' => 4),
        ), $this->_fromJson($dumper->dumpPimple($pimple, true)));
    }

    public function testAutodump()
    {
        $pimple = new Container();
        $dumper = new PimpleDumper();

        $pimple->register($dumper);

        $pimple['auto_2'] = 2;
        $pimple['auto_1'] = 1;

        //$dumper->__destruct();
        unset($dumper);

        isSame(array(
            array('name' => 'auto_1', 'type' => 'int', 'value' => 1),
            array('name' => 'auto_2', 'type' => 'int', 'value' => 2),
        ), $this->_fromJson(PROJECT_ROOT . '/pimple.json'));
    }

    public function testPhpstorm()
    {
        $pimple = new Container();
        $dumper = new PimpleDumper();

        $pimple['f_class'] = function () {
            return new \stdClass();
        };

        isFile($dumper->dumpPhpstorm($pimple));
    }

    public function testPhpstormFile()
    {
        mkdir('.phpstorm.meta.php');
        $pimple = new Container();
        $dumper = new PimpleDumper();

        $pimple['f_class'] = function () {
            return new \stdClass();
        };

        isFile($dumper->dumpPhpstorm($pimple));
    }

    public function testSetRoot()
    {
        $pimple = new Container();
        $dumper = new PimpleDumper();
        $dumper->setRoot(__DIR__);

        $pimple['num'] = 2;
        $filename      = $dumper->dumpPimple($pimple);

        isSame(array(
            array('name' => 'num', 'type' => 'int', 'value' => 2),
        ), $this->_fromJson($filename));
    }

    /**
     * @expectedException \JBZoo\PimpleDumper\Exception
     */
    public function testSetRootUndefined()
    {
        $dumper = new PimpleDumper();
        $dumper->setRoot('undefined');
    }

    public function testMergePlain()
    {
        $pimple = new Container();
        $dumper = new PimpleDumper();

        // before merge
        $pimple['num_0'] = 0;
        $pimple['num_1'] = 42;
        $beforeJson      = $this->_fromJson($dumper->dumpPimple($pimple, true));
        isSame(array(
            array("name" => "num_0", "type" => "int", "value" => 0),
            array("name" => "num_1", "type" => "int", "value" => 42),
        ), $beforeJson);

        // after merge
        $pimple['num_0'] = 41;
        $pimple['num_2'] = 43;
        $afterJson       = $this->_fromJson($dumper->dumpPimple($pimple, true));

        isSame(array(
            array("name" => "num_0", "type" => "int", "value" => 0),
            array("name" => "num_1", "type" => "int", "value" => 42),
            array("name" => "num_2", "type" => "int", "value" => 43),
        ), $afterJson);
    }

    public function testReplaceExistsObject()
    {
        $pimple = new Container();
        $dumper = new PimpleDumper();

        $pimple['cont'] = new \stdClass();
        $dumper->dumpPimple($pimple, true);

        $pimple['cont'] = new Container();

        isSame(array(
            array(
                "name"  => "cont",
                "type"  => "class",
                "value" => "stdClass",
            ),
        ), $this->_fromJson($dumper->dumpPimple($pimple, true)));
    }

    public function testMergeNested()
    {
        $pimple = new Container();
        $dumper = new PimpleDumper();

        $pimple['con_0']              = new Container();
        $pimple['con_0']['num_0']     = 41;
        $pimple['con_0']['con_empty'] = new Container();

        $dumper->dumpPimple($pimple, true);

        $pimple['con_0']          = new Container();
        $pimple['con_0']['num_0'] = 'NO_VALID';
        $pimple['con_0']['num_1'] = 42;
        $pimple['con_0']['num_2'] = 43;

        isSame(array(
            array(
                "name"  => "con_0",
                "type"  => "container",
                "value" => array(
                    array("name" => "con_empty", "type" => "container", "value" => array()),
                    array("name" => "num_0", "type" => "int", "value" => 41),
                    array("name" => "num_1", "type" => "int", "value" => 42),
                    array("name" => "num_2", "type" => "int", "value" => 43),
                ),
            ),
        ), $this->_fromJson($dumper->dumpPimple($pimple, true)));
    }

    /**
     * @param string $file
     * @return array
     */
    protected function _fromJson($file)
    {
        $data = json_decode(file_get_contents($file), true);
        return $data;
    }
}
