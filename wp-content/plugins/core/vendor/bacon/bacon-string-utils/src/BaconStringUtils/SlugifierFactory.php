<?php
/**
 * BaconStringUtils
 *
 * @link      http://github.com/Bacon/BaconStringUtils For the canonical source repository
 * @copyright 2011-2014 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace BaconStringUtils;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SlugifierFactory implements FactoryInterface
{
    /**
     * @return Slugifier
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $decoder   = $serviceLocator->get('BaconStringUtils\UniDecoder');
        $slugifier = new Slugifier();
        $slugifier->setUniDecoder($decoder);

        return $slugifier;
    }
}
