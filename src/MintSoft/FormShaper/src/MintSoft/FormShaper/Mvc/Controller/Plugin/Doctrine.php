<?php
/**
 * Created by PhpStorm.
 * User: sokool
 * Date: 30.12.14
 * Time: 15:24
 */

namespace MintSoft\FormShaper\Mvc\Controller\Plugin;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query as DoctrineQuery;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrinePaginationAdapter;
use MintSoft\FormShaper\StdLib\StringifyMethodTrait;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Paginator\Paginator as ZendPaginator;

class Doctrine extends AbstractPlugin
{
    use StringifyMethodTrait;

    const
        MANAGER_ORM = 'orm',
        MANAGER_ODM = 'odm';

    public static $objectsNamespace = '\Application\Entity\\';

    public static $manager = self::MANAGER_ORM;

    protected $managers = [
        self::MANAGER_ODM => 'Doctrine\ODM\Mongo\DocumentManager',
        self::MANAGER_ORM => 'Doctrine\ORM\EntityManager',
    ];

    public function __construct()
    {
        $this->methodMap = [
            'rm' => 'remove',
            'pf' => 'persistAndFlush',
            'pr' => 'paginateWithRepository',
            'm'  => 'manager',
            'p'  => 'persist',
            'r'  => 'repository',
            'f'  => 'flush',
            'h'  => 'hydrate',
            'e'  => 'extract',
        ];
    }

    /**
     *
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    public function manager()
    {
        return $this->getController()->getServiceLocator()->get($this->managers[self::$manager]);
    }

    /**
     * @param $className
     *
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function repository($className)
    {
        return $this->manager()->getRepository($this->toFQDN($className));
    }

    /**
     * @param $objectOrTraversable
     *
     * @return $this
     */
    public function persist($objectOrTraversable)
    {
        foreach ($this->toCollection($objectOrTraversable) as $object) {
            $this->manager()->persist($object);
        }

        return $this;
    }

    /**
     * @param $objectOrTraversable
     *
     * @return $this
     */
    public function persistAndFlush($objectOrTraversable)
    {
        return $this->persist($objectOrTraversable)->flush();
    }

    /**
     * @param $objectOrTraversable
     *
     * @return $this
     */
    public function remove($objectOrTraversable)
    {
        foreach ($this->toCollection($objectOrTraversable) as $object) {
            $this->manager()->remove($object);
        }

        return $this;
    }

    /**
     * @param object $object
     *
     * @return $this
     */
    public function flush($object = null)
    {
        $this->manager()->flush($object);

        return $this;
    }

    /**
     * @param $className
     * @param $data
     *
     * @return object
     */
    public function hydrate($className, $data)
    {
        $className = $this->toFQDN($className);

        return (new DoctrineHydrator($this->manager(), $className))
            ->hydrate($data, new $className);
    }

    /**
     * @param $objectOrTraversable
     *
     * @return array
     */
    public function extract($objectOrTraversable)
    {
        $hydrator = new DoctrineHydrator($this->manager());
        $result   = [];
        foreach ($this->toCollection($objectOrTraversable) as $object) {
            $result[] = $hydrator->extract($object);
        }

        return $result;
    }

    /**
     * TODO
     */
    public function paginate(DoctrineQuery $query, $pageNumber = 0, $elements = 10)
    {
        return (new ZendPaginator(new DoctrinePaginationAdapter(new DoctrinePaginator($query))))
            ->setItemCountPerPage($elements)
            ->setCurrentPageNumber($pageNumber);
    }

    public function paginateWithRepository($repoName, $repoMethod, array $repoArgs = [], $page = 0, $items = 10)
    {
        return $this
            ->paginate(
                call_user_func_array([$this->repository($repoName), $repoMethod], $repoArgs),
                $page,
                $items
            );
    }

    /**
     * @param $objectOrTraversable
     *
     * @return array|Collection|Traversable
     */
    private function toCollection($objectOrTraversable)
    {
        $collection = [];
        if (!($objectOrTraversable instanceof Collection) && !is_array($objectOrTraversable) && !($objectOrTraversable instanceof Traversable)) {
            $collection[] = $objectOrTraversable;
        } else {
            $collection = $objectOrTraversable;
        }

        return $collection;
    }

    /**
     * @param $className
     *
     * @return string
     */
    private function toFQDN($className)
    {
        $className = ucfirst($className);
        if (class_exists(self::$objectsNamespace . $className)) {
            $className = self::$objectsNamespace . $className;
        }

        return $className;
    }
}
