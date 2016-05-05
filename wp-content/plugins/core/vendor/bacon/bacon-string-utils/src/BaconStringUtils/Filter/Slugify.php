<?php
/**
 * BaconStringUtils
 *
 * @link      http://github.com/Bacon/BaconStringUtils For the canonical source repository
 * @copyright 2011-2013 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace BaconStringUtils\Filter;

use BaconStringUtils\Slugifier;
use Zend\Filter\FilterInterface;

/**
 * Filter
 */
class Slugify extends Slugifier implements FilterInterface
{
    /**
     * {@inheritdocs}
     */
    public function filter($value)
    {
        return $this->slugify($value);
    }
}
