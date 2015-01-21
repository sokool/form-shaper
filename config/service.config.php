<?php
return [
    'factories' => [
        'HydratorManager'                => function ($sm) {
            $hm = (new \Zend\Mvc\Service\HydratorManagerFactory())->createService($sm);
            $hm->setFactory('DoctrineObjectHydrator', function ($sm) {
                $em = $sm->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $do = new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($em);

                return $do;
            });

            return $hm;
        },
        'MintSoft\FormBakery'            => 'FormShaper\Factory\BakeryFactory',
        'MintSoft\FormBakery\Cache'      => 'FormShaper\Factory\BakeryCacheFactory',
        'MintSoft\FormAnnotationBuilder' => 'FormShaper\Factory\AnnotationBuilderFactory',

    ]
];