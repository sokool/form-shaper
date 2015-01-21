<?php
return [
    'invokables' => [
        'MintSoft\DataToggle' => 'FormShaper\Form\Element\DataToggle',
        'MintSoft\Select2'    => 'FormShaper\Form\Element\Select2',
    ],
    'factories'  => [
        'MintSoft\Doctrine\Select2' => function ($sm) {
            $entityManager = $sm->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $element       = new \FormShaper\Form\Element\Doctrine\Select2;

            $element->getProxy()->setObjectManager($entityManager);

            return $element;
        },
    ],
];
