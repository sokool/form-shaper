<?php
namespace FormShaper;

use Zend\ModuleManager\Feature\ControllerPluginProviderInterface;
use Zend\ModuleManager\Feature\FormElementProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

class Module implements ControllerPluginProviderInterface, FormElementProviderInterface, ServiceProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getFormElementConfig()
    {
        return include __DIR__ . '/config/form_element.config.php';
    }

    public function getControllerPluginConfig()
    {
        return include __DIR__ . '/config/plugin.config.php';
    }
    public function getServiceConfig()
    {
        return include __DIR__ . '/config/service.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
