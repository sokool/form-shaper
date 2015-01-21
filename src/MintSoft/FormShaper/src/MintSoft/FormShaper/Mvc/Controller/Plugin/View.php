<?php

namespace FormShaper\Mvc\Controller\Plugin;

use FormShaper\StdLib\StringifyMethodTrait;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class View extends AbstractPlugin
{
    use StringifyMethodTrait;

    public function model($templateName = null, array $params = array())
    {
        return (new ViewModel($params))->setTemplate($templateName);
    }

    public function json($params)
    {
        return new JsonModel($params);
    }

    /**
     * @codeCoverageIgnore
     *
     * @param        $templateName
     * @param  array $params
     *
     * @return mixed
     */
    public function render($templateName, array $params = array())
    {
        $view = ($templateName instanceof ViewModel) ? $templateName : $this->model($templateName, $params)->setTerminal(true);

        return $this
            ->getController()
            ->getServiceLocator()
            ->get('ViewRenderer')
            ->render($view);
    }
}
