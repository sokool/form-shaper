<?php
namespace MintSoft\FormShaper;

use Zend\Cache\Storage\Adapter\AbstractAdapter as CacheStorageAdapter;
use Zend\Form\Annotation\AnnotationBuilder as FormAnnotationBuilder;
use Zend\Stdlib\ArrayUtils;

/**
 * Created by PhpStorm.
 * User: sokool
 * Date: 19.01.15
 * Time: 14:00
 */
class FormBakery
{
    const CACHE_KEY = 'MintSoftFormBakery';

    /**
     * @var FormAnnotationBuilder
     */
    protected $builder;

    /**
     * @var CacheStorageAdapter
     */
    protected $cacheAdapter;

    public function __construct(FormAnnotationBuilder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param FormAnnotationBuilder $builder
     *
     * @return $this
     */
    public function setAnnotationBuilder(FormAnnotationBuilder $builder)
    {
        $this->builder = $builder;

        return $this;
    }

    /**
     * @return FormAnnotationBuilder
     */
    public function getAnnotationBuilder()
    {
        return $this->builder;
    }

    /**
     * @param CacheStorageAdapter $cacheAdapter
     *
     * @return $this
     */
    public function setCacheAdapter(CacheStorageAdapter $cacheAdapter)
    {
        $this->cacheAdapter = $cacheAdapter;

        return $this;
    }

    /**
     * @return CacheStorageAdapter|null
     */
    public function getCacheAdapter()
    {
        return $this->cacheAdapter;
    }

    /**
     * @param $entity
     *
     * @return \Zend\Form\Form
     */
    public function bake($entity)
    {
        $entityHash   = $this->generateCacheKey($entity);
        $formFactory  = $this->getAnnotationBuilder()->getFormFactory();
        $cacheAdapter = $this->getCacheAdapter();
        $formSpecs    = unserialize($cacheAdapter->getItem($entityHash));

        if (empty($formSpecs)) {
            $formSpecs = ArrayUtils::iteratorToArray($this->getAnnotationBuilder()->getFormSpecification($entity));
            $cacheAdapter->setItem($entityHash, serialize($formSpecs));
        }

        return $formFactory->createForm($formSpecs);
    }

    /**
     * @param $entity
     *
     * @return string
     */
    private function generateCacheKey($entity)
    {
        $entityHash = is_object($entity) ? get_class($entity) : $entity;

        return md5($entityHash . ':' . self::CACHE_KEY);
    }
}