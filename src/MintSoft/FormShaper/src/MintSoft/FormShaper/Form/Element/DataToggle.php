<?php
/**
 * Created by PhpStorm.
 * User: sokool
 * Date: 02.01.15
 * Time: 12:58
 */

namespace FormShaper\Form\Element;

use Zend\Form\Element\Hidden;

/**
 * Example of Annotation usage
 ***************************************************************************
 * @Form\Type("DataToggle")
 * @Form\Attributes({
 *      "toggle" : {
 *          "search_url" : {
 *              "name" : "dictionary-jobs-autocomplete",
 *              "params" : {
 *                  "dictionary" : "job",
 *                  "module" : "candidate"
 *              }
 *          },
 *          "placeholder" : "Start typing to add a job"
 *      }
 * })
 * @Form\Options({
 *      "label" : "Jobs",
 *      "toggle" : {
 *          "table" : "DictionaryJobTree",
 *          "method" : "findByIds",
 *      }
 * })
 ***************************************************************************
 *
 * Class DataToggle
 *
 * It transport array data into HTML element as JSON format.
 *
 * @package Flo\Form\Element
 */
class DataToggle extends Hidden
{
    protected $toggle = null;

    protected $attributes = array(
        'type'         => 'hidden',
        'data-toggle'  => null,
        'data-options' => [],
    );

    protected $options = [
        'toggle' => [],
    ];

    protected $toggleAttributes = [];

    public function __construct($name = null, $options = array())
    {
        $this->setAttribute('data-toggle', $this->toggle);
        parent::__construct($name, $options);
    }

    /**
     * @param array|\Traversable $options
     *
     * @return void|\Zend\Form\Element|\Zend\Form\ElementInterface
     */
    public function setOptions($options)
    {
        parent::setOptions($options);

        if (isset($options['toggle'])) {
            $this->setToggleOptions($options['toggle']);
        }
    }

    /**
     * @param $toggleOptions
     *
     * @return $this
     */
    public function setToggleOptions($toggleOptions)
    {
        if (!is_array($toggleOptions) && !$toggleOptions instanceof \Traversable) {
            throw new \InvalidArgumentException(sprintf(
                '%s expects an array or Traversable argument; received "%s"',
                __METHOD__,
                (is_object($toggleOptions) ? get_class($toggleOptions) : gettype($toggleOptions))
            ));
        }
        foreach ($toggleOptions as $key => $value) {
            $this->setToggleOption($key, $value);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getToggleOptions()
    {
        return $this->options['toggle'];
    }

    /**
     * @param $name
     * @param $value
     *
     * @return $this
     */
    public function setToggleOption($name, $value)
    {
        $this->options['toggle'][$name] = $value;

        return $this;
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function getToggleOption($name)
    {
        if (!array_key_exists($name, $this->options['toggle'])) {
            return;
        }

        return $this->options['toggle'][$name];
    }

    /**
     * @param $name
     * @param $value
     *
     * @return $this
     */
    public function setToggleAttribute($name, $value)
    {
        $attribute = ($this->getToggleAttribute($name));
        if (is_array($attribute)) {
            $value = array_merge($attribute, $value);
        }

        $this->toggleAttributes[$name] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getToggleAttributes()
    {
        return $this->toggleAttributes;
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function getToggleAttribute($name)
    {
        if (!array_key_exists($name, $this->toggleAttributes)) {
            return;
        }

        return $this->toggleAttributes[$name];
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return $this|\Zend\Form\Element|\Zend\Form\ElementInterface
     */
    public function setAttribute($key, $value)
    {
        if ($key == 'toggle' && is_array($value)) {
            foreach ($value as $name => $v) {
                $this->setToggleAttribute($name, $v);
            }

            return $this;
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * @return array|\Traversable
     */
    public function getAttributes()
    {
        $this->setAttribute('data-options', json_encode($this->getToggleAttributes()));

        return parent::getAttributes();
    }
}
