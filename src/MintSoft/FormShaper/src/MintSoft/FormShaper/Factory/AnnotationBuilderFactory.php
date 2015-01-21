<?php
/**
 * Created by PhpStorm.
 * User: sokool
 * Date: 19.01.15
 * Time: 13:29
 */

namespace MintSoft\FormShaper\Factory;

use Zend\Filter\FilterChain;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Form\Factory as FormFactory;
use Zend\InputFilter\Factory as InputFilterFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Validator\ValidatorChain;

class AnnotationBuilderFactory implements FactoryInterface
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
        $filterChain = (new FilterChain)
            ->setPluginManager($serviceLocator->get('filterManager'));

        $validatorChain = (new ValidatorChain)
            ->setPluginManager($serviceLocator->get('ValidatorManager'));

        $filterFactory = (new InputFilterFactory)
            ->setInputFilterManager($serviceLocator->get('InputFilterManager'))
            ->setDefaultValidatorChain($validatorChain)
            ->setDefaultFilterChain($filterChain);

        $formFactory = (new FormFactory($serviceLocator->get('FormElementManager')))
            ->setInputFilterFactory($filterFactory);

        $annotationBuilder = (new AnnotationBuilder)
            ->setFormFactory($formFactory);

        return $annotationBuilder;
    }
}