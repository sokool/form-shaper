<?php
/**
 * Created by PhpStorm.
 * User: sokool
 * Date: 19.01.15
 * Time: 13:59
 */

namespace FormShaper\Factory;

use Zend\Cache\StorageFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BakeryCacheFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $cacheConfig = $serviceLocator->get('Config')['mintsoft']['form-shaper']['cache'];

        return StorageFactory::factory($cacheConfig);
    }
}