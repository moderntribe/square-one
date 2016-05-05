<?php
/**
 * BaconStringUtils
 *
 * @link      http://github.com/Bacon/BaconStringUtils For the canonical source repository
 * @copyright 2011-2013 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace BaconStringUtils;

use BaconStringUtils\Slugifier;
use PHPUnit_Framework_TestCase as TestCase;

class SlugifierTest extends TestCase
{
    protected $slugifier;

    public function setUp()
    {
        $this->slugifier = new Slugifier();
    }

    public function testSlugifying()
    {
        $this->assertEquals('hello-dont-uber-bacon-no-13', $this->slugifier->slugify('Hello, don\'t "Über"-Bacon No. 13###'));
    }
}