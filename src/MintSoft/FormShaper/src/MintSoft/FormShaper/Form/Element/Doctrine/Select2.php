<?php
/**
 * Created by PhpStorm.
 * User: sokool
 * Date: 06.01.15
 * Time: 14:00
 */

namespace MintSoft\FormShaper\Form\Element\Doctrine;

use DoctrineModule\Form\Element\Proxy;
use MintSoft\FormShaper\Form\Element\Select2 as Select2Element;
use Zend\InputFilter\InputProviderInterface;

class Select2 extends Select2Element implements InputProviderInterface
{
    protected $toggle = 'zf2-tag';

    /**
     * @return Proxy
     */
    public function getProxy()
    {
        if (null === $this->proxy) {
            $this->proxy = new Proxy();
        }

        return $this->proxy;
    }

    public function setOptions($options)
    {
        $this->getProxy()->setOptions($options);

        return parent::setOptions($options);
    }

    public function setValue($value)
    {
        $isMultiple = (bool) $this->getToggleAttribute('multiple');
        $method     = 'get' . $this->getProxy()->getProperty();

        if ($isMultiple) {
            $arrayValue = explode(',', $value);
            foreach ($this->getObject($arrayValue) as $object) {
                $init[] = [
                    'id'   => $object->getId(),
                    'text' => $object->{$method}()
                ];
            }
            $this->setToggleAttribute('initSelection', $init);
        } elseif (is_numeric($value)) {

            $object = reset($this->getObject($value));

            $this->setToggleAttribute('initSelection', [
                'id'   => $value = $this->getProxy()->getValue($value),
                'text' => $object->{$method}()
            ]);

        } elseif(is_object($value)) {

            $value = $this->getProxy()->getValue($value);
            $object = $this->getObject($value)[0];
            $this->setToggleAttribute('initSelection', [
                'id'   => $value,
                'text' => $object->{$method}()
            ]);

        }

        return parent::setValue($value);
    }

    protected function getObject($value)
    {
        if(is_object($value)) {
            return $value;
        }

        return $this
            ->getProxy()
            ->getObjectManager()
            ->getRepository($this->getProxy()->getTargetClass())
            ->findById($value);
    }

    public function getInputSpecification()
    {
        $isMultiple = (bool) $this->getToggleAttribute('multiple');

        $spec = array(
            'name'     => $this->getName(),
            'required' => true,
        );

        $spec['allow_empty']       = true;
        $spec['continue_if_empty'] = true;
        $spec['filters']           = array(array(
            'name'    => 'Callback',
            'options' => array(
                'callback' => function ($value) use ($isMultiple) {

                    if (empty($value)) {
                        return;
                    }

                    $property = $this->getProxy()->getProperty();
                    if ($isMultiple) {
                        $value    = explode(',', $value);
                        $newValue = [];
                        foreach ($value as $v) {
                            if (!is_numeric($v)) {
                                $newValue[] = ['id' => null, $property => $v];
                            }
                        }
                    } else {
                        if (!is_numeric($value)) {
                            $newValue = ['id' => null, $property => $value];
                        } else {
                            $newValue = ['id' => $value];
                        }
                    }

                    return $newValue;
                }
            )
        ));

        return $spec;
//        }
    }
}