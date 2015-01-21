<?php
/**
 * Created by PhpStorm.
 * User: sokool
 * Date: 19.01.15
 * Time: 13:25
 */

namespace FormShaper\Factory;

use FormShaper\FormBakery;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BakeryFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $cache   = $serviceLocator->get('MintSoft\FormBakery\Cache');
        $builder = $serviceLocator->get('MintSoft\FormAnnotationBuilder');

        $formBakery = new FormBakery($builder);
        $formBakery->setCacheAdapter($cache);

        return $formBakery;
    }
}