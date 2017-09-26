<?php
/**
 * BaconStringUtils
 *
 * @link      http://github.com/Bacon/BaconStringUtils For the canonical source repository
 * @copyright 2011-2014 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace BaconStringUtils\Filter;

use BaconStringUtils\Slugifier;
use Zend\Filter\FilterInterface;

class Slugify implements FilterInterface
{
    /**
     * @var Slugifier
     */
    protected $slugifier;

    /**
     * {@inheritdoc}
     */
    public function filter($value)
    {
        return $this->slugifier->slugify($value);
    }

    /**
     * @param Slugifier $slugifier
     */
    public function setSlugifier(Slugifier $slugifier)
    {
        $this->slugifier = $slugifier;
    }

    /**
     * @return Slugifier
     */
    public function getSlugifier()
    {
        return $this->slugifier;
    }
}
