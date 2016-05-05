<?php
/**
 * BaconStringUtils
 *
 * @link      http://github.com/Bacon/BaconStringUtils For the canonical source repository
 * @copyright 2011-2013 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace BaconStringUtils;

use Zend\ModuleManager\Feature;

/**
 * Module providing typed string utilities.
 */
class Module implements 
    Feature\AutoloaderProviderInterface,
    Feature\FilterProviderInterface
{
    /**
     * getAutoloaderConfig(): defined by AutoloaderProviderInterface.
     *
     * @see    AutoloaderProviderInterface::getAutoloaderConfig()
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFilterConfig()
    {
        return array(
            'invokables' => array(
                'slugify' => 'BaconStringUtils\Filter\Slugify',
            ),
        );
    }  
}
