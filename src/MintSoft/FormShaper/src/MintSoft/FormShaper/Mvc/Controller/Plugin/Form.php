<?php

namespace FormShaper\Mvc\Controller\Plugin;

use FormShaper\StdLib\StringifyMethodTrait;
use Zend\Form\Factory;
use Zend\Form\Form as ZendForm;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Class Form
 *
 * @package Flo\Mvc\Controller\Plugin
 */
class Form extends AbstractPlugin
{
    use StringifyMethodTrait;

    /**
     * @param ZendForm $form
     * @param null     $object
     * @param array    $closures
     *
     * @return bool
     */
    public function validate(ZendForm $form, $object = null, array $closures = [])
    {
        $getMethodForm = strtolower($form->getAttribute('method')) == 'get';
        if ($this->controller->getRequest()->isPost() || $getMethodForm) {
            $post  = $this->controller->params()->fromPost();
            $files = $this->controller->params()->fromFiles();

            $data = array_merge_recursive($post, $files);
            if ($getMethodForm) {
                $data = array_merge_recursive($data, $this->controller->getRequest()->getQuery()->toArray());
            } else {
                if (empty($data)) {
                    return false;
                }
            }

            if (is_object($object)) {
                $form
                    ->setBindOnValidate(ZendForm::BIND_MANUAL)
                    ->setObject($object);
            }

            $form->setData($data);
            if ($form->isValid()) {
                if (array_key_exists('onSuccess', $closures) && $closures['onSuccess'] instanceof \Closure) {
                    $closures['onSuccess']($form, $data);
                }

                if (is_object($object)) {
                    //$form->getHydrator()->hydrate($data, $object);
                    $form->bindValues();
                }

                return true;
            } else {
                if (array_key_exists('onFail', $closures) && $closures['onFail'] instanceof \Closure) {
                    $closures['onFail']($form, $data);
                }

                return false;
            }
        }

        return false;
    }

    /**
     * @param      $entity
     * @param null $submitButtonName
     * @param null $cancelButtonName
     *
     * @return ZendForm
     */
    public function bake($entity, $submitButtonName = null, $cancelButtonName = null, $overrideSubmitOptions = null)
    {
        /** @var $entityForm \Zend\Form\Form */
        $entityForm = $this->getController()->getServiceLocator()->get('MintSoft\FormBakery')->bake($entity);

        $submitOptions = [
            'label' => $submitButtonName
        ];

        if (null !== $overrideSubmitOptions) {
            $submitOptions = $overrideSubmitOptions;
        }

        if (null !== $submitButtonName) {
            $entityForm->add([
                'name'       => 'submit',
                'type'       => 'Submit',
                'options'    => $submitOptions,
                'attributes' => [
                    'value' => $submitButtonName,
                    'id'    => 'submit',
                ]
            ]);
        }

        if (null !== $cancelButtonName) {
            $entityForm->add([
                'name'       => 'cancel',
                'type'       => 'button',
                'options'    => [
                    'label' => $cancelButtonName,
                ],
                'attributes' => [
                    'class' => 'grey',
                    'id'    => 'cancel',
                ]
            ]);
        }

        return $entityForm;
    }

    /**
     * @param ZendForm $form
     * @param array    $fields
     */
    public function excludeValidation(ZendForm $form, array $fields)
    {
        $vGroup = [];
        foreach ($form->getElements() as $element) {
            if (!in_array($element->getName(), $fields)) {
                $vGroup[] = $element->getName();
            }
        }
        $form->setValidationGroup($vGroup);
    }

    /**
     * Return form by Fully Qualified Domain Name
     *
     * @param string $fqdn
     *
     * @return ZendForm
     */
    public function load($fqdn)
    {
        /** @var $formFactory Factory */
        $formFactory = $this->controller->getServiceLocator()->get('MintSoft\FormAnnotationBuilder')->getFormFactory();
        $form        = $formFactory->getFormElementManager()->get($fqdn);

        $form->setFormFactory($formFactory);

        return $form;
    }
}